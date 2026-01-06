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
        $apiKeys = $this->getApiKeys();
        $lastException = null;
        
        // Try each API key until one works
        foreach ($apiKeys as $index => $apiKey) {
            try {
                Log::info("Trying Gemini API key #" . ($index + 1));
                
                $systemPrompt = $this->getSystemPrompt();
                
                // Build the conversation context
                $contents = $this->buildContents($systemPrompt, $conversationHistory, $userMessage);

                // Make API request to Gemini
                $response = $this->client->post($this->apiUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $apiKey,
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
                    Log::info("Gemini API key #" . ($index + 1) . " succeeded");
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }

                return "I'm sorry, I couldn't generate a response. Please try again.";

            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
                
                // If 429 (rate limit), try next key
                if ($statusCode == 429) {
                    Log::warning("API key #" . ($index + 1) . " hit rate limit, trying next key");
                    $lastException = $e;
                    continue; // Try next key
                }
                
                // For other errors, log and try next key
                $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';
                
                Log::error('Gemini API Request Failed', [
                    'key_number' => $index + 1,
                    'status_code' => $statusCode,
                    'api_url' => $this->apiUrl,
                    'model' => $this->model,
                    'response' => $responseBody
                ]);
                
                $lastException = $e;
                continue; // Try next key
                
            } catch (\Exception $e) {
                Log::error('Gemini Service Error', [
                    'key_number' => $index + 1,
                    'message' => $e->getMessage(),
                    'api_url' => $this->apiUrl
                ]);
                
                $lastException = $e;
                continue; // Try next key
            }
        }
        
        // All keys failed
        if ($lastException) {
            throw new \Exception("All API keys exhausted. Last error: " . $lastException->getMessage());
        }
        
        throw new \Exception("No API keys configured");
    }
    
    /**
     * Get all configured API keys
     */
    private function getApiKeys()
    {
        $keys = [];
        
        // Primary key
        if ($primary = config('services.gemini.api_key')) {
            $keys[] = $primary;
        }
        
        // Secondary keys (GEMINI_API_KEY_2, GEMINI_API_KEY_3, etc.)
        for ($i = 2; $i <= 5; $i++) {
            if ($key = config("services.gemini.api_key_$i")) {
                $keys[] = $key;
            }
        }
        
        return $keys;
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
- Be friendly, professional, and helpful
- Use emojis to make responses engaging (📅 🌍 💼 ✨)
- **Context Handling:**
  - If user asks for 'details' or 'info' about an event → Provide **FULL** details (Date, Venue, Organizer, Contacts, etc.)
  - If user asks a **specific question** (e.g., 'What is the date?', 'Where is it?') → Answer **ONLY** that specific question directly. Don't dump entire event info.
  - If user asks 'What events in Mumbai?' → List events with key details (Name, Date, Venue).
  
- **Examples:**
  - User: 'Tell me about Cashew Expo' → Reply: [FULL Event Details with all fields]
  - User: 'When is the Cashew Expo?' → Reply: 'The Cashew Expo is on 27 Dec 2025 (Saturday).'
  - User: 'Where is it held?' → Reply: 'It is held at ORACLE, Mumbai.'
  - User: 'Contact number for Tech Expo?' → Reply: 'You can contact them at +91-9876543210.'

- For payment or booking issues, direct them to support@globaltradefairs.com
- Use Indian Rupees (₹) for pricing

**Response Style:**
- **Specific Question** → **Direct, Short Answer**
- **General Request** ('tell me more', 'details') → **Detailed, Comprehensive Answer**

- View upcoming events
- Get detailed information about specific events
- Find events by city/country
- Get contact information for organizers
- Access registration links
- Compare package options
- Learn how to register
- Contact support

Remember: You're here to help users discover and participate in trade fairs! ALWAYS provide COMPLETE, DETAILED information from the database. Don't be vague - use the actual data!";
    }

    /**
     * Fetch real events data from database with FULL details
     */
    private function getEventsData()
    {
        try {
            // Get all events with ALL fields, ordered by date (most recent first)
            $allEvents = DB::table('forms')
                ->select('*')
                ->orderBy('Date', 'desc')
                ->limit(50)
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

            $eventsList = "**COMPLETE EVENT DATABASE:**\n\n";
            $eventsList .= "Total Events: " . count($allEvents) . " (Upcoming: " . count($upcomingEvents) . ", Past: " . count($pastEvents) . ")\n\n";

            // Add upcoming events section with FULL details
            if (!empty($upcomingEvents)) {
                $eventsList .= "═══════════════════════════════════════\n";
                $eventsList .= "🟢 UPCOMING EVENTS (" . count($upcomingEvents) . ")\n";
                $eventsList .= "═══════════════════════════════════════\n\n";
                
                foreach (array_slice($upcomingEvents, 0, 20) as $index => $event) {
                    $eventsList .= $this->formatEventDetails($event, $index + 1, true);
                }
            }

            // Add past events section with FULL details
            if (!empty($pastEvents)) {
                $eventsList .= "\n═══════════════════════════════════════\n";
                $eventsList .= "🔴 PAST EVENTS (" . count($pastEvents) . ") - For Reference\n";
                $eventsList .= "═══════════════════════════════════════\n\n";
                
                foreach (array_slice($pastEvents, 0, 20) as $index => $event) {
                    $eventsList .= $this->formatEventDetails($event, $index + 1, false);
                }
            }

            $eventsList .= "\n═══════════════════════════════════════\n";
            $eventsList .= "**INSTRUCTIONS FOR AI:**\n";
            $eventsList .= "- When user asks about a specific event, provide ALL details above\n";
            $eventsList .= "- When user asks about events in a city, filter by city/venue\n";
            $eventsList .= "- When user asks for contact info, provide phone/email\n";
            $eventsList .= "- When user asks about registration, provide the registration link\n";
            $eventsList .= "- Be specific and detailed in your responses\n";
            $eventsList .= "- Format dates in a user-friendly way\n";
            $eventsList .= "═══════════════════════════════════════\n";

            return $eventsList;

        } catch (\Exception $e) {
            Log::error('Error fetching events data: ' . $e->getMessage());
            return "Event data temporarily unavailable. Please visit our events page or contact support.";
        }
    }

    /**
     * Format individual event details comprehensively
     */
    private function formatEventDetails($event, $number, $isUpcoming)
    {
        $eventDate = Carbon::parse($event->Date);
        $formattedDate = $eventDate->format('d M Y (l)'); // e.g., "15 Jan 2026 (Wednesday)"
        $daysUntil = $isUpcoming ? $eventDate->diffInDays(Carbon::now()) : null;
        
        $details = "【{$number}】 **{$event->ExponName}**\n";
        $details .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        // Date and timing info
        $details .= "📅 Date: {$formattedDate}\n";
        if ($isUpcoming && $daysUntil !== null) {
            $details .= "⏰ Time Until Event: {$daysUntil} days\n";
        }
        
        // Location details
        $details .= "📍 Venue: {$event->VenueName}\n";
        if (!empty($event->city)) {
            $details .= "🏙️ City: {$event->city}\n";
        }
        if (!empty($event->country)) {
            $details .= "🌍 Country: {$event->country}\n";
        }
        if (!empty($event->hallno)) {
            $details .= "🏢 Hall Number: {$event->hallno}\n";
        }
        
        // Organizer info
        $details .= "🏛️ Organizer: {$event->Orgname}\n";
        
        // Contact information
        if (!empty($event->phone)) {
            $details .= "📞 Phone: {$event->phone}\n";
        }
        if (!empty($event->email)) {
            $details .= "📧 Email: {$event->email}\n";
        }
        
        // Registration
        if (!empty($event->reglink)) {
            $details .= "🔗 Registration: {$event->reglink}\n";
        }
        
        // Status
        if (isset($event->status)) {
            $details .= "✅ Status: {$event->status}\n";
        }
        
        $details .= "\n";
        
        return $details;
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
