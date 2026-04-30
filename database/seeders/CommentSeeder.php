<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        foreach ($posts as $post) {
            $commenters = $users->random(rand(1, 3));
            foreach ($commenters as $user) {
                Comment::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'body'    => fake()->sentence(),
                ]);
            }
        }
    }
}