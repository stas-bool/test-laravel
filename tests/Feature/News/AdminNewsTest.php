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
            ->get('/api/news/?search=lorem');

        $response->assertSuccessful();

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
            ->get('/api/news/'.$post->id);

        $response->assertSuccessful();
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
        $allNewsCount = count(News::get());
        $response = $this->actingAs($admin)
            ->post('/api/news/', [
                'title' => 'Test Title',
                'body' => 'Test body.',
            ]);

        $response->assertSuccessful();
        $this->assertCount($allNewsCount + 1, News::get());
    }

    public function test_admin_can_change_post_status(): void
    {
        $admin = User::role('admin')->first();
        $post = News::first();
        $newStatus = 'new status';

        $response = $this->actingAs($admin)
            ->patch('/api/news/'.$post->id, [
                'status' => $newStatus,
            ]);

        $response->assertSuccessful();

        $changedPost = News::find($post->id);
        $this->assertEquals($newStatus, $changedPost->status);
    }
}
