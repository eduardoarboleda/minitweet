<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ReactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --------- AUTH ---------
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes require auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // --------- USERS ---------
    Route::get('users', [UserController::class, 'getUsers']);
    Route::get('users/{id}', [UserController::class, 'getUser']);
    Route::put('users/{id}', [UserController::class, 'editUser']);
    Route::delete('users/{id}', [UserController::class, 'deleteUser']);

    // --------- TWEETS ---------
    Route::get('tweets', [TweetController::class, 'getAllTweets']);
    Route::get('tweets/user/{userId}', [TweetController::class, 'getTweetsByUser']);
    Route::get('tweets/{id}', [TweetController::class, 'getTweet']);
    Route::post('tweets', [TweetController::class, 'createTweet']);
    Route::put('tweets/{id}', [TweetController::class, 'editTweet']);
    Route::delete('tweets/{id}', [TweetController::class, 'deleteTweet']);

    // Like/React
    Route::post('tweets/{id}/like', [TweetController::class, 'toggleLike']);
});
