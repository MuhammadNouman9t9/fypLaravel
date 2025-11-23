<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS to a phone number.
     */
    public function send(string $phone, string $message): bool
    {
        $provider = config('services.sms.provider', 'log');

        return match ($provider) {
            'twilio' => $this->sendViaTwilio($phone, $message),
            'nexmo' => $this->sendViaNexmo($phone, $message),
            default => $this->sendViaLog($phone, $message),
        };
    }

    /**
     * Send SMS via Twilio.
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $accountSid = config('services.sms.twilio.account_sid');
        $authToken = config('services.sms.twilio.auth_token');
        $from = config('services.sms.twilio.from');

        if (!$accountSid || !$authToken || !$from) {
            Log::warning('Twilio credentials not configured. Falling back to log.');
            return $this->sendViaLog($phone, $message);
        }

        try {
            $client = new \Twilio\Rest\Client($accountSid, $authToken);
            $client->messages->create($phone, [
                'from' => $from,
                'body' => $message,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Twilio SMS sending failed: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS via Nexmo (Vonage).
     */
    protected function sendViaNexmo(string $phone, string $message): bool
    {
        $apiKey = config('services.sms.nexmo.api_key');
        $apiSecret = config('services.sms.nexmo.api_secret');
        $from = config('services.sms.nexmo.from');

        if (!$apiKey || !$apiSecret || !$from) {
            Log::warning('Nexmo credentials not configured. Falling back to log.');
            return $this->sendViaLog($phone, $message);
        }

        try {
            $basic = new \Vonage\Client\Credentials\Basic($apiKey, $apiSecret);
            $client = new \Vonage\Client($basic);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($phone, $from, $message)
            );

            return $response->current()->getStatus() === 0;
        } catch (\Exception $e) {
            Log::error('Nexmo SMS sending failed: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Log SMS (for development/testing).
     */
    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::info('SMS would be sent', [
            'to' => $phone,
            'message' => $message,
        ]);

        return true;
    }
}
