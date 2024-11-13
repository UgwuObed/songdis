<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
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
        'account_type' => 'required|string|in:artist,label-paid,label-free',
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


    return response()->json([
        'message' => 'Registration successful.',
        'user' => $user,
        'token' => $token
    ], 201);
}

}

