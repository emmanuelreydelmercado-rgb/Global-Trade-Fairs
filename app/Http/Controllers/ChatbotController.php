<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
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

            // GUEST RESTRICTION LOGIC - Check BEFORE saving the message
            if (!auth()->check()) {
                // Count how many USER messages (questions) the guest has ALREADY asked
                $previousQuestionsCount = ChatMessage::where('conversation_id', $conversation->id)
                    ->where('role', 'user')
                    ->count();

                // If they've already asked 2 questions, block the 3rd
                if ($previousQuestionsCount >= 2) {
                    Log::info('Guest blocked: exceeded question limit', [
                        'session_id' => $sessionId,
                        'count' => $previousQuestionsCount
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => '🔒 <strong>Login Required</strong><br>You have reached your free question limit (2 questions). Please login to continue chatting and ask unlimited questions.',
                        'action' => 'login_required',
                        'session_id' => $sessionId,
                    ]);
                }
            }

            // Save user message (AFTER checking the limit)
            $userMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'role' => 'user',
                'message' => $request->message,
            ]);

            // Get conversation history (last 10 messages for context)
            $history = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->take(10)
                ->get();

            $formattedHistory = $history->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'message' => $msg->message,
                    ];
                })
                ->toArray();

            // Generate AI response
            $aiResponse = $this->aiChatService->generateResponse(
                $request->message,
                array_slice($formattedHistory, 0, -1) // Exclude the current message
            );

            // Save AI response
            $assistantMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'message' => $aiResponse,
            ]);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
                'session_id' => $sessionId,
                'timestamp' => now()->toIso8601String(),
            ]);


        } catch (\Exception $e) {
            Log::error('Chatbot Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $sessionId ?? 'unknown',
                'user_id' => auth()->id() ?? 'guest'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'I\'m experiencing technical difficulties. Please try again later or contact support at support@globaltradefairs.com',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
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
