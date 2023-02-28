<?php

namespace Tests\Feature\Training;

use App\Models\Training\Trainer;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxSchedule;
use App\Models\Training\TrxTrainer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainingEventTest extends TestCase
{
    use DatabaseTransactions;
    
    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('public/training/banner');
        Storage::disk('oss')->deleteDirectory('public/training/banner_slide');
        parent::tearDown();
    }

    public function createTraining($param){
        $trxEvent= TrxEvent::factory()->create($param);
        $trxClass= TrxClass::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
        ]);
        TrxSchedule::factory()->count(2)->create([
            'trx_train_event_id' => $trxEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ? $trxClass->id : null,
            'class_inhouse_id' => $trxClass->type_class=='2' ? $trxClass->id : null,
            'is_active' => '1',
        ]);
        return $trxEvent;
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_premium_banner()
    {
        TrxEvent::factory()->count(3)->create(['is_ads'=>'1']);

        $response = $this->actingAs($this->user)
                         ->get('/api/training/premium');

        $response->assertStatus(200)
                 ->assertJson(function($json) {
                    $json->has(3);
                 })
                 ->assertJsonStructure([
                    '*'=>[
                        'id',
                        'title',
                        'type',
                        'banner_slide',
                    ]
            ]);
    }

    public function test_user_can_get_calendar_schedule()
    {
        $trxEvent= TrxEvent::factory()->create([
            'trx_code' => 'TR-TN',
            'type' => 'training',
            'type_question_id'=>'e1d9af57-62c1-4c80-bbbb-f3906788f8d7'
        ]);
        $trxClass= TrxClass::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
        ]);
        $trxSchedule= TrxSchedule::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ? $trxClass->id : null,
            'class_inhouse_id' => $trxClass->type_class=='2' ? $trxClass->id : null,
            'is_active' => '1',
        ]);

        $response = $this->actingAs($this->user)->get("/api/training/calendar?start=$trxSchedule->date&end=".\Carbon\Carbon::parse($trxSchedule->end_date)->addDay()->toDateString());
        $response->assertJsonCount(1);
    }

    public function test_user_can_get_all_training()
    {
        for ($i=0; $i < 5 ; $i++) { 
            $this->createTraining([
                'trx_code' => 'TR-TN',
                'type' => 'training',
            ]);
        }

        $response = $this->actingAs($this->user)
                         ->get('/api/training/training');
        
        $response->assertStatus(200)
                // ->assertJson(['total' => 5])
                ->assertJsonPath('data.0.type', 'training')
                ->assertJsonStructure([
                    'total',
                    'data' => [
                        '*'=>[
                            'id',
                            'title',
                            'type',
                            'provider_id',
                            'location',
                            'level',
                            'type_question_id',
                            'price', 
                            'date', 
                            'start_hour', 
                            'end_hour',
                            'provider',
                            'category'
                            ]
                    ]
                ]);
    }

    public function test_user_can_get_all_event()
    {
        for ($i=0; $i < 5 ; $i++) { 
            $this->createTraining([
                'trx_code' => 'TR-EN',
                'type' => 'event',
            ]);
        }

        $response = $this->actingAs($this->user)
                         ->get('/api/training/event');

        $response->assertStatus(200)
                ->assertJson(['total' => 5])
                ->assertJsonPath('data.0.type', 'event');
    }

    public function test_user_can_get_all_class_event()
    {
        for ($i=0; $i < 5 ; $i++) { 
            $this->createTraining([
                'trx_code' => 'TR-EN',
                'type' => 'event',
            ]);
        }

        $response = $this->actingAs($this->user)
                         ->get('/api/training/event?class=Public');

        $response->assertStatus(200)
                ->assertJsonPath('data.0.type_class', '1');
    }

    public function test_user_can_get_one_training_or_event()
    {
        $training = $this->createTraining([]);

        TrxTrainer::factory()->create([
            'trx_train_event_id' => $training->id
        ]);
        
        $response = $this->actingAs($this->user)
                         ->get("/api/training/$training->type/$training->slug_id");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $training->id,
                ]);
                // ->assertJsonStructure([
                //     'id',
                //     'trx_code',
                //     'trx_number'
                // ]);
    }

    public function test_user_can_create_training_or_event()
    {
        $training = TrxEvent::factory()->make([
            'banner' => UploadedFile::fake()->image('banner.jpg')->size(300),
            'banner_card' => UploadedFile::fake()->image('banner_card.jpg')->size(300),
        ]);
        $training->trainer_id = Trainer::factory()->count(1)->create()->pluck('id')->toArray();
        $training->class = TrxClass::factory()->make()->toArray();
        $training->schedules = TrxSchedule::factory()->count(1)->make()->toArray();
        unset($training->banner_slide);
 
        $response= $this->actingAs($this->user)
                         ->postJson("/api/training", $training->toArray());

        $response->assertStatus(201)
                ->assertJson(['trx_code' => $training->trx_code]);
        
        $this->assertDatabaseHas($training->getTable(), [
                'id' => $response['id'],
        ]);
    }

    public function test_user_can_update_training_or_event()
    {
        $training = TrxEvent::factory()->create();
        $training->title = 'New Title';
        $training->trainer_id = Trainer::factory()->count(1)->create()->pluck('id')->toArray();
        $training->class = TrxClass::factory()->create(['trx_train_event_id'=>$training->id])->toArray();
        $training->schedules = TrxSchedule::factory()->count(1)->create(['trx_train_event_id'=>$training->id, 'class_public_id'=>$training->class['id']])->toArray();
        unset($training->banner);
        unset($training->banner_card);
        unset($training->banner_slide);

        $response= $this->actingAs($this->user)
                         ->putJson("/api/training/$training->id", $training->toArray());

        $response->assertStatus(200)
                ->assertJson(['trx_code' => $training->trx_code]);
        
        $this->assertDatabaseHas($training->getTable(), [
            'id' => $response['id'],
            'title' => $training->title,
        ]); 
    }

    public function test_user_can_delete_training_or_event()
    {
        $training = TrxEvent::factory()->create();
        
        $response= $this->actingAs($this->user)
                         ->postJson("/api/training/deletes", [$training->id]);

        $response->assertStatus(204);
        
        $this->assertDatabaseMissing($training->getTable(), [
                'id' => $training->id,
        ]); 
    }
}
