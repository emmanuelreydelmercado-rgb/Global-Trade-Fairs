<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeminiService
{
    private $client;
    private $apiKey;
    private $model;
    private $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.5-flash');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Generate AI response from user message
     */
    public function generateResponse($userMessage, $conversationHistory = [])
    {
        try {
            $systemPrompt = $this->getSystemPrompt();
            
            // Build the conversation context
            $contents = $this->buildContents($systemPrompt, $conversationHistory, $userMessage);

            // Make API request to Gemini
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $this->apiKey,
                ],
                'json' => [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            
            // Extract the generated text
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            return "I'm sorry, I couldn't generate a response. Please try again.";

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return "I'm experiencing technical difficulties. Please try again later or contact support at support@globaltradefairs.com";
        }
    }

    /**
     * Build contents array for Gemini API
     */
    private function buildContents($systemPrompt, $conversationHistory, $userMessage)
    {
        $contents = [];

        // Add system prompt as first user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $systemPrompt]],
        ];

        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Understood. I will assist users with Global Trade Fairs inquiries professionally and helpfully.']],
        ];

        // Add conversation history
        foreach ($conversationHistory as $message) {
            $role = $message['role'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $message['message']]],
            ];
        }

        // Add current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        return $contents;
    }

    /**
     * Get system prompt for the chatbot
     */
    private function getSystemPrompt()
    {
        // Fetch real event data from database
        $eventsData = $this->getEventsData();
        
        return "You are a helpful AI assistant for Global Trade Fairs, a platform that helps businesses attend trade fairs worldwide.

**About Global Trade Fairs:**
- We organize and facilitate participation in trade fairs across India, Asia, and globally
- We offer three package tiers: Basic (India), Pro (Asia), and Expert (Global/Europe)
- Services include travel arrangements, accommodation, event registration, and support

**Current Events Database:**
{$eventsData}

**Package Information:**
1. **Basic Package** - ₹50,000
   - Coverage: India only
   - Includes: Event registration, basic support
   
2. **Pro Package** - ₹2,00,000
   - Coverage: Asia region
   - Includes: Event registration, travel assistance, accommodation support
   
3. **Expert Package** - ₹8,00,000
   - Coverage: Global/Europe
   - Includes: Full concierge service, premium accommodation, VIP event access, dedicated support

**Your Capabilities:**
1. Answer questions about BOTH upcoming AND past trade fairs and events (use the real data above!)
2. Explain our three package types and help users choose the right one
3. Guide users through the registration and payment process
4. Provide information about venues, dates, and event details from our database
5. Help with general inquiries about trade fairs
6. Share information about past events for reference and planning

**Guidelines:**
- Be friendly, professional, and concise
- Use emojis sparingly for a modern touch (📅 🌍 💼 ✨)
- When asked about events, refer to the ACTUAL events from the database above
- For specific dates, venues, and organizers, use the real data provided
- If asked about an event not in our database, politely say we don't have that event currently
- For payment or booking issues, direct them to support@globaltradefairs.com
- Keep responses under 150 words unless detailed explanation is needed
- Use Indian Rupees (₹) for pricing
- When listing events, format them nicely with dates and venues

**Quick Actions You Can Suggest:**
- View upcoming events
- Compare package options
- Learn how to register
- Contact support

Remember: You're here to help users discover and participate in trade fairs that can grow their business! Always use the REAL event data from our database.";
    }

    /**
     * Fetch real events data from database
     */
    private function getEventsData()
    {
        try {
            // Get all events, ordered by date (most recent first)
            $allEvents = DB::table('forms')
                ->orderBy('Date', 'desc')
                ->limit(50) // Increased limit to include more historical data
                ->get();

            if ($allEvents->isEmpty()) {
                return "No events currently in database. Please check our website for updates.";
            }

            // Separate upcoming and past events
            $upcomingEvents = [];
            $pastEvents = [];
            
            foreach ($allEvents as $event) {
                if (Carbon::parse($event->Date)->isFuture()) {
                    $upcomingEvents[] = $event;
                } else {
                    $pastEvents[] = $event;
                }
            }

            $eventsList = "";

            // Add upcoming events section
            if (!empty($upcomingEvents)) {
                $eventsList .= "**🟢 UPCOMING EVENTS:**\n\n";
                foreach (array_slice($upcomingEvents, 0, 15) as $index => $event) {
                    $eventDate = Carbon::parse($event->Date)->format('d M Y');
                    $eventsList .= ($index + 1) . ". **{$event->ExponName}**\n";
                    $eventsList .= "   - Date: {$eventDate}\n";
                    $eventsList .= "   - Venue: {$event->VenueName}\n";
                    $eventsList .= "   - Organizer: {$event->Orgname}\n\n";
                }
            }

            // Add past events section
            if (!empty($pastEvents)) {
                $eventsList .= "\n**� PAST EVENTS (For Reference):**\n\n";
                foreach (array_slice($pastEvents, 0, 15) as $index => $event) {
                    $eventDate = Carbon::parse($event->Date)->format('d M Y');
                    $eventsList .= ($index + 1) . ". **{$event->ExponName}**\n";
                    $eventsList .= "   - Date: {$eventDate}\n";
                    $eventsList .= "   - Venue: {$event->VenueName}\n";
                    $eventsList .= "   - Organizer: {$event->Orgname}\n\n";
                }
            }

            if (empty($eventsList)) {
                return "No events currently in database. Please check our website for updates.";
            }

            return $eventsList;

        } catch (\Exception $e) {
            Log::error('Error fetching events data: ' . $e->getMessage());
            return "Event data temporarily unavailable. Please visit our events page or contact support.";
        }
    }

    /**
     * Detect user intent from message
     */
    public function detectIntent($message)
    {
        $message = strtolower($message);
        
        $intents = [
            'booking' => ['book', 'register', 'sign up', 'attend', 'participate', 'join'],
            'pricing' => ['price', 'cost', 'package', 'payment', 'pay', 'how much', 'fee'],
            'events' => ['event', 'fair', 'exhibition', 'upcoming', 'when', 'where', 'show'],
            'support' => ['help', 'support', 'contact', 'issue', 'problem', 'question'],
            'packages' => ['basic', 'pro', 'expert', 'plan', 'tier', 'option'],
        ];

        foreach ($intents as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $intent;
                }
            }
        }

        return 'general';
    }
}
