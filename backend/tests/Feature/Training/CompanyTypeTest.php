<?php

namespace Tests\Feature\Training;

use App\Models\Training\CompanyType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTypeTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_company_type()
    {
        CompanyType::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/company-type');

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
    public function test_user_can_get_one_company_type()
    {
        $companyType = CompanyType::factory()->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/company-type/'.$companyType->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $companyType->id,
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_company_type()
    {
        $companyType = CompanyType::factory()->make();

        $response= $this->actingAs($this->user)
                         ->postJson('/api/company-type', $companyType->toArray());

        $response->assertStatus(201)
                ->assertJson([
                    'code' => $companyType->code,
                ]);

        $this->assertDatabaseHas($companyType->getTable(), [
                'code' => $companyType->code,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_company_type()
    {
        $companyType = CompanyType::factory()->create();
        $companyType->code = 'UPDATED';

        $response = $this->actingAs($this->user)
                         ->putJson('/api/company-type/'.$companyType->id, $companyType->toArray());

        $response->assertStatus(200)
                ->assertJson([
                    'code' => $companyType->code,
                ]);

        $this->assertDatabaseHas($companyType->getTable(), [
                    'code' => $companyType->code,
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_company_type()
    {
        $companyType = CompanyType::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/company-type/'.$companyType->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($companyType->getTable(), [
            'code' => $companyType->code,
        ]);
    }
}
