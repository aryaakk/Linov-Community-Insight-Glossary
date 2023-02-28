<?php

namespace Tests\Feature\Training;

use App\Models\Training\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    Use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_companies()
    {
        Company::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/company');

        $response->assertStatus(200)
                ->assertJson(function($json) {
                    $json->has(5);
                });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_company()
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/company/'.$company->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $company->id,
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_company()
    {
        $company = Company::factory()->make();

        $response= $this->actingAs($this->user)
                         ->postJson('/api/company', $company->toArray());

        $response->assertStatus(201)
                ->assertJson(['code' => $company->code]);

        $this->assertDatabaseHas($company->getTable(), [
                'code' => $company->code,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_company()
    {
        $company = Company::factory()->create();
        $company->code = 'ABC';

        $response = $this->actingAs($this->user)
                         ->putJson('/api/company/'.$company->id, $company->toArray());

        $response->assertStatus(200)
                ->assertJson([
                    'code' => $company->code,
                ]);

        $this->assertDatabaseHas($company->getTable(), [
                    'code' => $company->code,
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_company()
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/company/'.$company->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($company->getTable(), [
            'code' => $company->code,
        ]);
    }
}
