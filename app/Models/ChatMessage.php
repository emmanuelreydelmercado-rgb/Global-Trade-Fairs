<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'role',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the message
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Check if message is from user
     */
    public function isFromUser()
    {
        return $this->role === 'user';
    }

    /**
     * Check if message is from assistant
     */
    public function isFromAssistant()
    {
        return $this->role === 'assistant';
    }
}
