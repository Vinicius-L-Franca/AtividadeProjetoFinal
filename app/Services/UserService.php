<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function getProfile(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function updateAvatar(User $user, UploadedFile $file): User
    {
        if ($user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $path = $file->store('avatars', 'public');
        $user->update(['avatar_url' => $path]);

        return $user->fresh();
    }

    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->limit(20)
            ->get();
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