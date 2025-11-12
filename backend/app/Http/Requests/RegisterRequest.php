<?php

namespace App\Http\Requests;

use App\DTOs\UserDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function getDto(): UserDTO
    {
        return new UserDTO(
            name: $this->name,
            username: $this->username,
            email: $this->email,
            password: $this->password
        );
    }

}
