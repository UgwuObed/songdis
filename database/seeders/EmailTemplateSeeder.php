<?php

namespace Database\Seeders;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        EmailTemplate::create([
            'name' => 'welcome_email',
            'subject' => 'Welcome to SongDis, {{first_name}}! 🎉🎶',
            'content' => '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fafafa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #FF0000;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            padding: 30px;
            background: white;
        }
        .section {
            margin: 25px 0;
            background: white;
        }
        .section h2 {
            color: #FF0000;
            border-bottom: 2px solid #FF0000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .social-links {
            margin: 20px 0;
            padding: 15px;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #FF0000;
        }
        .highlight {
            color: #FF0000;
            font-weight: bold;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #FF0000;
            text-align: center;
        }
        a {
            color: #FF0000;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .logo-area {
            text-align: center;
            margin-bottom: 20px;
        }
        .cta-button {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            margin: 10px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #E60000;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SongDis! 🎵</h1>
        </div>
        <div class="content">
            <div class="logo-area">
                <img src="https://songdis.com/logo.png" alt="SongDis" style="max-width: 150px;">
            </div>
            <div class="section">
                <p>Hey there, {{first_name}}! 👋</p>
                <p>We\'re <span class="highlight">so glad</span> you\'re here! 🎉 We started <span class="highlight">SongDis</span> because we know how tough it can be for artists to distribute music and collect royalties. We\'re here to make that process simple and seamless for African artists like you. 🌍🎵</p>
            </div>

            <div class="section">
                <h2>Why SongDis?</h2>
                <p>As an artist manager, I\'ve seen firsthand how much time and effort artists put into creating music, only to face challenges in distribution and royalty collection. Back in 2018, while managing <span class="highlight">Kdiv Coco</span>, we faced a <span class="highlight">30% tax deduction</span> 💸 and a complicated payout process due to the African payment system when trying to collect streaming royalties.</p>
                
                <p>That\'s why we created <span class="highlight">SongDis</span> – to give African artists like you an easy way to distribute music, collect royalties, and withdraw earnings to local bank accounts. 💰✨ It\'s all done in simple steps, with professional support from industry experts, artists, and producers to help you succeed. 🚀</p>
            </div>

            <div class="section">
                <h2>Stay Connected!</h2>
                <p>To stay updated and be part of our growing community:</p>
                <div class="social-links">
                    <p>📸 <span class="highlight">Follow us on Instagram</span>: @songdis_</p>
                    <p>💬 <span class="highlight">Join our WhatsApp Artist group</span></p>
                    <a href="{{whatsapp_link}}" class="cta-button">Join Our WhatsApp Community</a>
                </div>
                <p>Whether you\'re just starting, emerging, established, or already a superstar, <span class="highlight">SongDis</span> is here to support you at every stage of your journey. 🌟</p>
            </div>

            <p>Looking forward to having you in our community and helping you take your music to the world! 🌍🎤</p>

            <div class="signature">
                <p>Best regards,<br>
                <span class="highlight">Melodysongz</span><br>
                CEO, SongDis ❤️🦢</p>
            </div>
        </div>
    </div>
</body>
</html>
            ',
            'variables' => [
                'first_name',
                'whatsapp_link'
            ]
        ]);
    }
}