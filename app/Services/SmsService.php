<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $clientUrlService;

    public function __construct(ClientUrlService $clientUrlService)
    {
        $this->clientUrlService = $clientUrlService;
    }

    /**
     * Send welcome SMS to client
     */
    public function sendWelcomeSms($client)
    {
        try {
            Log::info('Starting SMS send process for client: ' . $client->id);
            
            // Check if API key is available
            $apiKey = config('services.voodoosms.api_key');
            if (empty($apiKey)) {
                Log::error('VoodooSMS API key is not configured');
                return false;
            }
            
            $message = $this->generateSmsMessage($client);
            Log::info('SMS message generated for client: ' . $client->id);
            
            $apiUrl = config('services.voodoosms.api_url') . '/sendsms';
            Log::info('Sending SMS to VoodooSMS API: ' . $apiUrl);
            
            $requestData = [
                'to' => $this->formatPhoneNumber($client->mobile_number),
                'from' => 'Goldman',
                'msg' => $message
            ];
            
            Log::info('SMS request data: ' . json_encode($requestData));
            
            // Use cURL directly to avoid SSL issues
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            Log::info('VoodooSMS API response status: ' . $httpCode);
            Log::info('VoodooSMS API response body: ' . $response);

            if ($httpCode === 200) {
                Log::info('Welcome SMS sent successfully to: ' . $client->mobile_number);
                return true;
            } else {
                Log::error('Failed to send welcome SMS. Status: ' . $httpCode . ', Body: ' . $response . ', cURL Error: ' . $curlError);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Error sending welcome SMS: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Generate SMS message content
     */
    private function generateSmsMessage($client)
    {
        $caseReference = $this->generateCaseReference($client);
        $signatureUrl = $this->clientUrlService->generateSignatureUrl($client);
        
        return "Dear {$client->forename},\n\n" . 
               "Please visit the link below to sign the required documentation to progress your claim.\n\n" .
               "{$signatureUrl}\n\n" .
               "Regards,\n" .
               "Goldman Knightley";
    }

    /**
     * Generate case reference number
     */
    private function generateCaseReference($client)
    {
        // Generate a unique case reference based on client ID
        $prefix = 'E0';
        $suffix = str_pad($client->id, 6, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }

    /**
     * Generate short URL for SMS
     */
    private function generateShortUrl($client)
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
     * Format client address for SMS
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
     * Format phone number for SMS
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ensure it starts with country code if not already present
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            $phone = '44' . substr($phone, 1);
        }
        
        // If it's already 11 digits and starts with 0, convert to 44
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
            $phone = '44' . substr($phone, 1);
        }
        
        return $phone;
    }
} 