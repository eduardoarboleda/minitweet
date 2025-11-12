<?php

namespace App\DTOs;

use Carbon\Carbon;

class TweetDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $content,
        public readonly ?string $date_created = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            content: $data['content'],
            date_created: isset($data['created_at']) ? self::humanizeDate($data['created_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'content' => $this->content,
        ];
    }

    private static function humanizeDate(string|Carbon $date): string
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        $now = Carbon::now();

        $diffInSeconds = $now->diffInSeconds($date);
        if ($diffInSeconds < 60) return $diffInSeconds . ' seconds ago';

        $diffInMinutes = $now->diffInMinutes($date);
        if ($diffInMinutes < 60) return $diffInMinutes . ' minutes ago';

        $diffInHours = $now->diffInHours($date);
        if ($diffInHours < 24) return $diffInHours . ' hours ago';

        $diffInDays = $now->diffInDays($date);
        if ($diffInDays < 7) return $diffInDays . ' days ago';

        $diffInWeeks = floor($diffInDays / 7);
        return $diffInWeeks . ' weeks ago';
    }
}
