<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tweet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'parent_id',
    ];

    protected $appends = ['liked_by_users']; // automatically added to JSON

    /** Tweet author */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** All reactions (likes/unlikes) */
    public function reacts(): HasMany
    {
        return $this->hasMany(React::class);
    }

    /** Only likes (is_liked = true) */
    public function likes(): HasMany
    {
        return $this->hasMany(React::class)->where('is_liked', true);
    }

    /** Replies to this tweet */
    public function replies(): HasMany
    {
        return $this->hasMany(Tweet::class, 'parent_id')->with('user', 'likes.user');
    }

    /** Parent tweet if this is a reply */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'parent_id');
    }

    /** Check if a given user liked this tweet */
    public function isLikedByUser(int $userId): bool
    {
        return $this->reacts()->where('user_id', $userId)->where('is_liked', true)->exists();
    }

    /** Accessor to return users who liked this tweet */
    public function getLikedByUsersAttribute()
    {
        return $this->likes()->with('user')->get()->map(function ($react) {
            return [
                'id' => $react->user->id,
                'name' => $react->user->name,
                'username' => $react->user->username,
            ];
        });
    }
}
