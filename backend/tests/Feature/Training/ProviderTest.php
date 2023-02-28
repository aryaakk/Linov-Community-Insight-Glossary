<?php

namespace Tests\Feature\Training;

use App\Models\Training\Provider;
use App\Models\Training\TrainerProvider;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSchedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->provider = Provider::factory()->make();
    }

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('public/provider');
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_providers()
    {
        Provider::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/provider');

        $response->assertStatus(200)
                 ->assertJson(['total' => 5])
                 ->assertJsonStructure([
                    'total',
                    'data' => [
                        '*'=>[
                            'id',
                            'name',
                            'logo',
                            'state_name',
                            ]
                    ]
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_provider()
    {
        $provider = Provider::factory()->create();
        
        $response = $this->actingAs($this->user)
                         ->get('/api/provider/'.$provider->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $provider->id,
                ]);
    }

    public function test_user_can_get_trainers_by_provider(){
        $provider = Provider::factory()->create();

        TrainerProvider::factory()->count(5)->create([
            'provider_id' => $provider->id
        ]);

        $response = $this->actingAs($this->user)
                         ->get('/api/trainer?provider_id='.$provider->id);

        $response->assertStatus(200)
                ->assertJsonPath('data.0.provider_id', $provider->id);
    }

    public function test_user_can_get_training_event_by_provider(){
        $provider = Provider::factory()->create();

        $trxEvent= TrxEvent::factory()->create([
            'provider_id' => $provider->id
        ]);
        $trxClass= TrxClass::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
        ]);
        TrxSchedule::factory()->count(2)->create([
            'trx_train_event_id' => $trxEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ? $trxClass->id : null,
            'class_inhouse_id' => $trxClass->type_class=='2' ? $trxClass->id : null,
            'is_active' => '1',
        ]);

        $response = $this->actingAs($this->user)
                         ->get('/api/training/all?provider_id='.$provider->id);

        $response->assertStatus(200)
                ->assertJsonPath('data.0.provider_id', $provider->id);
    }
     /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_provider()
    {
        $response= $this->actingAs($this->user)
                         ->postJson('/api/provider', array_merge($this->provider->toArray(), [
                            'logo' => UploadedFile::fake()->image('photo.jpg',100, 100)->size(100),
                            'logo_2' => UploadedFile::fake()->image('photo.jpg',1024, 300)->size(100),
                         ]));
        
        $response->assertStatus(201)
                ->assertJson(['code' => $this->provider->code]);
            
        $this->assertDatabaseHas($this->provider->getTable(), [
                'code' => $this->provider->code,
        ]);
    }

    // public function test_user_can_create_provider_failed_upload()
    // {
    //     unset( $this->provider->logo_2);
    //     $response= $this->actingAs($this->user)
    //                      ->postJson('/api/provider', array_merge($this->provider->toArray(), [
    //                         'logo' => UploadedFile::fake()->image('photo.jpg')->size(100),
    //                      ]));

    //     $response->assertStatus(422)
    //             ->assertJsonPath('errors.logo.0', 'The logo has invalid image dimensions.');
    // }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_provider()
    {
        $provider = Provider::factory()->create();
        unset($provider->logo);
        unset($provider->logo_2);
        $provider->code = 'UPDATED';
        
        $response = $this->actingAs($this->user)
                         ->putJson('/api/provider/'.$provider->id, $provider->toArray());

        $response->assertStatus(200)
                ->assertJson(['code' => 'UPDATED']);

        $this->assertDatabaseHas($this->provider->getTable(), [
                'code' => 'UPDATED',
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_provider()
    {
        $provider = Provider::factory()->create();
        
        $response = $this->actingAs($this->user)
                         ->postJson('/api/provider/deletes',[$provider->id]);

        $response->assertStatus(204);
        
        $this->assertDatabaseMissing($this->provider->getTable(), [
                'code' => $this->provider->code,
        ]);
    }
}
