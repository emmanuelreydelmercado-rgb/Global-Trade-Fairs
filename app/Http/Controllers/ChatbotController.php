<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Send a message and get AI response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        try {
            // Get or create session ID
            $sessionId = $request->session_id ?? Str::uuid()->toString();

            // Get or create conversation
            $conversation = ChatConversation::firstOrCreate(
                ['session_id' => $sessionId],
                [
                    'user_id' => auth()->id(),
                    'started_at' => now(),
                ]
            );

            // Save user message
            $userMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'role' => 'user',
                'message' => $request->message,
            ]);

            // Get conversation history (last 10 messages for context)
            $history = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->take(10)
                ->get()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'message' => $msg->message,
                    ];
                })
                ->toArray();

            // Generate AI response
            $aiResponse = $this->geminiService->generateResponse(
                $request->message,
                array_slice($history, 0, -1) // Exclude the current message
            );

            // Save AI response
            $assistantMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'message' => $aiResponse,
            ]);

            // Detect intent for analytics
            $intent = $this->geminiService->detectIntent($request->message);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
                'session_id' => $sessionId,
                'intent' => $intent,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again or contact support@globaltradefairs.com',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get conversation history
     */
    public function getHistory(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $conversation = ChatConversation::where('session_id', $request->session_id)->first();

        if (!$conversation) {
            return response()->json([
                'success' => true,
                'messages' => [],
            ]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'message' => $msg->message,
                    'timestamp' => $msg->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * End a conversation session
     */
    public function endSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $conversation = ChatConversation::where('session_id', $request->session_id)->first();

        if ($conversation && $conversation->isActive()) {
            $conversation->update(['ended_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Conversation ended',
        ]);
    }

    /**
     * Get quick action suggestions
     */
    public function getQuickActions()
    {
        return response()->json([
            'success' => true,
            'actions' => [
                [
                    'text' => '📅 Show upcoming events',
                    'message' => 'What are the upcoming trade fairs?',
                ],
                [
                    'text' => '💰 View packages',
                    'message' => 'Tell me about your packages and pricing',
                ],
                [
                    'text' => '❓ How to register',
                    'message' => 'How do I register for an event?',
                ],
                [
                    'text' => '📞 Contact support',
                    'message' => 'How can I contact support?',
                ],
            ],
        ]);
    }
}
