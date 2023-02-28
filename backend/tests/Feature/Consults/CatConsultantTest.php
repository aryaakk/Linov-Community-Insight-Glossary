<?php

namespace Tests\Feature\Master;

use App\Models\Consults\Category;
use App\Models\Consults\Consults;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatConsultantTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_category_consultant()
    {
        Category::factory()->count(5)->create();
   
        $response = $this->actingAs($this->user)->get("/api/category/consultant");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

    public function test_admin_can_get_all_paging_category_consultant()
    {
        Category::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/category/consultant?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_category_consultant()
    {
        $categories = Category::factory()->count(5)->create();
        $query  = $categories->first()->name;
        
        $response = $this->actingAs($this->user)->get("/api/category/consultant?perpage=10&page=1&columns[0]=name&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_category_consultant()
    {
        $categori = Category::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/category/consultant/$categori->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $categori->id);
    }

    public function test_admin_can_create_category_consultant()
    {
        $categori = Category::factory()->make();

        $response= $this->actingAs($this->user)->postJson("/api/category/consultant", $categori->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $categori->code]);

        $this->assertDatabaseHas($categori->getTable(), [
            'code' => $categori->code,
        ]);
    }

    public function test_admin_can_edit_category_consultant()
    {
        $categori = Category::factory()->create();
        $categori->code = 'new_code';
        $response= $this->actingAs($this->user)->putJson("/api/category/consultant/$categori->id", $categori->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => $categori->code]);

        $this->assertDatabaseHas($categori->getTable(), [
            'code' => $categori->code,
        ]);
    }

    public function test_admin_can_delete_category_consultant()
    {
        $categori = Category::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/category/consultant/deletes",[$categori->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($categori->getTable(), [
                'id' => $categori->id,
        ]);
    }

    public function test_admin_can_delete_mutiple_category_consultant()
    {
        $categories = Category::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->postJson("/api/category/consultant/deletes",$categories->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($categories->first()->getTable(), [
                'id' => $categories->first()->id,
        ]);
    }
}
