<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;

class ZeptoMailService
{
    private $client;
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'api_key' => env('ZEPTO_MAIL_API_KEY'),
            'from_email' => env('ZEPTO_MAIL_FROM_ADDRESS'),
            'from_name' => env('ZEPTO_MAIL_FROM_NAME'),
        ];

        $this->client = new Client([
            'base_uri' => 'https://api.zeptomail.com/v1.1/',
            'headers' => [
                'Authorization' => $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    // Test method for basic email sending
    public function sendTestEmail(string $toEmail)
    {
        try {
            $payload = [
                'from' => [
                    'address' => $this->config['from_email']
                ],
                'to' => [
                    [
                        'email_address' => [
                            'address' => $toEmail
                        ]
                    ]
                ],
                'subject' => 'Test Email from SongDis',
                'htmlbody' => '<div><h1>Test Email</h1><p>This is a test email to verify the Zepto Mail integration.</p></div>'
            ];

            Log::info('Sending test email with payload:', ['payload' => $payload]);

            $response = $this->client->post('email', [
                'json' => $payload
            ]);

            Log::info('Test email sent successfully', [
                'to' => $toEmail,
                'status' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Failed to send test email', [
                'error' => $e->getMessage(),
                'to' => $toEmail,
                'payload' => $payload
            ]);
            throw $e;
        }
    }


    public function sendTemplateEmail(string $templateId, array $templateData, string $toEmail, string $toName = '')
    {
        try {
           
            try {
                $templateResponse = $this->client->get("template/{$templateId}");
            } catch (GuzzleException $e) {
                Log::warning('Could not verify template:', ['error' => $e->getMessage()]);
            }

            $payload = [
                'from' => [
                    'address' => $this->config['from_email']
                ],
                'to' => [
                    [
                        'email_address' => [
                            'address' => $toEmail,
                            'name' => $toName
                        ]
                    ]
                ],
                'template_key' => $templateId, 
                'merge_data' => [ 
                    'personalization' => [
                        $templateData
                    ]
                ]
            ];



            $response = $this->client->post('email/template', [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Failed to send template email', [
                'error' => $e->getMessage(),
                'template_id' => $templateId,
                'to' => $toEmail,
                'payload' => $payload
            ]);
            throw $e;
        }
    }

    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent)
{
    try {
        $payload = [
            'from' => [
                'address' => $this->config['from_email'],
                'name' => $this->config['from_name'],
            ],
            'to' => [
                [
                    'email_address' => [
                        'address' => $toEmail,
                        'name' => $toName,
                    ]
                ]
            ],
            'subject' => $subject,
            'htmlbody' => $htmlContent,
        ];


        $response = $this->client->post('email', [
            'json' => $payload,
        ]);

        Log::info('Email sent successfully', [
            'to' => $toEmail,
            'status' => $response->getStatusCode(),
            'response' => json_decode($response->getBody(), true),
        ]);

        return json_decode($response->getBody(), true);

    } catch (GuzzleException $e) {
        Log::error('Failed to send email', [
            'error' => $e->getMessage(),
            'to' => $toEmail,
            'payload' => $payload,
        ]);
        throw $e;
    }
}

}