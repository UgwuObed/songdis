<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendTemplatedEmail(string $templateName, array $data, string $to): void
    {
        try {
            $template = EmailTemplate::where('name', $templateName)->firstOrFail();
            
            $subject = $this->replaceVariables($template->subject, $data);
            $content = $this->replaceVariables($template->content, $data);

            Mail::html($content, function($message) use ($to, $subject) {
                $message->to($to)
                       ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        return $content;
    }
}
