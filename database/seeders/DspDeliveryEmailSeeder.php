<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class DspDeliveryEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'delivery_success'],
            [
                'name' => 'delivery_success',
                'subject' => '🚀 Your Music is Taking Off Across the Universe! 🌟',
                'content' => <<<HTML
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #8B0000; background-color: #FFF5F5;">
                        <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; border: 2px solid #FF6B6B;">
                            <h1 style="color: rgb(0, 0, 0);">Time to Party, {{primary_artist}}! 🎉</h1>
                            
                            <p style="color: rgb(0, 0, 0); font-size: 18px;">
                                DRUM ROLL PLEASE... 🥁 Your music just got beamed up to ALL the streaming platforms! 
                                We're talking Spotify, Apple Music, and the whole streaming galaxy! 🎵✨
                            </p>
                            
                            <div style="background-color: #FFE5E5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                <h3 style="color: rgb(0, 0, 0);">Your Superstar To-Do List: 🌟</h3>
                                <ul style="color: #C62828;">
                                    <li>🎮 Hit up your <strong>Songdis Dashboard</strong> - it's like your personal mission control center!</li>
                                    <li>📱 Time to make some noise! Get those social media posts ready!</li>
                                    <li>🎨 Share those amazing cover art shots</li>
                                    <li>🎧 Create some buzz with your teaser clips</li>
                                    <li>💫 Tag us @Songdisonline - we love sharing your success!</li>
                                </ul>
                            </div>
                            
                            <p style="color: rgb(0, 0, 0);">
                                We're literally bouncing off the walls waiting to see your music climb those charts! 
                                Got questions? Need some promo tips? We're here faster than you can say "platinum record"! 🏆
                            </p>
                            
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #FFE5E5;">
                                <p style="color: #C62828;">Let's make those streams go crazy! 📈</p>
                                <p style="color: #B71C1C; font-weight: bold;">
                                    Your Biggest Fans at Songdis 🎸❤️🦢
                                    <br>
                                    <span style="font-size: 14px;">(Yes, we're already streaming your tracks on repeat! 🔥)</span>
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
HTML,
                'variables' => ['primary_artist'],
            ]
        );
    }
}