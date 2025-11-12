<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\UserDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    /**
     * Get a single user by ID.
     */
    public function getUser(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Get all users.
     */
    public function getUsers(): Collection
    {
        return User::all();
    }

    /**
     * Edit a user.
     */
    public function editUser(int $id, UserDTO $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data->toArray());

        return $user;
    }

    /**
     * Delete a user.
     */
    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
