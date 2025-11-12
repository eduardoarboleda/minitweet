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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * Get the user who created the tweet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all reactions (likes/unlikes) for this tweet.
     */
    public function reacts(): HasMany
    {
        return $this->hasMany(React::class);
    }

    /**
     * Check if a given user liked this tweet.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->reacts()->where('user_id', $user->id)->where('is_liked', true)->exists();
    }
}
