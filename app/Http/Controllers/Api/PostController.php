<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image'   => 'required|image|max:5120',
            'caption' => 'nullable|string|max:2200',
        ]);

        $post = $this->postService->create(
            $request->user(),
            $request->input('caption'),
            $request->file('image')
        );

        return response()->json(new PostResource($post->load('user')), 201);
    }

    public function show(string $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        return response()->json(new PostResource($post));
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $request->validate(['caption' => 'required|string|max:2200']);
        $updated = $this->postService->update($post, $request->input('caption'));

        return response()->json(new PostResource($updated->load('user')));
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $this->postService->delete($post);

        return response()->json(['message' => 'Post deletado com sucesso.']);
    }

    public function byUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $posts = $this->postService->getByUser($user);

        return response()->json(PostResource::collection($posts));
    }
}