<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class ResetPasswordEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'reset_password'],
            [
                'name' => 'reset_password',
                'subject' => '🔐 Reset Your Password - Songdis',
                'content' => <<<HTML
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #8B0000; background-color: #FFF5F5;">
                        <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; border: 2px solid #FF6B6B;">
                            <h1 style="color: rgb(0, 0, 0);">Hey {{first_name}}! 👋</h1>
                            
                            <p style="color: rgb(0, 0, 0); font-size: 16px;">
                                We heard you needed a password reset! No worries – it happens to the best of us! 🔑
                            </p>
                            
                            <div style="background-color: #FFE5E5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                <p style="color: rgb(0, 0, 0);">Just click the button below to reset your password:</p>
                                
                                <div style="text-align: center; margin: 25px 0;">
                                    <a href="{{reset_link}}" 
                                       style="background-color: #FF6B6B; 
                                              color: white; 
                                              padding: 12px 25px; 
                                              text-decoration: none; 
                                              border-radius: 5px; 
                                              font-weight: bold;">
                                        Reset Password
                                    </a>
                                </div>
                                
                                <p style="color: #C62828; font-size: 14px;">
                                    ⚡ This link will expire in 24 hours for security reasons.
                                </p>
                            </div>
                            
                            <p style="color: rgb(0, 0, 0);">
                                If you didn't request this reset, no worries! Your account is still secure and you can ignore this email. 🛡️
                            </p>
                            
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #FFE5E5;">
                                <p style="color: #C62828;">Need help? We're here for you! 💫</p>
                                <p style="color: #B71C1C; font-weight: bold;">
                                    The Songdis Team 🎵
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
HTML,
                'variables' => ['first_name', 'reset_link'],
            ]
        );
    }
}