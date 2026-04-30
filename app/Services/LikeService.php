<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class LikeService
{
    /**
     * Curtir um post.
     * Idempotente: curtir repetido mantém curtido, sem duplicar.
     */
    public function like(User $user, Post $post): array
    {
        $like = Like::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return [
            'liked'       => true,
            'likes_count' => $post->likes()->count(),
        ];
    }

    /**
     * Descurtir um post.
     * Idempotente: descurtir repetido mantém descurtido, sem erro.
     */
    public function unlike(User $user, Post $post): array
    {
        Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->delete(); // retorna 0 silenciosamente se não existia

        return [
            'liked'       => false,
            'likes_count' => $post->likes()->count(),
        ];
    }

    public function likedBy(Post $post): LengthAwarePaginator
    {
        return $post->likes()->with('user')->paginate(20);
    }
}