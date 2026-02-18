<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $accessToken;
    protected $phoneNumberId;
    protected $recipientPhoneNumber;

    public function __construct()
    {
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->recipientPhoneNumber = config('services.whatsapp.recipient_phone_number');
    }

    /**
     * Send a notification about a new event using hello_world template.
     * This works with Meta's test phone number without any 24h restriction.
     *
     * @param array $eventDetails Key-value pairs of event details
     * @return bool
     */
    public function sendEventNotification(array $eventDetails)
    {
        if (!$this->accessToken || !$this->phoneNumberId || !$this->recipientPhoneNumber) {
            Log::warning('WhatsApp service is not configured. Skipping notification.');
            return false;
        }

        $url = "https://graph.facebook.com/v22.0/{$this->phoneNumberId}/messages";

        // Using 'hello_world' template — works with Meta test numbers
        // and does NOT require the recipient to message first.
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => 'hello_world',
                'language' => [
                    'code' => 'en_US'
                ]
            ]
        ];

        try {
            $response = Http::withToken($this->accessToken)
                ->contentType('application/json')
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent for event: ' . ($eventDetails['ExponName'] ?? 'Unknown'));
                return true;
            } else {
                Log::error('WhatsApp API Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Exception: ' . $e->getMessage());
            return false;
        }
    }
}
