<?php

namespace App\Http\Requests;

use App\DTOs\TweetDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateTweetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|max:280',
            'parent_id' => 'nullable|exists:tweets,id',
        ];
    }

    public function getDto(): TweetDTO
    {
        return new TweetDTO(
            user_id: $this->user()->id,
            content: $this->input('content')
        );
    }
}
