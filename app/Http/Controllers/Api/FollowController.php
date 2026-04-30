<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct(private FollowService $followService) {}

    /**
     * POST /api/users/{id}/follow
     * SelfFollowException lança 403 automaticamente via render().
     */
    public function follow(Request $request, int $id): JsonResponse
    {
        $target = User::findOrFail($id);
        $this->followService->follow($request->user(), $target);

        return response()->json(['message' => 'Usuário seguido com sucesso.']);
    }

    /**
     * DELETE /api/users/{id}/follow
     */
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