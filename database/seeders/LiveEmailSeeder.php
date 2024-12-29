<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class LiveEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'live_email'],
            [
                'name' => 'live_email',
                'subject' => 'Songdis is LIVE! 🎉 Your Exclusive Access Awaits',
                'content' => <<<HTML
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    </head>
                    <body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; margin: 0; padding: 0;">
                        <div style="max-width: 600px; margin: 0 auto; padding: 30px 20px; background-color: white; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <!-- Header Section -->
                            <div style="
    text-align: center; 
    margin-bottom: 40px;
    padding: 30px 20px;
    background: linear-gradient(135deg, #f6f8ff 0%, #ffffff 100%);
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);">
    
    <div style="
        display: inline-block;
        position: relative;
        padding: 0 30px;">
        
        <h1 style="
            color:rgb(241, 10, 10);
            font-family: 'Arial', sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1.3;">
            ✨ Congratulations! 🎉
        </h1>
        
        <div style="
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 2px;">
        </div>
    </div>
</div>

                            <!-- Main Content -->
                            <div style="background-color: #fff; padding: 25px; border-radius: 12px; margin-bottom: 25px;">
                                <p style="font-size: 18px; color: #2d2d2d; margin-bottom: 20px;">
                                    Hey {{first_name}}, Songdis is LIVE!
                                </p>

                                <p style="color: #2d2d2d; margin-bottom: 20px;">
                                    We've officially launched, and as a valued waitlist member, you're first in line to explore the platform! 
                                    To kickstart your journey, we're giving you <strong>14 days FREE</strong> to distribute your music globally 
                                    and take the spotlight you deserve.
                                </p>

                                <p style="color: #2d2d2d; margin-bottom: 20px;">
                                    With SongDis, you're not just releasing music; you're joining a community of creators making their mark.
                                </p>

                                <!-- Call to Action Section -->
                                <div style="background: linear-gradient(to right,rgb(253, 31, 31),rgb(255, 2, 2)); padding: 20px; border-radius: 10px; color: white; margin: 25px 0;">
                                    <h3 style="margin-top: 0;">Let's make your sound heard:</h3>
                                    <p style="margin-bottom: 10px;">
                                        Get Started Here: <a href="www.songdis.com" style="color: white; text-decoration: underline;">www.songdis.com</a>
                                    </p>
                                    <p style="background: rgba(255, 255, 255, 0.1); padding: 10px; border-radius: 5px;">
                                        Use the promo code: <strong>SONGDIS-5XC9VZXB</strong>
                                    </p>
                                </div>

                                <p style="color: #2d2d2d; margin-bottom: 20px;">
                                    Start today and enjoy unlimited releases, fast payouts, and 24/7 support.
                                </p>

                                <p style="font-size: 16px; margin: 0; font-weight: bold;">
                                    💬 <span style="background-color: #ffd5d5; padding: 2px 6px; border-radius: 4px;">Join our WhatsApp Artist group</span>
                                </p>
                                <a href="https://chat.whatsapp.com/GAtEskogpSB3miS9DIv7st" 
                                style="display: inline-block; text-decoration: none; color: #ffffff; background-color: #d32f2f; padding: 10px 15px; border-radius: 5px; margin-top: 8px; font-size: 14px; font-weight: bold;">
                                Join Our WhatsApp Community
                                </a>
                            </div>
<!-- Footer -->
<div style="text-align: left; margin-top: 40px; padding-top: 25px; border-top: 2px solid #eaeaea; font-family: 'Arial', sans-serif;">
    <p style="color: #555; margin-bottom: 8px; font-size: 16px; line-height: 1.4;">
        Cheers to your success,
    </p>
    <p style="color: #333; font-weight: 600; font-size: 18px; margin-bottom: 20px;">
        The SongDis Team 🎵
    </p>
    <div style="margin-top: 25px;">
        <p style="color: #666; margin-bottom: 15px; font-size: 15px;">Connect with us:</p>
        <div style="display: flex; gap: 25px; align-items: center;">
            <!-- Instagram -->
            <a href="https://www.instagram.com/songdisonline?igsh=Z2tlODBmOGZzeGN1" 
               style="text-decoration: none; display: flex; align-items: center; color: #C13584; padding: 8px; border-radius: 6px; transition: all 0.3s ease;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/instagram.svg" alt="Instagram" style="width: 22px; height: 22px; margin-right: 8px; filter: invert(26%) sepia(19%) saturate(7395%) hue-rotate(275deg) brightness(89%) contrast(92%);">
                <span style="font-size: 15px;">Instagram</span>
            </a>
            
            <!-- Twitter -->
            <a href="https://x.com/songdisonline?t=E85RshozKemONH7nhxEQCQ&s=08" 
               style="text-decoration: none; display: flex; align-items: center; color: #1DA1F2; padding: 8px; border-radius: 6px; transition: all 0.3s ease;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/twitter.svg" alt="Twitter" style="width: 22px; height: 22px; margin-right: 8px; filter: invert(48%) sepia(95%) saturate(1117%) hue-rotate(176deg) brightness(97%) contrast(91%);">
                <span style="font-size: 15px;">Twitter</span>
            </a>
            
            <!-- WhatsApp -->
            <a href="https://chat.whatsapp.com/GAtEskogpSB3miS9DIv7st" 
               style="text-decoration: none; display: flex; align-items: center; color: #25D366; padding: 8px; border-radius: 6px; transition: all 0.3s ease;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/whatsapp.svg" alt="WhatsApp" style="width: 22px; height: 22px; margin-right: 8px; filter: invert(56%) sepia(67%) saturate(1304%) hue-rotate(108deg) brightness(96%) contrast(98%);">
                <span style="font-size: 15px;">Join Community</span>
            </a>
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