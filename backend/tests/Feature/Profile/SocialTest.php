<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Social;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SocialTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_can_get_all_socials()
    {
        Social::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/socials");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }
}
