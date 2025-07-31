<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Log;

class EmailService
{
    protected $clientUrlService;

    public function __construct(ClientUrlService $clientUrlService)
    {
        $this->clientUrlService = $clientUrlService;
    }

    /**
     * Get Microsoft Graph access token
     */
    private function getGraphAccessToken()
    {
        $clientId = '4fe41997-efb4-4607-adfd-4158deedf78c';
        $clientSecret = 'uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz';
        $tenantId = '07f5987e-d351-4de4-9c7a-4525be53ff1a';

        $response = file_get_contents("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'scope'         => 'https://graph.microsoft.com/.default',
                ]),
            ]
        ]));

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    /**
     * Send welcome email to client
     */
    public function sendWelcomeEmail($client)
    {
        try {
            Log::info('Starting email send process for client: ' . $client->id);
            
            // Get access token
            $accessToken = $this->getGraphAccessToken();
            if (!$accessToken) {
                Log::error('Failed to get Outlook access token');
                return false;
            }
            
            Log::info('Access token obtained successfully');
            
            $emailData = $this->prepareEmailData($client);
            Log::info('Email data prepared for client: ' . $client->id);
            
            // Prepare email payload
            $payload = [
                'message' => [
                    'subject' => 'Welcome to Goldman Knightley - Sign Your Letter of Authority',
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $this->generateEmailTemplate($emailData)
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $client->email_address
                            ]
                        ]
                    ]
                ]
            ];
            
            Log::info('Sending email to: ' . $client->email_address);
            
            // Send email using cURL with specific user account
            $fromEmail = 'pcpclaims@goldmanknightley.co.uk';
            $ch = curl_init("https://graph.microsoft.com/v1.0/users/{$fromEmail}/sendMail");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for development
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            Log::info('Outlook API response status: ' . $httpCode);
            Log::info('Outlook API response body: ' . $response);

            if ($httpCode === 202) {
                Log::info('Welcome email sent successfully to: ' . $client->email_address);
                return true;
            } else {
                Log::error('Failed to send welcome email. Status: ' . $httpCode . ', Body: ' . $response);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Error sending welcome email: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Prepare email data
     */
    private function prepareEmailData($client)
    {
        $signatureUrl = $this->clientUrlService->generateSignatureUrl($client);
        
        return [
            'first_name' => $client->forename,
            'client_name' => $client->forename . ' ' . $client->surname,
            'email' => $client->email_address,
            'phone' => $client->mobile_number,
            'address' => $this->formatAddress($client),
            'contact_id' => $client->id,
            'signature_url' => $signatureUrl
        ];
    }

    /**
     * Format client address for email
     */
    private function formatAddress($client)
    {
        $addressParts = [];
        
        if ($client->house_number) {
            $addressParts[] = $client->house_number;
        }
        if ($client->address_line_1) {
            $addressParts[] = $client->address_line_1;
        }
        if ($client->address_line_2) {
            $addressParts[] = $client->address_line_2;
        }
        if ($client->address_line_3) {
            $addressParts[] = $client->address_line_3;
        }
        if ($client->town_city) {
            $addressParts[] = $client->town_city;
        }
        if ($client->postcode) {
            $addressParts[] = $client->postcode;
        }
        
        return implode(', ', $addressParts);
    }

    /**
     * Generate signature URL
     */
    private function generateSignatureUrl($client)
    {
        return $this->clientUrlService->generateSignatureUrl($client);
    }

    /**
     * Encrypt client ID for URL security
     */
    private function encryptClientId($clientId)
    {
        return $this->clientUrlService->encryptClientId($clientId);
    }

    /**
     * Generate email HTML template
     */
    private function generateEmailTemplate($data)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to Goldman Knightley</title>
        </head>
        <body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f4f4f4">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4">
                <tbody>
                    <tr>
                        <td align="center" style="padding:40px 0">
                            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px">
                                <tbody>
                                    <tr>
                                        <td style="background:#0056b3;padding:30px;border-radius:8px 8px 0 0">
                                            <h1 style="color:#ffffff;margin:0;font-size:24px;text-align:center">Goldman Knightley</h1>
                                            <p style="color:#ffffff;margin:10px 0 0 0;text-align:center;font-size:16px;opacity:0.9">
                                            PCP Claims Team</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:40px 30px">
                                            <h3 style="color:#333333;margin:0 0 20px 0;font-size:20px">Hi ' . htmlspecialchars($data['first_name']) . ',</h3>
                                            <p style="color:#555555;margin:0 0 20px 0;font-size:16px;line-height:1.6">
                                            Thank you for submitting your claim. To continue, we need you to <strong>electronically sign your Letter of Authority (LOA)</strong>.
                                            </p>
                                            <p style="color:#555555;margin:0 0 20px 0;font-size:16px;line-height:1.6">
                                            This link is personalized for you: <strong>' . htmlspecialchars($data['client_name']) . '</strong>. </p>
                                            <div style="text-align:center;margin:30px 0">
                                                <a href="' . htmlspecialchars($data['signature_url']) . '" style="display:inline-block;background:#0056b3;color:#ffffff;text-decoration:none;padding:15px 30px;border-radius:6px;font-size:16px;font-weight:bold" target="_blank">
                                                    Sign LOA Now
                                                </a>
                                            </div>
                                            <div style="font-size:11px">
                                                <small>You acknowledge that you are not required to use a third party to make a complaint on your behalf and you can claim for free without using a firm by contacting the finance provider and then using the Financial Ombudsman Service.</small>
                                            </div>
                                            <hr style="border:none;border-top:1px solid #e9ecef;margin:30px 0">
                                            <p style="font-size:13px;color:#666666;margin:0;text-align:center">
                                                PCP Claims Team<br>
                                                Goldman Knightley
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';
    }
} 