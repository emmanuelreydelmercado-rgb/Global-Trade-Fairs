<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profilepic',
        'role',
        'google_id',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper methods (very useful)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Send the email verification notification with custom template.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    // ---- Community Relationships ----

    /** Users that THIS user has added as friends */
    public function communityFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_friends', 'user_id', 'friend_id');
    }

    /** Incoming pending friend requests */
    public function incomingFriendRequests(): HasMany
    {
        return $this->hasMany(CommunityFriendRequest::class, 'receiver_id')
                    ->where('status', 'pending');
    }

    /** Outgoing friend requests sent by this user */
    public function outgoingFriendRequests(): HasMany
    {
        return $this->hasMany(CommunityFriendRequest::class, 'sender_id');
    }

    /** All messages this user has sent */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(CommunityMessage::class, 'sender_id');
    }

    /** All messages this user has received */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(CommunityMessage::class, 'receiver_id');
    }
}
