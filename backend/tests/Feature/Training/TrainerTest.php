<?php

namespace Tests\Feature\Training;

use App\Models\Profiles\UserSocial;
use App\Models\Training\Trainer;
use App\Models\Training\TrainerCertificate;
use App\Models\Training\TrainerDetail;
use App\Models\Training\TrainerEducation;
use App\Models\Training\TrainerProvider;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSchedule;
use App\Models\Training\TrxTrainer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use DatabaseTransactions;
    protected $trainer;

    public function setUp(): void
    {
        parent::setUp();
        $this->trainer = Trainer::factory()->make();
    }

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('public/trainer');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_trainers()
    {
        Trainer::factory()->count(5)->create(['is_active'=>'1']);

        $response = $this->get('/api/trainer');
   
        $response->assertStatus(200)
                ->assertJson(['total' => 5])
                ->assertJsonStructure([
                    'total',
                    'data' => [
                        '*'=>[
                            'id',
                            'name',
                            'photo'
                            ]
                    ]
                ]);
    }

    
    public function test_admin_can_get_all_paging_trainer()
    {
       Trainer::factory()->count(5)->create();
        
        $response = $this->withHeaders(['Origin'=>config('app.admin_url')])
                        ->actingAs($this->user)
                        ->get("/api/trainer?perpage=10&page=1");

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_all_search_trainer()
    {
        $trainers =Trainer::factory()->count(2)->create();
        $query  = $trainers->first()->code;
        
        $response = $this->withHeaders(['Origin'=>config('app.admin_url')])
                         ->actingAs($this->user)
                         ->get("/api/trainer?perpage=10&page=1&columns[0]=code&search=$query");

        $response->assertStatus(200)
                ->assertJsonPath('total', 1)
                ->assertJsonCount(1, 'data');
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_trainer()
    {
        $trainer = Trainer::factory()->create();
        TrainerProvider::factory()->count(1)->create(['trainer_id' => $trainer->id, 'is_active' => '1']);
        TrainerDetail::factory()->count(2)->create(['trainer_id' => $trainer->id]);
        TrainerEducation::factory()->count(1)->create(['trainer_id' => $trainer->id]);
        UserSocial::factory()->count(1)->create(['user_id'=>$trainer->id]);
        TrainerCertificate::factory()->count(1)->create(['trainer_id' => $trainer->id]);

        $response= $this->get('/api/trainer/'.$trainer->id);

        $response->assertStatus(200)
                ->assertJson(['id' => $trainer->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

     public function test_user_can_get_trainer_course(){
        $trainer = Trainer::factory()->create();
        $trxEvent= TrxEvent::factory()->create();
        $trxClass= TrxClass::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
        ]);
        TrxSchedule::factory()->count(2)->create([
            'trx_train_event_id' => $trxEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ? $trxClass->id : null,
            'class_inhouse_id' => $trxClass->type_class=='2' ? $trxClass->id : null,
            'is_active' => '1',
        ]);
        TrxTrainer::factory()->count(3)->create(['trainer_id' => $trainer->id, 'trx_train_event_id' => $trxEvent->id]);

        $response = $this->get('/api/trainer/'.$trainer->id.'/course');

        $response->assertStatus(200)
                ->assertJson(function($json){
                    $json->has(3);
                });
     }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_trainer()
    {
        // TODO: add provider
        $response = $this->actingAs($this->user)
                    ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
                        'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
                    ]));

        $response->assertStatus(201)
                 ->assertJson(['code' => $this->trainer->code]);
    
        $this->assertDatabaseHas($this->trainer->getTable(), [
                'code' => $this->trainer->code,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_new_education_trainer()
    {
        $educations = TrainerEducation::factory()->count(2)->make([
            'title_degree_id' => null,
            'university_id' => null,
            'major_id' => null,
        ]);

        $response = $this->actingAs($this->user)
                    ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
                        'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
                        'educations' => json_encode($educations->toArray()),
                    ]));

        $response->assertStatus(201)
                ->assertJson(['code' => $this->trainer->code]);
    
        $this->assertDatabaseHas('tre_train_educations', [
            'trainer_id' => $response['id'],
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_existing_education_trainer()
    {
        $educations = TrainerEducation::factory()->count(2)->make([
            'other_title' => null,
            'other_university' => null,
            'other_major' => null,
        ]);

        $response = $this->actingAs($this->user)
                    ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
                        'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
                        'educations' => json_encode($educations),
                    ]));

        $response->assertStatus(201)
                ->assertJson(['code' => $this->trainer->code]);
    
        $this->assertDatabaseHas('tre_train_educations', [
            'trainer_id' => $response['id'],
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_experience_trainer()
    {
        $experiences = TrainerDetail::factory()->count(1)->make([
            'trainer_id' => null,
            'created_by' => null,
        ]);

        $response = $this->actingAs($this->user)
                    ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
                        'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
                        'experiences' => json_encode($experiences),
                    ]));

        $response->assertStatus(201)
                    ->assertJson(['code' => $this->trainer->code]);
        
        $this->assertDatabaseHas('tre_trainer_details', [
            'trainer_id' => $response['id'],
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_provider_trainer()
    {
        $providers = TrainerProvider::factory()->count(1)->make([
            'trainer_id' => null,
        ]);

        $response = $this->actingAs($this->user)
        ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
            'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
            'providers' => json_encode($providers),
        ]));

        $response->assertStatus(201)
                ->assertJson(['code' => $this->trainer->code]);

        $this->assertDatabaseHas('tre_train_providers', [
            'trainer_id' => $response['id'],
        ]);
    }

    public function test_user_can_create_socmed_trainer()
    {
        $socials = UserSocial::factory()->count(1)->make([
            'user_id' => null,
        ]);

        $response = $this->actingAs($this->user)
        ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
            'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
            'socials' => json_encode($socials),
        ]));
 
        $response->assertStatus(201)
                ->assertJson(['code' => $this->trainer->code]);

        $this->assertDatabaseHas('com_user_socials', [
            'user_id' => $response['id'],
        ]);
    }

    public function test_user_can_create_certificate_trainer()
    {
        $certificate = TrainerCertificate::factory()->count(1)->make([
            'trainer_id' => null,
        ]);

        $response = $this->actingAs($this->user)
        ->postJson('/api/trainer', array_merge($this->trainer->toArray(), [
            'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
            'certificates' => json_encode($certificate),
        ]));

        $response->assertStatus(201)
                ->assertJson(['code' => $this->trainer->code]);

        $this->assertDatabaseHas('tre_trainer_certificate', [
            'trainer_id' => $response['id'],
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_trainer()
    {
        $trainer = Trainer::factory()->create();
        $trainer->code = 'UPDATED';
        $trainer->skills = json_encode($trainer->skills);
        $response= $this->actingAs($this->user)
                    ->putJson('/api/trainer/'.$trainer->id, array_merge($trainer->toArray(), [
                        'photo' => UploadedFile::fake()->image('photo.jpg')->size(100),
                    ]));
     
        $response->assertStatus(200)
            ->assertJson(['code' => $trainer->code]);

        $this->assertDatabaseHas($trainer->getTable(), [
            'code' => $trainer->code,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_trainer(){
        $trainer = Trainer::factory()->count(2)->create();

        $response = $this->actingAs($this->user)
                         ->postJson('/api/trainer/deletes', $trainer->pluck('id')->toArray());

        $response->assertStatus(204);

        $this->assertDatabaseMissing($trainer->first()->getTable(), [
                'code' => $trainer->first()->code,
        ]);
    }
}
