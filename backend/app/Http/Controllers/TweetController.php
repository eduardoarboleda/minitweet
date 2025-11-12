<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTweetRequest;
use App\Http\Requests\EditTweetRequest;
use App\Services\TweetService;
use Illuminate\Http\JsonResponse;

class TweetController extends Controller
{
    public function __construct(private readonly TweetService $tweetService) {}

    public function getAllTweets(): JsonResponse
    {
        return response()->json($this->tweetService->getAllTweets());
    }

    public function getTweetsByUser(int $userId): JsonResponse
    {
        return response()->json($this->tweetService->getTweetsByUser($userId));
    }

    public function getTweet(int $id): JsonResponse
    {
        return response()->json($this->tweetService->getTweet($id));
    }

    public function createTweet(CreateTweetRequest $request): JsonResponse
    {
        $tweet = $this->tweetService->createTweet($request->getDto(), $request->parent_id);
        return response()->json($tweet, 201);
    }

    public function editTweet(EditTweetRequest $request, int $id): JsonResponse
    {
        return response()->json($this->tweetService->editTweet($id, $request->content));
    }

    public function deleteTweet(int $id): JsonResponse
    {
        $this->tweetService->deleteTweet($id);
        return response()->json(['message' => 'Tweet deleted']);
    }

    public function toggleLike(int $id): JsonResponse
    {
        $this->tweetService->toggleLike($id, auth()->id());
        return response()->json(['message' => 'Toggled like']);
    }
}
