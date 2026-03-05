<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\CommunityFriend;
use App\Models\CommunityFriendRequest;
use App\Models\CommunityMessage;

class CommunityController extends Controller
{
    // =====================================================================
    // PAGE VIEW
    // =====================================================================

    /**
     * Main community page.
     */
    public function index(Request $request)
    {
        $user    = Auth::user();
        $friends = $this->getFriendsList($user->id);

        // Pending incoming requests for notification badge
        $pendingCount = CommunityFriendRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // If a friend is selected via query param, load their info
        $selectedFriend = null;
        if ($request->has('friend_id')) {
            $selectedFriend = User::find($request->friend_id);
        }

        return view('community.index', compact('friends', 'pendingCount', 'selectedFriend'));
    }

    // =====================================================================
    // FRIEND SYSTEM
    // =====================================================================

    /**
     * Search user by email (AJAX).
     */
    public function findFriend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $me   = Auth::id();
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['found' => false, 'message' => 'No user found with that email.'], 404);
        }

        if ($user->id === $me) {
            return response()->json(['found' => false, 'message' => 'You cannot add yourself.'], 422);
        }

        // Check if already friends
        $alreadyFriend = CommunityFriend::where('user_id', $me)
            ->where('friend_id', $user->id)
            ->exists();

        // Check if request already sent
        $requestSent = CommunityFriendRequest::where('sender_id', $me)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        // Check if they sent a request to me
        $requestReceived = CommunityFriendRequest::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->where('status', 'pending')
            ->first();

        return response()->json([
            'found'           => true,
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'avatar'          => $user->profilepic && $user->profilepic !== 'default.jpg'
                                    ? asset('profilepics/' . $user->profilepic)
                                    : asset('profilepics/user_avatar.png'),
            'already_friend'  => $alreadyFriend,
            'request_sent'    => $requestSent,
            'request_received'=> $requestReceived ? $requestReceived->id : null,
        ]);
    }

    /**
     * Send a friend request (AJAX).
     */
    public function sendFriendRequest(Request $request)
    {
        $request->validate(['receiver_id' => 'required|exists:users,id']);

        $me         = Auth::id();
        $receiverId = $request->receiver_id;

        if ($me === (int)$receiverId) {
            return response()->json(['success' => false, 'message' => 'Cannot add yourself.'], 422);
        }

        // Already friends?
        if (CommunityFriend::where('user_id', $me)->where('friend_id', $receiverId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already friends.'], 409);
        }

        // Already a pending request?
        $existing = CommunityFriendRequest::where('sender_id', $me)
            ->where('receiver_id', $receiverId)
            ->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return response()->json(['success' => false, 'message' => 'Request already sent.'], 409);
            }
            // Re-send if previously rejected
            $existing->update(['status' => 'pending']);
            return response()->json(['success' => true, 'message' => 'Friend request re-sent.']);
        }

        CommunityFriendRequest::create([
            'sender_id'   => $me,
            'receiver_id' => $receiverId,
            'status'      => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Friend request sent!']);
    }

    /**
     * Accept or reject a friend request (AJAX).
     */
    public function respondFriendRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:community_friend_requests,id',
            'action'     => 'required|in:accept,reject',
        ]);

        $me        = Auth::id();
        $freqRequest = CommunityFriendRequest::where('id', $request->request_id)
            ->where('receiver_id', $me)
            ->firstOrFail();

        if ($request->action === 'accept') {
            $freqRequest->update(['status' => 'accepted']);

            // Create bidirectional friendship entries
            CommunityFriend::firstOrCreate(['user_id' => $me,                 'friend_id' => $freqRequest->sender_id]);
            CommunityFriend::firstOrCreate(['user_id' => $freqRequest->sender_id, 'friend_id' => $me]);

            return response()->json(['success' => true, 'message' => 'Friend request accepted!']);
        }

        $freqRequest->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Friend request rejected.']);
    }

    /**
     * Get pending incoming requests (AJAX – for badge updates).
     */
    public function pendingRequests()
    {
        $me = Auth::id();

        $requests = CommunityFriendRequest::with('sender')
            ->where('receiver_id', $me)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id'     => $r->id,
                'name'   => $r->sender->name,
                'email'  => $r->sender->email,
                'avatar' => $r->sender->profilepic && $r->sender->profilepic !== 'default.jpg'
                                ? asset('profilepics/' . $r->sender->profilepic)
                                : asset('profilepics/user_avatar.png'),
            ]);

        return response()->json(['requests' => $requests, 'count' => $requests->count()]);
    }

    /**
     * Get unread message notifications for the nav bar (AJAX – polling).
     * Returns: total unread count + list of senders with latest unread message preview.
     */
    public function notifications()
    {
        $me = Auth::id();

        // Get unread messages grouped by sender
        $unreadMessages = CommunityMessage::with(['sender:id,name,profilepic'])
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->where('is_deleted', false)
            ->orderByDesc('id')
            ->get();

        $totalUnread = $unreadMessages->count();

        // Group by sender, keep only the latest message per sender
        $bySender = $unreadMessages->groupBy('sender_id')->map(function ($msgs) {
            $latest = $msgs->first(); // already ordered by desc id
            return [
                'sender_id'    => $latest->sender_id,
                'sender_name'  => $latest->sender->name,
                'sender_avatar'=> $latest->sender->profilepic && $latest->sender->profilepic !== 'default.jpg'
                                      ? asset('profilepics/' . $latest->sender->profilepic)
                                      : asset('profilepics/user_avatar.png'),
                'message'      => \Str::limit($latest->message, 50),
                'time'         => $latest->created_at->diffForHumans(),
                'count'        => $msgs->count(),
                'friend_id'    => $latest->sender_id,
            ];
        })->values();

        return response()->json([
            'total'        => $totalUnread,
            'notifications'=> $bySender,
        ]);
    }

    // =====================================================================
    // MESSAGING
    // =====================================================================

    /**
     * Get chat messages between auth user and a friend (AJAX – polling).
     *
     * Query param: lang = receiver's language code (e.g. "ta" for Tamil).
     *              after_id = last message ID client already has (for incremental load).
     */
    public function getMessages(Request $request, int $friendId)
    {
        $me       = Auth::id();
        $myLang   = $request->get('my_lang', 'en');
        $afterId  = (int) $request->get('after_id', 0);

        $query = CommunityMessage::between($me, $friendId)
            ->with(['sender:id,name,profilepic'])
            ->orderBy('id');

        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get()->map(function ($msg) use ($me, $myLang) {
            $displayText = $this->resolveDisplayText($msg, $me, $myLang);

            return [
                'id'           => $msg->id,
                'sender_id'    => $msg->sender_id,
                'sender_name'  => $msg->sender->name,
                'sender_avatar'=> $msg->sender->profilepic && $msg->sender->profilepic !== 'default.jpg'
                                      ? asset('profilepics/' . $msg->sender->profilepic)
                                      : asset('profilepics/user_avatar.png'),
                'is_mine'      => $msg->sender_id === $me,
                'message'      => $msg->is_deleted ? null : $msg->message,
                'display_text' => $displayText,
                'is_edited'    => $msg->is_edited,
                'is_deleted'   => $msg->is_deleted,
                'time'         => $msg->created_at->format('h:i A'),
                'full_time'    => $msg->created_at->toDateTimeString(),
            ];
        });

        // Mark messages as read
        CommunityMessage::where('sender_id', $friendId)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message (AJAX).
     *
     * Body params:
     *   receiver_id   – friend's user ID
     *   message       – raw text typed by sender
     *   sender_lang   – sender's UI language code (e.g. "ta")
     *   receiver_lang – receiver's UI language code
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id'   => 'required|exists:users,id',
            'message'       => 'required|string|max:4000',
            'sender_lang'   => 'nullable|string|max:10',
            'receiver_lang' => 'nullable|string|max:10',
        ]);

        $me           = Auth::id();
        $receiverId   = (int) $request->receiver_id;
        $rawMessage   = trim($request->message);
        $senderLang   = $request->get('sender_lang', 'en');
        $receiverLang = $request->get('receiver_lang', 'en');

        // Verify they are friends
        $isFriend = CommunityFriend::where('user_id', $me)->where('friend_id', $receiverId)->exists();
        if (!$isFriend) {
            return response()->json(['success' => false, 'message' => 'Not friends.'], 403);
        }

        // --- Translation ---
        // 1. If sender typed in a non-English language, translate to English first
        $msgEn = $rawMessage;
        if ($senderLang !== 'en') {
            $translated = $this->translate($rawMessage, 'en', $senderLang);
            $msgEn      = $translated ?? $rawMessage;
        }

        // 2. Translate English to receiver's language (if not English)
        $msgTranslated = null;
        if ($receiverLang && $receiverLang !== 'en') {
            $msgTranslated = $this->translate($msgEn, $receiverLang, 'en');
        }

        $msg = CommunityMessage::create([
            'sender_id'         => $me,
            'receiver_id'       => $receiverId,
            'message'           => $rawMessage,
            'message_en'        => $msgEn,
            'message_translated'=> $msgTranslated,
            'receiver_lang'     => $receiverLang,
            'is_edited'         => false,
            'is_deleted'        => false,
        ]);

        return response()->json([
            'success' => true,
            'id'      => $msg->id,
            'time'    => $msg->created_at->format('h:i A'),
        ]);
    }

    /**
     * Edit a message (AJAX).
     */
    public function editMessage(Request $request, int $id)
    {
        $request->validate(['message' => 'required|string|max:4000']);

        $me  = Auth::id();
        $msg = CommunityMessage::where('id', $id)->where('sender_id', $me)->firstOrFail();

        if ($msg->is_deleted) {
            return response()->json(['success' => false, 'message' => 'Cannot edit a deleted message.'], 422);
        }

        $newText   = trim($request->message);
        $senderLang  = $request->get('sender_lang', 'en');
        $receiverLang = $msg->receiver_lang ?? 'en';

        // Re-translate
        $msgEn = $newText;
        if ($senderLang !== 'en') {
            $msgEn = $this->translate($newText, 'en', $senderLang) ?? $newText;
        }

        $msgTranslated = null;
        if ($receiverLang !== 'en') {
            $msgTranslated = $this->translate($msgEn, $receiverLang, 'en');
        }

        $msg->update([
            'message'           => $newText,
            'message_en'        => $msgEn,
            'message_translated'=> $msgTranslated,
            'is_edited'         => true,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Soft-delete a message (AJAX).
     */
    public function deleteMessage(int $id)
    {
        $me  = Auth::id();
        $msg = CommunityMessage::where('id', $id)->where('sender_id', $me)->firstOrFail();
        $msg->update(['is_deleted' => true]);

        return response()->json(['success' => true]);
    }

    // =====================================================================
    // PRIVATE HELPERS
    // =====================================================================

    /**
     * Returns the list of friends with unread message counts.
     */
    private function getFriendsList(int $userId): \Illuminate\Support\Collection
    {
        // Get all friend IDs where this user is user_id
        $friendIds = CommunityFriend::where('user_id', $userId)->pluck('friend_id');

        return User::whereIn('id', $friendIds)
            ->get()
            ->map(function ($friend) use ($userId) {
                $unread = CommunityMessage::where('sender_id', $friend->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->where('is_deleted', false)
                    ->count();

                // Last message preview
                $last = CommunityMessage::between($userId, $friend->id)
                    ->where('is_deleted', false)
                    ->latest()
                    ->first();

                return [
                    'id'           => $friend->id,
                    'name'         => $friend->name,
                    'email'        => $friend->email,
                    'avatar'       => $friend->profilepic && $friend->profilepic !== 'default.jpg'
                                         ? asset('profilepics/' . $friend->profilepic)
                                         : asset('profilepics/user_avatar.png'),
                    'unread'       => $unread,
                    'last_message' => $last ? ($last->is_deleted ? 'Message deleted' : \Str::limit($last->message, 35)) : '',
                    'last_time'    => $last ? $last->created_at->diffForHumans() : '',
                ];
            });
    }

    /**
     * Decide which text to show a user based on who they are and their language.
     */
    private function resolveDisplayText(CommunityMessage $msg, int $viewerId, string $viewerLang): array
    {
        if ($msg->is_deleted) {
            return [['text' => '🚫 This message was deleted.', 'lang' => '']];
        }

        // If viewer is the sender → show original message only
        if ($msg->sender_id === $viewerId) {
            $parts = [['text' => $msg->message, 'lang' => '']];
            if ($msg->is_edited) {
                $parts[0]['edited'] = true;
            }
            return $parts;
        }

        // Viewer is receiver
        $parts = [];

        // English version first
        $englishText  = $msg->message_en ?? $msg->message;
        $parts[]      = ['text' => $englishText, 'lang' => 'en', 'label' => 'English'];

        // Target language (receiver's preferred language)
        if ($viewerLang !== 'en' && $msg->message_translated) {
            $parts[] = ['text' => $msg->message_translated, 'lang' => $viewerLang, 'label' => ''];
        }

        if ($msg->is_edited) {
            $parts[0]['edited'] = true;
        }

        return $parts;
    }

    /**
     * Call Google Cloud Translation API to translate text.
     *
     * Uses the FREE MyMemory API (no API key needed, 10,000 words/day free).
     * API docs: https://mymemory.translated.net/doc/spec.php
     *
     * @param string $text   Source text
     * @param string $target Target language code (e.g. "ta", "fr")
     * @param string $source Source language code (default: "en")
     * @return string|null   Translated text or null on failure
     */
    private function translate(string $text, string $target, string $source = 'en'): ?string
    {
        // Same language — no need to translate
        if ($source === $target) {
            return $text;
        }

        // Skip empty text
        if (empty(trim($text))) {
            return null;
        }

        try {
            // MyMemory free API — no key required
            // langpair format: "en|ta" (source|target)
            $response = Http::timeout(8)->get('https://api.mymemory.translated.net/get', [
                'q'        => $text,
                'langpair' => "{$source}|{$target}",
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // responseStatus 200 means success
                if (($data['responseStatus'] ?? null) === 200) {
                    $translated = $data['responseData']['translatedText'] ?? null;

                    // MyMemory returns "PLEASE SELECT TWO DISTINCT LANGUAGES" on bad langpair
                    if ($translated && !str_starts_with($translated, 'PLEASE SELECT')) {
                        return html_entity_decode($translated, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fail gracefully — chat still works without translation
            \Log::warning('Community translation failed: ' . $e->getMessage());
        }

        return null;
    }
}
