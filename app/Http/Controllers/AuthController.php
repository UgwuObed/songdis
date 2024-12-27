<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use App\Services\ZeptoMailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class AuthController extends Controller

{

    protected $zeptoMailService;

    public function __construct(ZeptoMailService $zeptoMailService)
    {
        $this->zeptoMailService = $zeptoMailService;
    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password is required.',
        ];
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token
        ], 200);
    }
    

    public function register(Request $request)
{
    $messages = [
        'first_name.required' => 'Your first name is required.',
        'last_name.required' => 'Your last name is required.',
        'email.required' => 'An email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'A password is required.',
        'password.min' => 'Your password must be at least 8 characters long.',
        'password.confirmed' => 'Password confirmation does not match.',
        'account_type.required' => 'Please select an account type.',
    ];

    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'account_type' => 'required|string|in:basic,growth,professional,admin',
    ], $messages);

    if ($validator->fails()) {
        \Log::error('Validation Errors: ', $validator->errors()->toArray());
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'account_type' => $request->account_type,
    ]);



    $token = $user->createToken('api-token')->plainTextToken;

    try {
        $template = EmailTemplate::where('name', 'welcome_email')->first();

        if ($template) {
            $content = $template->content;
            $content = str_replace(
                ['{{first_name}}', '{{whatsapp_link}}'],
                [$user->first_name, 'https://chat.whatsapp.com/GAtEskogpSB3miS9DIv7st'],
                $content
            );

            $this->zeptoMailService->sendEmail(
                $user->email,
                $user->first_name,
                $template->subject,
                $content
            );
        } else {
            Log::warning('Welcome email template not found');
        }
    } catch (\Exception $e) {
        Log::error('Failed to send welcome email', ['error' => $e->getMessage()]);
    }

    return response()->json([
        'message' => 'Registration successful.',
        'user' => $user,
        'token' => $token
    ], 201);
}

public function fetchAllUsers(Request $request)
{
    $user = Auth::user();

    if ($user->account_type !== 'admin') {
        Log::warning('Unauthorized access attempt to fetch all users', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'You are not authorized to perform this action.'
        ], 403);
    }

    $users = User::paginate(10);

    $totalUsers = User::count();

    return response()->json([
        'status' => 'success',
        'message' => 'Users fetched successfully.',
        'count' => $totalUsers,
        'data' => $users 
    ], 200);
}


public function forgotPassword(Request $request)
{
    $messages = [
        'email.required' => 'The email address is required.',
        'email.email' => 'Please enter a valid email address.',
    ];

    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users',
    ], $messages);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $token = Str::random(64);

    // Delete any existing reset tokens for this email
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    // Create new password reset token
    DB::table('password_reset_tokens')->insert([
        'email' => $request->email,
        'token' => $token,
        'created_at' => Carbon::now()
    ]);

    try {
        $user = User::where('email', $request->email)->first();
        $template = EmailTemplate::where('name', 'reset_password')->first();

        if ($template) {
            $resetLink = config('app.url') . '/reset-password/' . $token;
            $content = str_replace(
                ['{{first_name}}', '{{reset_link}}'],
                [$user->first_name, $resetLink],
                $template->content
            );

            $this->zeptoMailService->sendEmail(
                $user->email,
                $user->first_name,
                $template->subject,
                $content
            );
        } else {
            Log::warning('Reset password email template not found');
            return response()->json([
                'message' => 'Unable to send reset password email. Please contact support.'
            ], 500);
        }
    } catch (\Exception $e) {
        Log::error('Failed to send reset password email', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Failed to send reset password email. Please try again later.'
        ], 500);
    }

    return response()->json([
        'message' => 'Password reset link has been sent to your email.'
    ], 200);
}

public function resetPassword(Request $request)
{
    $messages = [
        'email.required' => 'The email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'token.required' => 'Reset token is required.',
    ];

    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users',
        'password' => 'required|string|min:8|confirmed',
        'token' => 'required'
    ], $messages);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $tokenData = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$tokenData) {
        return response()->json([
            'message' => 'Invalid reset token or token has expired.'
        ], 422);
    }

    // Check if token is expired (24 hours)
    if (Carbon::parse($tokenData->created_at)->addHours(24)->isPast()) {
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return response()->json([
            'message' => 'Reset token has expired. Please request a new one.'
        ], 422);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete the token after successful reset
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json([
        'message' => 'Password has been successfully reset.'
    ], 200);
}

}

