<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityMessage extends Model
{
    protected $table = 'community_messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'message_en',
        'message_translated',
        'receiver_lang',
        'is_edited',
        'is_deleted',
        'read_at',
    ];

    protected $casts = [
        'is_edited'  => 'boolean',
        'is_deleted' => 'boolean',
        'read_at'    => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope messages between two users (either direction).
     */
    public function scopeBetween($query, int $userA, int $userB)
    {
        return $query->where(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userA)->where('receiver_id', $userB);
        })->orWhere(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userB)->where('receiver_id', $userA);
        });
    }
}
