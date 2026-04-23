<?php

namespace App\Services;

use App\Models\User;

class FollowService
{
    public function __construct(private NotificationService $notificationService) {}

    public function follow(User $follower, User $target): void
    {
        $follower->following()->syncWithoutDetaching([$target->id]);

        $this->notificationService->create($target->id, 'follow', [
            'user_id'   => $follower->id,
            'user_name' => $follower->name,
        ]);
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
        return $user->followers()->paginate(20);
    }

    public function following(User $user)
    {
        return $user->following()->paginate(20);
    }
}