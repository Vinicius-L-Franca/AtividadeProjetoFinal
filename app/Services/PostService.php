<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function create(User $user, ?string $caption, UploadedFile $image): Post
    {
        $path = $image->store('posts', 'public');

        return Post::create([
            'user_id'   => $user->id,
            'image_url' => $path,
            'caption'   => $caption,
        ]);
    }

    public function update(Post $post, string $caption): Post
    {
        $post->update(['caption' => $caption]);
        return $post->fresh();
    }

    public function delete(Post $post): void
    {
        Storage::disk('public')->delete($post->image_url);
        $post->delete();
    }

    public function getById(int $id): Post
    {
        return Post::with('user')->findOrFail($id);
    }

    public function getByUser(User $user)
    {
        return Post::where('user_id', $user->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(12);
    }
}