<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Degree;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DegreeTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_degrees()
    {
        Degree::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/degrees");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }


    public function test_admin_can_get_all_paging_degrees()
    {
        Degree::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/degrees?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_degrees()
    {
        $degree = Degree::factory()->count(2)->create();
        $query  = $degree->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/degrees?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_degrees()
    {
        $position = Degree::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/degrees/$position->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $position->id);
    }

    public function test_admin_can_create_degrees()
    {
        $position = Degree::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/degrees", $position->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_edit_degrees()
    {
        $position = Degree::factory()->create();
        $position->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/degrees/$position->id", $position->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_delete_degrees()
    {
        $position = Degree::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/degrees/deletes",[$position->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($position->getTable(), [
                'id' => $position->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_degrees()
    {
        $degree = Degree::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/degrees/deletes",$degree->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($degree->first()->getTable(), [
                'id' => $degree->first()->id,
        ]);
    }
}
