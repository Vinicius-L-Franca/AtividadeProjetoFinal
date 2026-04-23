<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FeedService
{
    public function getFeed(User $user): LengthAwarePaginator
    {
        $followingIds = $user->following()->pluck('users.id');

        return Post::whereIn('user_id', $followingIds)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(12);
    }
}
