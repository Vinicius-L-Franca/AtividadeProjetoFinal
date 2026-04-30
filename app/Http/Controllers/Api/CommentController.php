<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    public function store(Request $request, string $id): JsonResponse
    {
        $request->validate(['body' => 'required|string|max:1000']);
        $post = Post::findOrFail($id);

        $comment = $this->commentService->create(
            $request->user(),
            $post,
            $request->input('body')
        );

        return response()->json(new CommentResource($comment->load('user')), 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        $request->validate(['body' => 'required|string|max:1000']);
        $updated = $this->commentService->update($comment, $request->input('body'));

        return response()->json(new CommentResource($updated->load('user')));
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('delete', $comment);

        $this->commentService->delete($comment);

        return response()->json(['message' => 'Comentário deletado com sucesso.']);
    }

    public function index(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $comments = $this->commentService->getByPost($post);

        return response()->json([
            'data' => CommentResource::collection($comments->items()),
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
            'total' => $comments->total(),
        ]);
    }
}