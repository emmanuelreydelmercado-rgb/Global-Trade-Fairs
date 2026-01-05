<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the user that owns the conversation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages for the conversation
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Check if conversation is active
     */
    public function isActive()
    {
        return is_null($this->ended_at);
    }
}
