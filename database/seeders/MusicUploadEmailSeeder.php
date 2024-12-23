<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class MusicUploadEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'music_upload_success'],
            [
                'name' => 'music_upload_success',
                'subject' => '🎵 Your Track Just Dropped on Songdis! 🎸',
                'content' => <<<HTML
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #8B0000; background-color: #FFF5F5;">
                        <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; border: 2px solid #FF6B6B;">
                            <h1 style="color:rgb(0, 0, 0);">Woohoo! You Did It, {{primary_artist}}! 🎉</h1>
                            
                            <p style="color:rgb(0, 0, 0);">Your awesome track just landed on Songdis like a rockstar! 🎸 Our team of music ninjas is working their magic to get your beats ready for the world! 🌍✨</p>
                            
                            <div style="background-color: #FFE5E5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                <h3 style="color:rgb(0, 0, 0);">The Backstage Pass - What's Happening Now:</h3>
                                <ul style="color: #C62828;">
                                    <li>🎧 Our sound wizards are making sure your track is crystal clear</li>
                                    <li>📝 Double-checking all your track details (because nobody likes typos in their name!)</li>
                                    <li>🚀 Getting ready to blast your music across the universe (okay, just Earth for now)</li>
                                    <li>✨ Sprinkling some extra magic dust for good measure</li>
                                </ul>
                            </div>
                            
                            <p style="color: #C62828;">Don't worry - we'll give you a shout when your track is ready to rock the streaming platforms! 🎵</p>
                            
                            <p style="color: #C62828;">Now go celebrate - you've earned it! 🎉 Maybe write another hit while you're at it? 😉</p>
                            
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #FFE5E5;">
                                <p style="color:rgb(0, 0, 0);">Rock on!</p>
                                <p style="color: #B71C1C; font-weight: bold;">The Songdis Team 🎸🤘</p>
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
