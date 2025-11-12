<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->getDto());
        return response()->json($user, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        // Accept either email or username from the request
        $identifier = $request->email ?? $request->username;

        if (!$identifier) {
            return response()->json(['message' => 'Please provide email or username.'], 400);
        }

        $token = $this->authService->login($identifier, $request->password);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }


    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(): JsonResponse
    {
        return response()->json($this->authService->getUser());
    }
}
