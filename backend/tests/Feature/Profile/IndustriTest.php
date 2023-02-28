<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Industries;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndustriTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_industries()
    {
        Industries::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/industries");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

    public function test_admin_can_get_all_paging_industries()
    {
        Industries::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/industries?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_industries()
    {
        $industries = Industries::factory()->count(5)->create();
        $query  = $industries->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/industries?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_industries()
    {
        $industri = Industries::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/industries/$industri->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $industri->id);
    }

    public function test_admin_can_create_industries()
    {
        $industri = Industries::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/industries", $industri->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $industri->code]);

        $this->assertDatabaseHas($industri->getTable(), [
            'code' => $industri->code,
        ]);
    }

    public function test_admin_can_edit_industries()
    {
        $industri = Industries::factory()->create();
        $industri->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/industries/$industri->id", $industri->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $industri->code]);

        $this->assertDatabaseHas($industri->getTable(), [
            'code' => $industri->code,
        ]);
    }

    public function test_admin_can_delete_industries()
    {
        $industri = Industries::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/industries/deletes",[$industri->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($industri->getTable(), [
                'id' => $industri->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_industries()
    {
        $industries = Industries::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/industries/deletes",$industries->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($industries->first()->getTable(), [
                'id' => $industries->first()->id,
        ]);
    }
}
