<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\Degree;
use App\Models\Profiles\Skill;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_skills()
    {
        Skill::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/skills");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }


    public function test_admin_can_get_all_paging_skills()
    {
        Skill::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/skills?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_skills()
    {
        $degree = Skill::factory()->count(2)->create();
        $query  = $degree->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/skills?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_skills()
    {
        $skill = Skill::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/skills/$skill->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $skill->id);
    }

    public function test_admin_can_create_skills()
    {
        $skill = Skill::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/skills", $skill->toArray());

        $response->assertStatus(201)
                ->assertJson(['name' => $skill->name]);

        $this->assertDatabaseHas($skill->getTable(), [
            'name' => $skill->name,
        ]);
    }

    public function test_admin_can_edit_skills()
    {
        $skill = Skill::factory()->create();
        $skill->name = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/skills/$skill->id", $skill->toArray());

        $response->assertStatus(200)
                ->assertJson(['name' => $skill->name]);

        $this->assertDatabaseHas($skill->getTable(), [
            'name' => $skill->name,
        ]);
    }

    public function test_admin_can_delete_skills()
    {
        $skill = Skill::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/skills/deletes",[$skill->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($skill->getTable(), [
                'id' => $skill->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_skills()
    {
        $degree = Skill::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/skills/deletes",$degree->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($degree->first()->getTable(), [
                'id' => $degree->first()->id,
        ]);
    }
}
