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
            'from_email' => env('ZEPTO_MAIL_FROM_EMAIL'),
            'from_name' => env('ZEPTO_MAIL_FROM_NAME'),
            'bounce_address' => env('ZEPTO_MAIL_BOUNCE_ADDRESS', null),
        ];

        $this->client = new Client([
            'base_uri' => 'https://api.zeptomail.com/v1.1/',
            'headers' => [
                'Authorization' => 'Zoho-enczapikey ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Send email using a template from Zepto Mail dashboard
     */
    public function sendTemplateEmail(string $templateId, array $templateData, string $toEmail, string $toName = '')
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
                            'name' => $toName
                        ]
                    ]
                ],
                'template' => [
                    'template_id' => $templateId,
                    'data' => $templateData
                ],
            ];

            if ($this->config['bounce_address']) {
                $payload['bounce_address'] = $this->config['bounce_address'];
            }

            $response = $this->client->post('email/template', [
                'json' => $payload
            ]);

            Log::info('Template email sent successfully', [
                'template_id' => $templateId,
                'to' => $toEmail,
                'status' => $response->getStatusCode()
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Failed to send template email', [
                'error' => $e->getMessage(),
                'template_id' => $templateId,
                'to' => $toEmail
            ]);
            throw $e;
        }
    }

    /**
     * Send a basic email without template
     */
    public function sendBasicEmail(string $toEmail, string $subject, string $htmlContent, string $toName = '', array $attachments = [])
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
                            'name' => $toName
                        ]
                    ]
                ],
                'subject' => $subject,
                'htmlbody' => $htmlContent,
            ];

            if (!empty($attachments)) {
                $payload['attachments'] = $this->prepareAttachments($attachments);
            }

            if ($this->config['bounce_address']) {
                $payload['bounce_address'] = $this->config['bounce_address'];
            }

            $response = $this->client->post('email', [
                'json' => $payload
            ]);

            Log::info('Basic email sent successfully', [
                'to' => $toEmail,
                'subject' => $subject,
                'status' => $response->getStatusCode()
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Failed to send basic email', [
                'error' => $e->getMessage(),
                'to' => $toEmail,
                'subject' => $subject
            ]);
            throw $e;
        }
    }

    /**
     * Prepare attachments for API request
     */
    private function prepareAttachments(array $attachments): array
    {
        return array_map(function($attachment) {
            return [
                'name' => basename($attachment['name']),
                'content' => base64_encode(file_get_contents($attachment['path'])),
                'mime_type' => mime_content_type($attachment['path'])
            ];
        }, $attachments);
    }
}