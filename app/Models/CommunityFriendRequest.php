<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityFriendRequest extends Model
{
    protected $table = 'community_friend_requests';

    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
