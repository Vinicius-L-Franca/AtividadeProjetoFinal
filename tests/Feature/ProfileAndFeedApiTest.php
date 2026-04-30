<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileAndFeedApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_endpoint_accepts_numeric_identifier(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $user->id,
            'username' => $user->username,
        ]);
    }

    public function test_users_me_returns_authenticated_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users/me');

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $user->id,
            'username' => $user->username,
        ]);
    }

    public function test_users_me_posts_returns_only_authenticated_users_posts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Post::factory()->for($user)->create(['caption' => 'own-post-caption']);
        Post::factory()->for($otherUser)->create(['caption' => 'other-post-caption']);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users/me/posts');

        $response->assertOk();
        $response->assertJsonFragment(['caption' => 'own-post-caption']);
        $response->assertJsonMissing(['caption' => 'other-post-caption']);
    }

    public function test_feed_includes_authenticated_users_own_posts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Post::factory()->for($user)->create(['caption' => 'my-feed-post-caption']);
        Post::factory()->for($otherUser)->create(['caption' => 'outside-feed-post-caption']);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/feed');

        $response->assertOk();
        $response->assertJsonFragment(['caption' => 'my-feed-post-caption']);
        $response->assertJsonMissing(['caption' => 'outside-feed-post-caption']);
    }
}
