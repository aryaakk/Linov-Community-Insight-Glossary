<?php

namespace Tests\Feature\Utility;

use App\Models\Utility\Bank;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BankTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::deleteDirectory('public/bank');
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_bank()
    {
        Bank::factory()->count(5)->create([
            'is_active'=>'1',
        ]);

        $response = $this->actingAs($this->user)->get('/api/bank');

        $response->assertStatus(200)
                ->assertJson(function($json) {
                    $json->has(5);
                });
    }

    public function test_admin_can_get_all_paging_bank()
    {
        Bank::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/bank?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_bank()
    {
        $categories = Bank::factory()->count(2)->create();
        $query  = $categories->first()->code;
        
        $response = $this->actingAs($this->user)->get("/api/bank?perpage=10&page=1&columns[0]=code&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_get_one_bank()
    {
        $bank = Bank::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/bank/$bank->id");

        $response->assertStatus(200)
                ->assertJsonPath('id', $bank->id);
    }

    public function test_user_can_create_bank(){
        $bank = Bank::factory()->make();
        $bank->icon = UploadedFile::fake()->image('icon.jpg')->size(130);

        $response = $this->actingAs($this->user)
                         ->postJson('/api/bank', $bank->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($bank->getTable(), [
                'code' => $bank->code,
        ]);
    }

    public function test_user_can_edit_bank(){
        $bank = Bank::factory()->create();
        $bank->code = $bank->code.'-'.rand(1,100);
        unset($bank->icon);

        $response = $this->actingAs($this->user)
                         ->putJson('/api/bank/'.$bank->id, $bank->toArray());

        $response->assertStatus(200);

        $this->assertDatabaseHas($bank->getTable(), [
                'code' => $bank->code,
        ]);
    }

    public function test_user_can_delete_bank(){
        $bank = Bank::factory()->count(2)->create();

        $response = $this->actingAs($this->user)
                         ->postJson('/api/bank/deletes', $bank->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($bank->first()->getTable(), [
                'code' => $bank->first()->code,
        ]);
    }
}
