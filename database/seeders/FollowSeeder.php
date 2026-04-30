<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $targets = $users->where('id', '!=', $user->id)->random(3);
            foreach ($targets as $target) {
                $user->following()->syncWithoutDetaching([$target->id]);
            }
        }
    }
}