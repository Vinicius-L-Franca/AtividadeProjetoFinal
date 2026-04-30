<?php

namespace Tests\Feature;

use Tests\TestCase;

class SwaggerDocsTest extends TestCase
{
    public function test_swagger_ui_page_is_accessible(): void
    {
        $response = $this->get('/docs');

        $response->assertOk();
        $response->assertSee('Instaclone API');
        $response->assertSee('Swagger UI');
    }

    public function test_openapi_spec_is_accessible(): void
    {
        $response = $this->getJson('/docs/openapi.json');

        $response->assertOk();
        $response->assertJsonPath('openapi', '3.0.3');

        $spec = $response->json();

        $this->assertArrayHasKey('info', $spec);
        $this->assertArrayHasKey('paths', $spec);
        $this->assertArrayHasKey('/auth/login', $spec['paths']);
        $this->assertArrayHasKey('/feed', $spec['paths']);
        $this->assertArrayHasKey('/posts/{id}', $spec['paths']);
    }
}