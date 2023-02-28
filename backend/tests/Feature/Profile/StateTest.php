<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\State;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StateTest extends TestCase
{
    use DatabaseTransactions;
    public function test_admin_can_get_all_state()
    {
        State::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/state?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_state()
    {
        $states = State::factory()->count(5)->create();
        $query  = $states->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/state?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_state()
    {
        $state = State::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/state/$state->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $state->id);
    }

    public function test_admin_can_create_state()
    {
        $state = State::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/state", $state->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $state->code]);

        $this->assertDatabaseHas($state->getTable(), [
            'code' => $state->code,
        ]);
    }

    public function test_admin_can_edit_state()
    {
        $state = State::factory()->create();
        $state->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/state/$state->id", $state->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $state->code]);

        $this->assertDatabaseHas($state->getTable(), [
            'code' => $state->code,
        ]);
    }

    public function test_admin_can_delete_state()
    {
        $state = State::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/state/deletes",[$state->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($state->getTable(), [
                'id' => $state->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_state()
    {
        $state = State::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/state/deletes",$state->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($state->first()->getTable(), [
                'id' => $state->first()->id,
        ]);
    }
}
