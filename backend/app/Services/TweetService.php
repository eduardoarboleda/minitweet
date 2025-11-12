<?php

namespace App\Services;

use App\DTOs\TweetDTO;
use App\Models\Tweet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TweetService
{
    /**
     * Fetch all top-level tweets, paginated 10 per page,
     * including replies and liked_by_users for each tweet.
     */
    public function getAllTweets(int $perPage = 10): LengthAwarePaginator
    {
        $tweets = Tweet::with(['user', 'reacts.user', 'replies.user', 'replies.reacts.user'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);

        return $tweets->through(fn(Tweet $tweet) => $this->transformTweet($tweet));
    }

    /**
     * Fetch tweets for a specific user.
     */
    public function getTweetsByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        $tweets = Tweet::with(['user', 'reacts.user', 'replies.user', 'replies.reacts.user'])
            ->where('user_id', $userId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);

        return $tweets->through(fn(Tweet $tweet) => $this->transformTweet($tweet));
    }

    /**
     * Fetch a single tweet with likes and replies.
     */
    public function getTweet(int $tweetId): ?array
    {
        $tweet = Tweet::with(['user', 'reacts.user', 'replies.user', 'replies.reacts.user'])
            ->find($tweetId);

        return $tweet ? $this->transformTweet($tweet) : null;
    }

    /**
     * Transform a tweet into array with liked_by_users.
     */
    private function transformTweet(Tweet $tweet): array
    {
        return [
            'id' => $tweet->id,
            'user_id' => $tweet->user_id,
            'content' => $tweet->content,
            'created_at' => $tweet->created_at,
            'updated_at' => $tweet->updated_at,
            'deleted_at' => $tweet->deleted_at,
            'parent_id' => $tweet->parent_id,
            'liked_by_users' => $tweet->reacts->where('is_liked', true)
                ->map(fn($react) => [
                    'id' => $react->user->id,
                    'name' => $react->user->name,
                    'username' => $react->user->username,
                ])->values(),
            'user' => [
                'id' => $tweet->user->id,
                'name' => $tweet->user->name,
                'username' => $tweet->user->username,
                'email' => $tweet->user->email,
                'email_verified_at' => $tweet->user->email_verified_at,
                'bio' => $tweet->user->bio,
                'avatar' => $tweet->user->avatar,
                'created_at' => $tweet->user->created_at,
                'updated_at' => $tweet->user->updated_at,
            ],
            'replies' => $tweet->replies->map(fn($reply) => $this->transformTweet($reply))->values(),
        ];
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
}
