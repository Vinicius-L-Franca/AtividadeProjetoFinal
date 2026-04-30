<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public function create(User $user, Post $post, string $body): Comment
    {
        return Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body'    => $body,
        ]);
    }

    public function update(Comment $comment, string $body): Comment
    {
        $comment->update(['body' => $body]);
        return $comment->fresh();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function getByPost(Post $post): LengthAwarePaginator
    {
        return $post->comments()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);
    }
}