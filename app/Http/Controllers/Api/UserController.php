<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function show(string $username): JsonResponse
    {
        $user = $this->userService->getProfile($username);
        return response()->json(new UserResource($user));
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50|unique:users,username,' . $request->user()->id,
            'bio'      => 'sometimes|string|max:300',
        ]);

        $user = $this->userService->updateProfile($request->user(), $data);
        return response()->json($user);
    }

    public function avatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = $this->userService->updateAvatar($request->user(), $request->file('avatar'));
        return response()->json($user);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:1']);
        $users = $this->userService->search($request->query('q'));
        return response()->json(UserResource::collection($users));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $users = $this->userService->suggestions($request->user());
        return response()->json(UserResource::collection($users));
    }
}