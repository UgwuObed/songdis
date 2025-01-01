<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class NewYearEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'new_year_2025'],
            [
                'name' => 'new_year_2025',
                'subject' => 'Happy New Year from All of Us at Songdis 🎶',
                'content' => <<<HTML
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    </head>
                    <body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.8; color: #333; background-color: #f8f9fa; margin: 0; padding: 0;">
                        <div style="max-width: 700px; margin: 0 auto; padding: 40px 30px; background-color: white; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <!-- Header Section -->
                            <div style="
                                text-align: center; 
                                margin-bottom: 50px;
                                padding: 40px 30px;
                                background: linear-gradient(135deg, #f6f8ff 0%, #ffffff 100%);
                                border-radius: 12px;
                                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);">
                                
                                <div style="
                                    display: inline-block;
                                    position: relative;
                                    padding: 0 40px;">
                                    
                                    <h1 style="
                                        color: rgb(241, 10, 10);
                                        font-family: 'Arial', sans-serif;
                                        font-size: 42px;
                                        font-weight: 700;
                                        margin: 0;
                                        letter-spacing: -0.5px;
                                        line-height: 1.4;">
                                        Happy New Year 2025! 🎉
                                    </h1>
                                    
                                    <div style="
                                        position: absolute;
                                        bottom: -10px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 80px;
                                        height: 4px;
                                        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
                                        border-radius: 2px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <div style="background-color: #fff; padding: 35px; border-radius: 12px; margin-bottom: 35px;">
                                <p style="font-size: 24px; color: #2d2d2d; margin-bottom: 30px;">
                                    Dear {{first_name}},
                                </p>

                                <p style="font-size: 20px; color: #2d2d2d; margin-bottom: 30px;">
                                    As we step into 2025, we're taking a moment to appreciate the incredible creativity and talent within our community. 
                                    Whether you're already part of the Songdis family or just exploring your musical journey, we're honored to be alongside you.
                                </p>

                                <p style="font-size: 20px; color: #2d2d2d; margin-bottom: 30px;">
                                    This year is a fresh canvas, ready for your music to leave its mark. Let's make it memorable together—full of melodies, 
                                    milestones, and moments to cherish.
                                </p>

                                <!-- Call to Action Section -->
                                <div style="background: linear-gradient(to right,rgb(253, 31, 31),rgb(255, 2, 2)); padding: 30px; border-radius: 12px; color: white; margin: 35px 0;">
                                    <h3 style="margin-top: 0; font-size: 26px;">Ready to make this the year your music reaches new audiences?</h3>
                                    <p style="margin-bottom: 15px; font-size: 20px;">
                                        Start your journey with SongDis today: <a href="www.songdis.com" style="color: white; text-decoration: underline;">www.songdis.com</a>
                                    </p>
                                </div>

                                <p style="font-size: 20px; color: #2d2d2d; margin-bottom: 30px;">
                                    Here's to your success and a year filled with inspiration!
                                </p>

                                <p style="font-size: 20px; margin: 0; font-weight: bold;">
                                    💬 <span style="background-color: #ffd5d5; padding: 4px 10px; border-radius: 6px;">Join our WhatsApp Artist group</span>
                                </p>
                                <a href="https://chat.whatsapp.com/GAtEskogpSB3miS9DIv7st" 
                                   style="display: inline-block; text-decoration: none; color: #ffffff; background-color: #d32f2f; padding: 15px 25px; border-radius: 8px; margin-top: 12px; font-size: 18px; font-weight: bold;">
                                    Join Our WhatsApp Community
                                </a>
                            </div>

                            <!-- Footer -->
                            <div style="text-align: left; margin-top: 50px; padding-top: 35px; border-top: 2px solid #eaeaea; font-family: 'Arial', sans-serif;">
                                <p style="color: #555; margin-bottom: 12px; font-size: 20px; line-height: 1.5;">
                                    Warm wishes,
                                </p>
                                <p style="color: #333; font-weight: 600; font-size: 24px; margin-bottom: 30px;">
                                    The SongDis Team 🎵
                                </p>
                                <div style="margin-top: 35px;">
                                    <p style="color: #666; margin-bottom: 20px; font-size: 18px;">Connect with us:</p>
                                    <div style="display: flex; gap: 35px; align-items: center;">
                                        <!-- Instagram -->
                                        <a href="https://www.instagram.com/songdisonline?igsh=Z2tlODBmOGZzeGN1" 
                                           style="text-decoration: none; display: flex; align-items: center; color: #C13584; padding: 12px; border-radius: 8px; transition: all 0.3s ease;">
                                            <span style="font-size: 18px;">Instagram</span>
                                        </a>
                                        
                                        <!-- Twitter -->
                                        <a href="https://x.com/songdisonline?t=E85RshozKemONH7nhxEQCQ&s=08" 
                                           style="text-decoration: none; display: flex; align-items: center; color: #1DA1F2; padding: 12px; border-radius: 8px; transition: all 0.3s ease;">
                                            <span style="font-size: 18px;">Twitter</span>
                                        </a>
                                        
                                        <!-- WhatsApp -->
                                        <a href="https://chat.whatsapp.com/GAtEskogpSB3miS9DIv7st" 
                                           style="text-decoration: none; display: flex; align-items: center; color: #25D366; padding: 12px; border-radius: 8px; transition: all 0.3s ease;">
                                            <span style="font-size: 18px;">Join Community</span>
                                        </a>
                                    </div>
                                </div>
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