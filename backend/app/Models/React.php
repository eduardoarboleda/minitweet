<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class React extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tweet_id',
        'is_liked',
    ];

    /** React author */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Tweet this reaction belongs to */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }
}
