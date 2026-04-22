<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowService
{
    public function follow(User $follower, User $target): void
    {
        $follower->following()->syncWithoutDetaching([$target->id]);
    }

    public function unfollow(User $follower, User $target): void
    {
        $follower->following()->detach($target->id);
    }

    public function isFollowing(User $follower, User $target): bool
    {
        return $follower->following()->where('following_id', $target->id)->exists();
    }

    public function followers(User $user)
    {
        return UserResource::collection($user->followers()->paginate(20));
    }

    public function following(User $user)
    {
        return UserResource::collection($user->following()->paginate(20));
    }
}