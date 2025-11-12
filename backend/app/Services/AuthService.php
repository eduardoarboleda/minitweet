<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Register a new user.
     */
    public function register(UserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password, // Laravel will hash automatically if cast is set
        ]);

        return $user;
    }

    /**
     * Login a user and return an API token.
     */
    public function login(string $email, string $password): ?string
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();

        // Create a personal access token (Sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return $token;
    }

    /**
     * Get the currently authenticated user.
     */
    public function getUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Logout the currently authenticated user.
     */
    public function logout(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete(); // revoke all tokens
        }
    }
}
