<?php

namespace App\Http\Controllers;

use App\Services\TweetService;
use Illuminate\Http\JsonResponse;

class ReactController extends Controller
{
    public function __construct(private readonly TweetService $tweetService) {}

    public function toggleLike(int $tweetId): JsonResponse
    {
        $this->tweetService->toggleLike($tweetId, auth()->id());
        return response()->json(['message' => 'Toggled like']);
    }
}
