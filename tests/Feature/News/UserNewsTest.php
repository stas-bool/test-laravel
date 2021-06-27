<?php

namespace Tests\Feature\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserNewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_can_not_read_news(): void
    {
        $response = $this->get('/api/user/news/?search=lorem');
        $response->assertForbidden();
    }

    public function test_user_can_search_news(): void
    {
        $user = User::role('user')->first();
        $response = $this->actingAs($user)
            ->get('/api/user/news/?search=lorem');

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->first(function ($json) {
                $this->assertArrayHasKey('title', $json->toArray());
                $this->assertArrayHasKey('updated_at', $json->toArray());
                $json->missing('status')
                    ->etc();
            });
        });
    }

    public function test_user_can_get_post(): void
    {
        $user = User::role('user')->first();
        $post = News::first();

        $response = $this->actingAs($user)
            ->get('/api/user/news/'.$post->id);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($post) {
            $json->where('id', $post->id)
                ->missing('status')
                ->etc();

            $postArray = $json->toArray();
            $this->assertArrayHasKey('title', $postArray);
            $this->assertArrayHasKey('body', $postArray);
            $this->assertArrayHasKey('updated_at', $postArray);
        });
    }

    public function test_user_can_not_get_nonexistent_post(): void
    {
        $user = User::role('user')->first();
        $nonExistentPostId = 99999999;

        $response = $this->actingAs($user)
            ->get('/api/user/news/'.$nonExistentPostId);

        $response->assertStatus(404);
    }

    public function test_user_can_not_create_post(): void
    {
        $user = User::role('user')->first();
        $response = $this->actingAs($user)
            ->post('/api/admin/news/');
        $response->assertForbidden();
    }
}
