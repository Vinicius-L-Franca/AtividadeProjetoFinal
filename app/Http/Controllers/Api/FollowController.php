<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct(private FollowService $followService) {}

    public function follow(Request $request, int $id): JsonResponse
    {
        $target = User::findOrFail($id);

        if ($request->user()->id === $target->id) {
            return response()->json(['message' => 'Você não pode seguir a si mesmo.'], 422);
        }

        $this->followService->follow($request->user(), $target);

        return response()->json(['message' => 'Usuário seguido com sucesso.']);
    }

    public function unfollow(Request $request, int $id): JsonResponse
    {
        $target = User::findOrFail($id);
        $this->followService->unfollow($request->user(), $target);

        return response()->json(['message' => 'Você deixou de seguir o usuário.']);
    }

    public function isFollowing(Request $request, int $id): JsonResponse
    {
        $target = User::findOrFail($id);
        $following = $this->followService->isFollowing($request->user(), $target);

        return response()->json(['following' => $following]);
    }

    public function followers(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($this->followService->followers($user));
    }

    public function following(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($this->followService->following($user));
    }
}