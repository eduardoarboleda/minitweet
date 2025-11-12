<?php

namespace App\DTOs;

class ReactDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly int $tweet_id,
        public readonly bool $is_liked = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            tweet_id: $data['tweet_id'],
            is_liked: $data['is_liked'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'tweet_id' => $this->tweet_id,
            'is_liked' => $this->is_liked,
        ];
    }
}
