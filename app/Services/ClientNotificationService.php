<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ClientNotificationService
{
    private $emailService;
    private $smsService;

    public function __construct(EmailService $emailService, SmsService $smsService)
    {
        $this->emailService = $emailService;
        $this->smsService = $smsService;
    }

    /**
     * Send welcome notifications to new client
     */
    public function sendWelcomeNotifications($client)
    {
        $results = [
            'email_sent' => false,
            'sms_sent' => false,
            'email_error' => null,
            'sms_error' => null
        ];

        // Send email notification
        try {
            if ($this->emailService) {
                $results['email_sent'] = $this->emailService->sendWelcomeEmail($client);
                if (!$results['email_sent']) {
                    $results['email_error'] = 'Email service returned false';
                }
            } else {
                $results['email_error'] = 'Email service not available';
            }
        } catch (\Exception $e) {
            $results['email_error'] = 'Email error: ' . $e->getMessage();
            Log::error('Email notification error: ' . $e->getMessage());
        }

        // Send SMS notification
        try {
            if ($this->smsService) {
                $results['sms_sent'] = $this->smsService->sendWelcomeSms($client);
                if (!$results['sms_sent']) {
                    $results['sms_error'] = 'SMS service returned false';
                }
            } else {
                $results['sms_error'] = 'SMS service not available';
            }
        } catch (\Exception $e) {
            $results['sms_error'] = 'SMS error: ' . $e->getMessage();
            Log::error('SMS notification error: ' . $e->getMessage());
        }

        // Log the results
        $this->logNotificationResults($client, $results);

        return $results;
    }

    /**
     * Log notification results
     */
    private function logNotificationResults($client, $results)
    {
        $logMessage = "Client notifications for client ID {$client->id} ({$client->email_address}): ";
        
        if ($results['email_sent']) {
            $logMessage .= "Email sent successfully. ";
        } else {
            $logMessage .= "Email failed: {$results['email_error']}. ";
        }
        
        if ($results['sms_sent']) {
            $logMessage .= "SMS sent successfully. ";
        } else {
            $logMessage .= "SMS failed: {$results['sms_error']}. ";
        }

        Log::info($logMessage);
    }
} 