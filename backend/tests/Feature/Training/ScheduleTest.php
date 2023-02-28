<?php

namespace Tests\Feature\Training;

use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSchedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_schedule_by_training()
    {
        $trainEvent = TrxEvent::factory()->create();

        $trxClass = TrxClass::factory()->count(3)->create([
            'trx_train_event_id' => $trainEvent->id,
        ]);
        foreach ($trxClass as $class) {
            TrxSchedule::factory()->count(2)->create([
                'trx_train_event_id' => $trainEvent->id,
                'class_public_id' => $class->type_class=='1' ?  $class->id : null,
                'class_inhouse_id'=> $class->type_class=='2' ?  $class->id : null,
                'is_active' => '1',
            ]);
        }

        $response = $this->actingAs($this->user)
                         ->get('/api/schedule/'.$trainEvent->id);
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    '*'=>[
                        '*'=>[
                            'id',
                            'type_class',
                            'date',
                            'end_date',
                            'day',
                            'start_hour',
                            'end_hour',
                            'class_id'
                        ]
                    ]
                ]);
    }

    public function test_user_can_get_one_schedule()
    {
        $trainEvent = TrxEvent::factory()->create();
        $trxClass = TrxClass::factory()->create([
            'trx_train_event_id' => $trainEvent->id,
        ]);
        $schedule = TrxSchedule::factory([
            'trx_train_event_id' => $trainEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ?  $trxClass->id : null,
            'class_inhouse_id'=> $trxClass->type_class=='2' ?  $trxClass->id : null,
            'is_active' => '1',
        ])->create();

        $response = $this->get('/api/schedule/'.$schedule->id.'/show');

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $schedule->id,
                ])
                ->assertJsonStructure([
                    'id',
                    'type',
                    'title',
                    'level',
                    'trx_train_event_id',
                    'class_public_id',
                    'class_inhouse_id',
                    'date',
                    'end_date',
                    'day',
                    'start_hour',
                    'end_hour',
                    'class_public',
                    'class_inhouse'
                ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_schedule(){
        $schedule = TrxSchedule::factory()->make();
        if($schedule->class_public_id)
            unset($schedule->class_inhouse_id);
        else
            unset($schedule->class_public_id);

        $response = $this->actingAs($this->user)
                         ->postJson('/api/schedule', $schedule->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($schedule->getTable(), [
                'id' => $response['id'],
        ]);
    }

    public function test_user_can_edit_schedule(){
        $schedule = TrxSchedule::factory()->create();
        
        if($schedule->class_public_id)
            unset($schedule->class_inhouse_id);
        else
            unset($schedule->class_public_id);

        $schedule->date = \Carbon\Carbon::parse($schedule->date)->addDay(1)->format('Y-m-d');

        $response = $this->actingAs($this->user)
                         ->putJson('/api/schedule/'.$schedule->id, $schedule->toArray());
  
        $response->assertStatus(200)
                 ->assertJson([
                    'date' => $schedule->date,
                 ]);

        $this->assertDatabaseHas($schedule->getTable(), [
                'id'  => $response['id'],
                'date' => $schedule->date,
        ]);
    }

    public function test_user_can_delete_schedule(){
        $schedule = TrxSchedule::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/schedule/'.$schedule->id);
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing($schedule->getTable(), [
            'id' => $schedule->id,
        ]);
    }
}
