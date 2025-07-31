<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Outlook API Configuration
    'outlook' => [
        'api_url' => env('OUTLOOK_API_URL', 'https://graph.microsoft.com/v1.0'),
        'access_token' => env('OUTLOOK_ACCESS_TOKEN'),
        'client_id' => env('OUTLOOK_CLIENT_ID'),
        'client_secret' => env('OUTLOOK_CLIENT_SECRET'),
        'tenant_id' => env('OUTLOOK_TENANT_ID'),
    ],

    // VoodooSMS Configuration
    'voodoosms' => [
        'api_url' => env('VOODOOSMS_API_URL', 'https://api.voodoosms.com'),
        'api_key' => env('VOODOOSMS_API_KEY'),
        'from' => env('VOODOOSMS_FROM', 'Kingsnorth'),
    ],

];
