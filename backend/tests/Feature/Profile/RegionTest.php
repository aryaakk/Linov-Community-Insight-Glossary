<?php

namespace Tests\Feature\Profile;

use App\Models\Profiles\City;
use App\Models\Profiles\PhoneCode;
use App\Models\Profiles\State;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegionTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_country()
    {
        PhoneCode::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/regions");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_state()
    {
        $country = PhoneCode::factory()->create();
        State::factory()->count(5)->create([
            'phone_code_id'=>$country->id
        ]);
        
        $response = $this->actingAs($this->user)->get("/api/regions/$country->id");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

            /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_city()
    {
        $state = State::factory()->create();
        City::factory()->count(5)->create([
            'state_id'=>$state->id
        ]);
        
        $response = $this->actingAs($this->user)->get("/api/regions/$state->phone_code_id/$state->id");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }
}
