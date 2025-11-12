<?php

namespace App\Services;

use App\Models\React;
use App\DTOs\ReactDTO;
use Illuminate\Database\Eloquent\Collection;

class ReactService
{
    /**
     * Get a single react by ID.
     */
    public function getReact(int $id): React
    {
        return React::findOrFail($id);
    }

    /**
     * Get all reacts.
     */
    public function getReacts(): Collection
    {
        return React::with('user', 'tweet')->get();
    }

    /**
     * Edit a react.
     */
    public function editReact(int $id, ReactDTO $data): React
    {
        $react = React::findOrFail($id);
        $react->update($data->toArray());

        return $react;
    }

    /**
     * Delete a react.
     */
    public function deleteReact(int $id): bool
    {
        $react = React::findOrFail($id);
        return $react->delete();
    }

    /**
     * Create a new react.
     */
    public function createReact(ReactDTO $data): React
    {
        return React::create($data->toArray());
    }
}
