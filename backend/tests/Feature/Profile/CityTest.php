<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\City;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
{
    use DatabaseTransactions;
    public function test_admin_can_get_all_city()
    {
        City::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/city?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_city()
    {
        $cities = City::factory()->count(5)->create();
        $query  = $cities->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/city?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_city()
    {
        $city = City::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/city/$city->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $city->id);
    }

    public function test_admin_can_create_city()
    {
        $city = City::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/city", $city->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $city->code]);

        $this->assertDatabaseHas($city->getTable(), [
            'code' => $city->code,
        ]);
    }

    public function test_admin_can_edit_city()
    {
        $city = City::factory()->create();
        $city->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/city/$city->id", $city->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $city->code]);

        $this->assertDatabaseHas($city->getTable(), [
            'code' => $city->code,
        ]);
    }

    public function test_admin_can_delete_city()
    {
        $city = City::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/city/deletes",[$city->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($city->getTable(), [
                'id' => $city->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_city()
    {
        $city = City::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/city/deletes",$city->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($city->first()->getTable(), [
                'id' => $city->first()->id,
        ]);
    }
}
