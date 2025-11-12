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
        return User::create([
            'name' => $dto->name,
            'username' => $dto->username,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }

    /**
     * Login a user and return an API token.
     * Can log in using either email or username.
     */
    public function login(string $identifier, string $password): ?string
    {
        // Try to log in by email or username
        $credentials = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identifier, 'password' => $password]
            : ['username' => $identifier, 'password' => $password];

        if (!Auth::attempt($credentials)) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();
        return $user->createToken('api-token')->plainTextToken;
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
