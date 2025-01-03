<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class UploadAttentionEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'upload_attention'],
            [
                'name' => 'upload_attention',
                'subject' => '⚠️ Your Upload Needs Attention - Songdis',
                'content' => <<<HTML
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #8B0000; background-color: #FFF5F5;">
                        <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; border: 2px solid #FF6B6B;">
                            <h1 style="color: rgb(0, 0, 0);">Hey {{primary_artist}}! 👋</h1>
                            
                            <p style="color: rgb(0, 0, 0); font-size: 16px;">
                                We noticed something that needs your attention with your recent release! 🎵
                            </p>
                            
                            <div style="background-color: #FFE5E5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                <p style="color: rgb(0, 0, 0);">Your release has been flagged for potential copyright matches. Don't worry – we're here to help sort this out! 🤝</p>
                                
                                <p style="color: #C62828; font-size: 14px;">
                                    ⚡ Please provide documentation and clarification to resolve this issue.
                                </p>
                            </div>
                            
                            <p style="color: rgb(0, 0, 0);">
                                You can reach out to our support team in two ways:
                            </p>
                            
                            <div style="text-align: center; margin: 25px 0;">
                                <a href="mailto:support@songdis.com" 
                                   style="background-color: #FF6B6B; 
                                          color: white; 
                                          padding: 12px 25px; 
                                          text-decoration: none; 
                                          border-radius: 5px; 
                                          font-weight: bold;
                                          display: inline-block;
                                          margin: 10px;">
                                    Email Support
                                </a>
                                
                                <a href="https://wa.me/c/2349155335515" 
                                   style="background-color: #25D366; 
                                          color: white; 
                                          padding: 12px 25px; 
                                          text-decoration: none; 
                                          border-radius: 5px; 
                                          font-weight: bold;
                                          display: inline-block;
                                          margin: 10px;">
                                    WhatsApp Support
                                </a>
                            </div>
                            
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #FFE5E5;">
                                <p style="color: #C62828;">We're here to help you resolve this quickly! 💫</p>
                                <p style="color: #B71C1C; font-weight: bold;">
                                    The Songdis Team 🎵
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
HTML,
                'variables' => ['first_name'],
            ]
        );
    }
}