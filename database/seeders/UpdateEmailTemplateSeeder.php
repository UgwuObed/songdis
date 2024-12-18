<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class UpdateEmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $template = EmailTemplate::where('name', 'welcome_email')->first();

        if ($template) {
            $template->update([
                'subject' => 'Welcome to Songdis, Super Star! 🎉🎶',
                'content' => '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif;
            line-height: 1.8;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .header {
            background: rgba(255, 0, 0, 0.92);
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .content {
            padding: 35px;
            font-size: 1.1rem;
        }
        .section {
            margin: 30px 0;
        }
        .section h2 {
            color: rgba(255, 0, 0, 0.92);
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 3px solid rgba(255, 0, 0, 0.92);
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .social-links {
            margin: 25px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 2px solid rgba(255, 0, 0, 0.92);
            text-align: center;
            font-size: 1.1rem;
        }
        .highlight {
            font-weight: bold;
            color: rgba(255, 0, 0, 0.92);
        }
        .signature {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 0, 0, 0.92);
            text-align: center;
            font-size: 1rem;
            color: #555;
        }
        a {
            color: rgba(255, 0, 0, 0.92);
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .logo-area {
            text-align: center;
            margin-bottom: 30px;
        }
        .cta-button {
            display: inline-block;
            background-color: rgba(255, 0, 0, 0.92);
            color: white;
            padding: 15px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1.1rem;
            margin: 15px 0;
        }
        .cta-button:hover {
            background-color: rgba(200, 0, 0, 0.92);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Welcome to Songdis!
        </div>
        <div class="content">
            <div class="section">
                <p>Hey there, <span class="highlight">{{first_name}}</span>! 👋</p>
                <p>We&#39;re so glad to have you&#39;re here! 🎉 We started <span class="highlight">Songdis</span> because we know how tough it can be for artists to distribute music and collect royalties. We&#39;re here to make that process simple and seamless for African artists like you. 🌍🎵</p>
            </div>

            <div class="section">
                <h2>Why Songdis?</h2>
                <p>As an artist manager, I&#39;ve seen firsthand how much time and effort artists put into creating music, only to face challenges in distribution and royalty collection. Back in 2018, while managing <span class="highlight">Kdiv Coco</span>, we faced a <span class="highlight">30% tax deduction</span> 💸 and a complicated payout process due to the African payment system when trying to collect streaming royalties.</p>
                
                <p>That&#39;s why we created <span class="highlight">Songdis</span> – to give African artists like you an easy way to distribute music, collect royalties, and withdraw earnings to local bank accounts. 💰✨ It&#39;s all done in simple steps, with professional support from industry experts, artists, and producers to help you succeed. 🚀</p>
            </div>

            <div class="section">
                <h2>Stay Connected!</h2>
                <p>To stay updated and be part of our growing community:</p>
                <div class="social-links">
                    <p>📸 <span class="highlight">Follow us on Instagram</span>: @songdisonline</p>
                    <p>💬 <span class="highlight">Join our WhatsApp Artist group</span></p>
                    <a href="{{whatsapp_link}}" class="cta-button">Join Our WhatsApp Community</a>
                </div>
                <p>Whether you&#39;re just starting, emerging, established, or already a superstar, <span class="highlight">Songdis</span> is here to support you at every stage of your journey. 🌟</p>
            </div>

            <p>Looking forward to having you in our community and helping you take your music to the world! 🌍🎤</p>

            <div class="signature">
                <p>Best regards,<br>
                <span class="highlight">Melodysongz</span><br>
                CEO, Songdis ❤️🦢</p>
            </div>
        </div>
    </div>
</body>
</html>

                ',
                'variables' => ['first_name', 'whatsapp_link'],
            ]);

            $this->command->info('Welcome email template updated.');
        } else {
            $this->command->info('Welcome email template does not exist.');
        }
    }
}
