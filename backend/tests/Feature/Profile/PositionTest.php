<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PositionTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_positions()
    {
        Position::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/positions");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

    public function test_admin_can_get_all_paging_positions()
    {
        Position::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/positions?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_positions()
    {
        $positiones = Position::factory()->count(5)->create();
        $query  = $positiones->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/positions?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_positions()
    {
        $position = Position::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/positions/$position->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $position->id);
    }

    public function test_admin_can_create_positions()
    {
        $position = Position::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/positions", $position->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_edit_positions()
    {
        $position = Position::factory()->create();
        $position->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/positions/$position->id", $position->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_delete_positions()
    {
        $position = Position::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/positions/deletes",[$position->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($position->getTable(), [
                'id' => $position->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_positions()
    {
        $positiones = Position::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/positions/deletes",$positiones->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($positiones->first()->getTable(), [
                'id' => $positiones->first()->id,
        ]);
    }
}
