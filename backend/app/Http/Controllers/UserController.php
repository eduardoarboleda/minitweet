<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function getUser(int $id): JsonResponse
    {
        return response()->json($this->userService->getUser($id));
    }

    public function getUsers(): JsonResponse
    {
        return response()->json($this->userService->getUsers());
    }

    public function editUser(EditUserRequest $request, int $id): JsonResponse
    {
        return response()->json($this->userService->editUser($id, $request->validated()));
    }

    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted']);
    }
}
