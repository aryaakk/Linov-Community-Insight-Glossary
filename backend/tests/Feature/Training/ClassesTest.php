<?php

namespace Tests\Feature\Training;

use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_classes_by_training()
    {
        $trainEvent = TrxEvent::factory()->create();

        TrxClass::factory()->count(5)->create([
            'trx_train_event_id' => $trainEvent->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->get('/api/class?event_id='.$trainEvent->id);

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
    public function test_user_can_create_class(){
        $class = TrxClass::factory()->make();

        $response = $this->actingAs($this->user)
                         ->postJson('/api/class', $class->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($class->getTable(), [
                'trx_train_event_id' => $class->trx_train_event_id,
        ]);
    }

    public function test_user_can_edit_class(){
        $class = TrxClass::factory()->create();
        $class->seq = $class->seq+1;

        $response = $this->actingAs($this->user)
                         ->putJson('/api/class/'.$class->id, $class->toArray());
  
        $response->assertStatus(200)
                 ->assertJson([
                    'seq' => $class->seq,
                 ]);

        $this->assertDatabaseHas($class->getTable(), [
                'id'  => $response['id'],
                'seq' => $class->seq,
        ]);
    }

    public function test_user_can_delete_class(){
        $class = TrxClass::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/class/'.$class->id);
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing($class->getTable(), [
            'id' => $class->id,
        ]);
    }
}
