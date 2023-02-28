<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Major;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class MajorTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_majors()
    {
        Major::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/majors");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }


    public function test_admin_can_get_all_paging_majors()
    {
        Major::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/majors?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_majors()
    {
        $major = Major::factory()->count(5)->sequence(
           [ 'name'=>Str::random(5)],
           [ 'name'=>Str::random(5)],
           [ 'name'=>Str::random(5)],
           [ 'name'=>Str::random(5)],
           [ 'name'=>Str::random(5)],
        )->create();

        $query  = $major->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/majors?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_majors()
    {
        $position = Major::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/majors/$position->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $position->id);
    }

    public function test_admin_can_create_majors()
    {
        $position = Major::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/majors", $position->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_edit_majors()
    {
        $position = Major::factory()->create();
        $position->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/majors/$position->id", $position->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $position->code]);

        $this->assertDatabaseHas($position->getTable(), [
            'code' => $position->code,
        ]);
    }

    public function test_admin_can_delete_majors()
    {
        $position = Major::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/majors/deletes",[$position->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($position->getTable(), [
                'id' => $position->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_majors()
    {
        $major = Major::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/majors/deletes",$major->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($major->first()->getTable(), [
                'id' => $major->first()->id,
        ]);
    }
}
