<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'   => User::inRandomOrder()->first()->id,
            'image_url' => 'posts/fake.jpg',
            'caption'   => fake()->sentence(),
        ];
    }
}