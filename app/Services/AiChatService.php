<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AiChatService
{
    private $client;
    private $apiKey;
    private $model;
    private $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.groq.api_key'); // Use config for production
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
        $this->apiUrl = "https://api.groq.com/openai/v1/chat/completions";
    }

    public function generateResponse($userMessage, $conversationHistory = [])
    {
        try {
            // Handle greetings directly (prevent hallucinations)
            $lowerMessage = strtolower(trim($userMessage));
            $greetings = ['hi', 'hello', 'hey', 'ok', 'okay', 'hola', 'namaste'];
            
            if (in_array($lowerMessage, $greetings) || strlen($lowerMessage) <= 3) {
                return "👋 Welcome to **Global Trade Fairs**! 

I'm your AI assistant, here to help you discover trade fairs and events worldwide. 🌍

**I can help you with:**
📅 Upcoming and past events
🏙️ Events by city or country
💼 Package information (Basic, Pro, Expert)
📞 Contact details for organizers
🔗 Registration links

**Try asking:**
- 'What events are happening in Mumbai?'
- 'Tell me about Tech Expo 2025'
- 'What packages do you offer?'

How can I assist you today? 😊";
            }
            
            $systemPrompt = $this->getSystemPrompt();
            
            // Build messages array (OpenAI format)
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];
            
            // Add history
            foreach ($conversationHistory as $msg) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['message']
                ];
            }
            
            // Add current message
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.1,
                    'max_tokens' => 1024,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }
            
            return "I messed up the response parsing. Check logs.";

        } catch (\Exception $e) {
            Log::error("Groq API Error: " . $e->getMessage());
            return "Technical difficulties with Groq AI. Please check logs.";
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
- Be friendly, professional, and helpful
- Use emojis to make responses engaging (📅 🌍 💼 ✨)
- **Grammar Rules:**
  - Say 'held at [VenueName]' NOT 'held at the [VenueName]' (e.g., 'held at Marina Trench' not 'held at the Marina Trench')
  - Use proper English grammar and sentence structure
  - Keep responses natural and conversational
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
            // Get approved events with ALL fields, ordered by date (most recent first)
            $allEvents = DB::table('forms')
                ->where('status', 'approved')
                ->orderBy('Date', 'desc')
                ->limit(200)
                ->get();

            if ($allEvents->isEmpty()) {
                return "No approved events currently in database. Please check our website for updates.";
            }

            $eventsList = "CURRENT SYSTEM DATE: " . Carbon::now()->format('d M Y') . "\n";
            $eventsList .= "**INTERNAL EVENT DATABASE (Sorted by Date DESC):**\n\n";
            
            foreach ($allEvents as $index => $event) {
                $isFuture = false;
                try {
                    $isFuture = Carbon::parse($event->Date)->isFuture();
                } catch (\Exception $e) {}

                $eventsList .= $this->formatEventDetails($event, $index + 1, $isFuture);
            }

            $eventsList .= "\n═══════════════════════════════════════\n";
            $eventsList .= "**CRITICAL RULES - FAILURE TO FOLLOW = SYSTEM ERROR:**\n";
            $eventsList .= "1. **ABSOLUTE PROHIBITION:** NEVER mention 'Global Tech Fair 2025' or 'New York' unless user EXPLICITLY asks for it by name.\n";
            $eventsList .= "2. **GREETING HANDLING:** If user says 'hi', 'hello', 'ok', or similar → You should NOT see this (handled before you).\n";
            $eventsList .= "3. **SPECIFIC QUESTIONS ONLY:** If user asks about 'Event X', talk ONLY about 'Event X'. Do NOT mention other events.\n";
            $eventsList .= "4. **NO UNSOLICITED INFO:** Do not volunteer information the user didn't ask for.\n";
            $eventsList .= "5. **STOP IMMEDIATELY:** After answering the question, STOP. Do not add extra facts or suggestions.\n";
            $eventsList .= "6. **NO META-COMMENTARY:** Never start with 'Currently, our database shows...' - just answer directly.\n";
            $eventsList .= "\n**RESPONSE STYLE:**\n";
            $eventsList .= "- Specific question → Direct, short answer\n";
            $eventsList .= "- General request ('tell me more') → Detailed answer\n";
            $eventsList .= "- When user asks about events in a city, filter by city/venue\n";
            $eventsList .= "- When user asks for contact info, provide phone/email\n";
            $eventsList .= "- When user asks about registration, provide the registration link\n";
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
        $daysUntil = $isUpcoming ? abs(Carbon::now()->diffInDays($eventDate)) : null;
        
        $statusLabel = $isUpcoming ? "🟢 [UPCOMING]" : "🔴 [PAST]";
        $details = "【{$number}】 {$statusLabel} **{$event->ExponName}**\n";
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
