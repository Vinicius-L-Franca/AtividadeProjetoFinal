<?php

namespace App\Services;

use App\Exceptions\SelfFollowException;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FollowService
{
    /**
     * @throws SelfFollowException
     */
    public function follow(User $follower, User $target): void
    {
        if ($follower->id === $target->id) {
            throw new SelfFollowException();
        }

        // syncWithoutDetaching é idempotente: seguir repetido não duplica
        $follower->following()->syncWithoutDetaching([$target->id]);
    }

    /**
     * @throws SelfFollowException
     */
    public function unfollow(User $follower, User $target): void
    {
        if ($follower->id === $target->id) {
            throw new SelfFollowException();
        }

        $follower->following()->detach($target->id);
    }

    public function isFollowing(User $follower, User $target): bool
    {
        return $follower->following()->where('following_id', $target->id)->exists();
    }

    public function followers(User $user): LengthAwarePaginator
    {
        return $user->followers()->paginate(20);
    }

    public function following(User $user): LengthAwarePaginator
    {
        return $user->following()->paginate(20);
    }

    /**
     * Sugestões: usuários que $user ainda não segue, ordenados por nº de seguidores.
     */
    public function suggestions(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        $followingIds = $user->following()->pluck('users.id');

        return User::whereNotIn('id', $followingIds)
            ->where('id', '!=', $user->id)
            ->withCount('followers')
            ->orderByDesc('followers_count')
            ->limit($limit)
            ->get();
    }
}