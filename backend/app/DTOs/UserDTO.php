<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $bio = null,
        public readonly ?string $avatar = null,
    ) {}

    /**
     * Create a DTO from a request array or model attributes.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            username: $data['username'],
            email: $data['email'],
            password: $data['password'] ?? null,
            bio: $data['bio'] ?? null,
            avatar: $data['avatar'] ?? null,
        );
    }

    /**
     * Convert DTO back into array (e.g., for model creation).
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
        ];
    }
}
