<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    public function like(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $result = $this->likeService->like($request->user(), $post);
        return response()->json($result);
    }

    public function unlike(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $result = $this->likeService->unlike($request->user(), $post);
        return response()->json($result);
    }

    public function likes(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $likes = $this->likeService->likedBy($post);

        $data = $likes->through(fn($like) => new UserResource($like->user));
        return response()->json($data);
    }
}