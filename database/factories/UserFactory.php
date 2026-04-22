<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'username'          => fake()->unique()->userName(),
            'bio'               => fake()->sentence(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => Hash::make('12345678'),
            'email_verified_at' => now(),
        ];
    }
}