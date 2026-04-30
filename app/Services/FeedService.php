<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FeedService
{
    public function getFeed(User $user): LengthAwarePaginator
    {
        $userIdsInFeed = $user->following()
            ->pluck('users.id')
            ->push($user->id)
            ->unique();

        return Post::whereIn('user_id', $userIdsInFeed)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(12);
    }
}