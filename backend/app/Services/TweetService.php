<?php

namespace App\Services;

use App\DTOs\TweetDTO;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TweetService
{
    /**
     * Fetch all top-level tweets, paginated 10 per page.
     */
    public function getAllTweets(int $perPage = 10): LengthAwarePaginator
    {
        return Tweet::with(['user', 'reacts.user', 'replies.user'])
            ->whereNull('parent_id') // only top-level tweets
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Fetch all top-level tweets from a specific user, paginated 10 per page.
     */
    public function getTweetsByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Tweet::where('user_id', $userId)
            ->whereNull('parent_id') // only top-level
            ->with(['reacts.user', 'replies.user'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Fetch a single tweet with its reacts and replies.
     */
    public function getTweet(int $tweetId): ?Tweet
    {
        return Tweet::with(['user', 'reacts.user', 'replies.user'])
            ->find($tweetId);
    }

    /**
     * Create a new tweet or reply.
     */
    public function createTweet(TweetDTO $dto, ?int $parentId = null): Tweet
    {
        return Tweet::create([
            'user_id' => $dto->user_id,
            'content' => $dto->content,
            'parent_id' => $parentId,
        ]);
    }

    /**
     * Edit an existing tweet.
     */
    public function editTweet(int $tweetId, string $content): ?Tweet
    {
        $tweet = Tweet::find($tweetId);
        if (!$tweet) return null;

        $tweet->update(['content' => $content]);
        return $tweet;
    }

    /**
     * Delete a tweet (cascades to replies).
     */
    public function deleteTweet(int $tweetId): bool
    {
        $tweet = Tweet::find($tweetId);
        if (!$tweet) return false;

        return $tweet->delete();
    }

    /**
     * Toggle like/unlike for a tweet by a user.
     */
    public function toggleLike(int $tweetId, int $userId): bool
    {
        $tweet = Tweet::find($tweetId);
        if (!$tweet) return false;

        $react = $tweet->reacts()->where('user_id', $userId)->first();

        if ($react) {
            $react->is_liked = !$react->is_liked;
            $react->save();
        } else {
            $tweet->reacts()->create([
                'user_id' => $userId,
                'is_liked' => true,
            ]);
        }

        return true;
    }

    /**
     * Fetch latest top-level tweets for feed (default 10 per page).
     */
    public function getFeed(int $perPage = 10): LengthAwarePaginator
    {
        return Tweet::with(['user', 'reacts.user', 'replies.user'])
            ->whereNull('parent_id') // top-level only
            ->latest()
            ->paginate($perPage);
    }
}
