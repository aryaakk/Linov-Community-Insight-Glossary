<?php

namespace Tests\Feature\Utility;

use App\Models\Article\Article;
use App\Models\Training\TrxEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_sitemap_article()
    {
        Article::factory()->count(10)->create();

        $response = $this->actingAs($this->user)->get('/api/sitemap/article');

        $response->assertStatus(200)
                ->assertJsonCount(10);
    }

    public function test_can_get_sitemap_event()
    {
        TrxEvent::factory()->count(10)->create(['type'=>'event']);

        $response = $this->actingAs($this->user)->get('/api/sitemap/event');

        $response->assertStatus(200)
                ->assertJsonCount(10);
    }
}
