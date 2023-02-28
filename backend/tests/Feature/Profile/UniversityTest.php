<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\University;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UniversityTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_university()
    {
        University::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/university");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }
    
    public function test_admin_can_get_all_paging_university()
    {
        University::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/university?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_university()
    {
        $university = University::factory()->count(5)->create();
        $query  = $university->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/university?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_university()
    {
        $position = University::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/university/$position->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $position->id);
    }

    public function test_admin_can_create_university()
    {
        $position = University::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/university", $position->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_edit_university()
    {
        $position = University::factory()->create();
        $position->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/university/$position->id", $position->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_delete_university()
    {
        $position = University::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/university/deletes",[$position->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($position->getTable(), [
                'id' => $position->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_university()
    {
        $university = University::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/university/deletes",$university->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($university->first()->getTable(), [
                'id' => $university->first()->id,
        ]);
    }
}
