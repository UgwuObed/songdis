<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Services\ZeptoMailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BulkEmailController extends Controller
{
    protected $zeptoMailService;

    public function __construct(ZeptoMailService $zeptoMailService)
    {
        $this->zeptoMailService = $zeptoMailService;
    }

    public function sendBulkWelcomeEmails(Request $request)
    {
        try {
            if ($request->user()->account_type !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized access.',
                ], 403);
            }

            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'template_id' => 'required|exists:email_templates,id'  
            ]);

            $template = EmailTemplate::find($request->template_id);
            
            if (!$template) {
                Log::warning('Email template not found.');
                return response()->json([
                    'message' => 'Email template not found.',
                ], 404);
            }

            $successCount = 0;
            $failedEmails = [];

            $users = User::whereIn('id', $request->user_ids)->get();

            foreach ($users as $user) {
                try {
                    $content = str_replace(
                        '{{first_name}}',
                        $user->first_name,
                        $template->content
                    );

                    $this->zeptoMailService->sendEmail(
                        $user->email,
                        $user->first_name,
                        $template->subject,
                        $content
                    );

                    $successCount++;

                } catch (\Exception $e) {
                    Log::error('Failed to send email', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'template_id' => $template->id,
                        'error' => $e->getMessage()
                    ]);

                    $failedEmails[] = [
                        'email' => $user->email,
                        'error' => $e->getMessage()
                    ];
                }
            }

            $message = "$successCount emails sent successfully.";
            if (count($failedEmails) > 0) {
                $message .= " " . count($failedEmails) . " emails failed to send.";
            }

            return response()->json([
                'message' => $message,
                'success_count' => $successCount,
                'failed_emails' => $failedEmails,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in sendBulkWelcomeEmails', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sendWelcomeEmailToAllUsers(Request $request)
    {
        try {
            if ($request->user()->account_type !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized access.',
                ], 403);
            }

            $request->validate([
                'template_id' => 'required|exists:email_templates,id'  
            ]);

            $users = User::query()
                ->when($request->has('created_after'), function($query) use ($request) {
                    return $query->where('created_at', '>=', $request->created_after);
                })
                ->when($request->has('created_before'), function($query) use ($request) {
                    return $query->where('created_at', '<=', $request->created_before);
                })
                ->get();

            $userIds = $users->pluck('id')->toArray();

            
            $request->merge(['user_ids' => $userIds]);
            return $this->sendBulkWelcomeEmails($request);

        } catch (\Exception $e) {
            Log::error('Error in sendWelcomeEmailToAllUsers', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Added new method to list available email templates
    public function listEmailTemplates()
    {
        try {
            $templates = EmailTemplate::select('id', 'name', 'subject')->get();
            
            return response()->json([
                'templates' => $templates
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error in listEmailTemplates', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}