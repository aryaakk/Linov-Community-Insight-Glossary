<?php

namespace Tests\Feature\Training;

use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSyllabus;
use App\Models\Training\TrxSyllabusDetail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SyllabusTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_syllabus_by_training()
    {
        $trainEvent = TrxEvent::factory()->create();

        TrxSyllabus::factory()->count(5)->create([
            'trx_train_event_id' => $trainEvent->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->get('/api/syllabus/'.$trainEvent->id);
        // dd($response->getContent());
        $response->assertStatus(200)
                 ->assertJson(function($json) {
                    $json->has(5);
                 })
                 ->assertJsonStructure([
                    '*' => [
                        'id',
                        'title',
                        'sub_title',
                        'details' => [
                            '*' => [
                                'id',
                                'syllabus_id',
                                'icon',
                                'head',
                                'description'
                            ]
                        ]
                    ]
                 ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_syllabus(){
        $syllabus = TrxSyllabus::factory()->make();

        $response = $this->actingAs($this->user)
                         ->postJson('/api/syllabus', $syllabus->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($syllabus->getTable(), [
            'id' => $response['id'],
        ]);
    }

    public function test_user_can_create_detail_syllabus(){
        $detail = TrxSyllabusDetail::factory()->make()->toArray();

        $response = $this->actingAs($this->user)->postJson('/api/syllabus', ['details'=>$detail]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tre_trx_syllabus_details', [
            'syllabus_id' => $detail['syllabus_id'],
        ]);

    }

    public function test_user_can_edit_syllabus(){
        $syllabus = TrxSyllabus::factory()->create();

        $syllabus->title = 'new title';

        $response = $this->actingAs($this->user)
                         ->putJson('/api/syllabus/'.$syllabus->id, $syllabus->toArray());

        $response->assertStatus(200)
                 ->assertJson([
                    'title' => $syllabus->title,
                 ]);

        $this->assertDatabaseHas($syllabus->getTable(), [
                'id'  => $response['id'],
                'title' => $syllabus->title,
        ]);
    }

    public function test_user_can_edit_detail_syllabus(){
        $detail = TrxSyllabusDetail::factory()->create();

        $detail->head = 'new head';

        $response = $this->actingAs($this->user)
                         ->putJson('/api/syllabus/'.$detail->id, ['details'=>$detail->toArray()]);

        $response->assertStatus(200)
                 ->assertJson([
                    'head' => $detail->head,
                 ]);

        $this->assertDatabaseHas($detail->getTable(), [
                'id'  => $response['id'],
                'head' => $detail->head,
        ]);
    }

    public function test_user_can_delete_syllabus(){
        $syllabus = TrxSyllabus::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/syllabus/'.$syllabus->id);
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing($syllabus->getTable(), [
            'id' => $syllabus->id,
        ]);
    }

    public function test_user_can_delete_detail_syllabus(){
        $syllabus = TrxSyllabusDetail::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/syllabus/'.$syllabus->id);
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing('tre_trx_syllabus_details', [
            'syllabus_id' => $syllabus->id,
        ]);
    }
}
