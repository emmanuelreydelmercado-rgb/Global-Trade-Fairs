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
     * Send a notification about a new event.
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

        // Format the message
        $messageBody = "🎉 *New Event Created!* \n\n" .
            "📌 *Event:* " . ($eventDetails['ExponName'] ?? 'N/A') . "\n" .
            "🏢 *Organizer:* " . ($eventDetails['Orgname'] ?? 'N/A') . "\n" .
            "📅 *Date:* " . ($eventDetails['Date'] ?? 'N/A') . "\n" .
            "📍 *Venue:* " . ($eventDetails['VenueName'] ?? 'N/A') . "\n" .
            "🌍 *Location:* " . ($eventDetails['city'] ?? '') . ", " . ($eventDetails['country'] ?? '');

        // Payload for a text message
        // Note: For business-initiated conversations, you normally need to use a Template.
        // However, if the recipient has messaged the business in the last 24h, this text message will work.
        // If not, this might fail with a policy violation error.
        // In that case, you must create a Template in Meta Business Manager and use 'type' => 'template'.
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->recipientPhoneNumber,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $messageBody
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
