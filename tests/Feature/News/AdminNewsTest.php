<?php

namespace Tests\Feature\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AdminNewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_search_news(): void
    {
        $admin = User::role('admin')->first();
        $response = $this->actingAs($admin)
            ->get('/api/admin/news/?search=lorem');

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->first(function ($json) {
                $this->assertArrayHasKey('title', $json->toArray());
                $this->assertArrayHasKey('updated_at', $json->toArray());
                $this->assertArrayHasKey('status', $json->toArray());
                $json->etc();
            });
        });
    }

    public function test_admin_can_get_post(): void
    {
        $admin = User::role('admin')->first();
        $post = News::first();

        $response = $this->actingAs($admin)
            ->get('/api/admin/news/'.$post->id, [
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($post) {
            $json->where('id', $post->id)
                ->where('status', $post->status)
                ->where('title', $post->title)
                ->where('updated_at', $post->updated_at)
                ->where('body', $post->body);
        });
    }

    public function test_admin_can_create_new_post(): void
    {
        $admin = User::role('admin')->first();
        $response = $this->actingAs($admin)
            ->post('/api/admin/news/', [
                'title' => 'Test Title',
                'body' => 'Test body.',
            ]);

        $response->assertStatus(201);
    }

    public function test_admin_can_change_post_status(): void
    {
        $admin = User::role('admin')->first();
        $post = News::first();
        $newStatus = 'new status';

        $response = $this->actingAs($admin)
            ->patch('/api/admin/news/'.$post->id, [
                'status' => $newStatus,
            ]);

        $response->assertStatus(204);

        $changedPost = News::find($post->id);
        $this->assertEquals($newStatus, $changedPost->status);
    }
}
