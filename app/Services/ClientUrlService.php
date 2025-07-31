<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ClientUrlService
{
    /**
     * Decrypt client ID from URL
     */
    public function decryptClientId($encryptedId)
    {
        try {
            $key = config('app.key');
            $decoded = base64_decode(urldecode($encryptedId));
            
            $decrypted = openssl_decrypt(
                $decoded,
                'AES-256-CBC',
                $key,
                0,
                substr(hash('sha256', $key), 0, 16)
            );
            
            return $decrypted ? (int) $decrypted : null;
        } catch (\Exception $e) {
            Log::error('Failed to decrypt client ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get client by encrypted ID
     */
    public function getClientByEncryptedId($encryptedId)
    {
        $clientId = $this->decryptClientId($encryptedId);
        
        if (!$clientId) {
            return null;
        }
        
        return Client::find($clientId);
    }

    /**
     * Encrypt client ID for URL
     */
    public function encryptClientId($clientId)
    {
        $key = config('app.key');
        $encrypted = openssl_encrypt(
            $clientId,
            'AES-256-CBC',
            $key,
            0,
            substr(hash('sha256', $key), 0, 16)
        );
        
        return urlencode(base64_encode($encrypted));
    }

    /**
     * Generate signature URL with encrypted client ID
     */
    public function generateSignatureUrl($client)
    {
        $baseUrl = config('app.url', 'https://goldmanknightley.co.uk');
        $encryptedId = $this->encryptClientId($client->id);
        
        return $baseUrl . '/submit-signature/' . $encryptedId;
    }
} 