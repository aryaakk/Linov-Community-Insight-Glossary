<?php

namespace Tests\Feature\Training;

use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxOrder;
use App\Models\Training\TrxParticipant;
use App\Models\Training\TrxSchedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('private/training/order');
        Storage::disk('oss')->deleteDirectory('private/training/payment');
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_order()
    {
        TrxOrder::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
                         ->get('/api/order');

        $response->assertStatus(200)
                 ->assertJson(['total' => 5]);
    }

    public function test_user_can_get_one_order()
    {
        $order = TrxOrder::factory()->create();

        $response = $this->actingAs($this->user)->get('/api/order/'.$order->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $order->id,
                ]);
    }

    public function test_user_can_create_order()
    {
        $order = TrxOrder::factory()->make();

        $order->tf_upload = UploadedFile::fake()->create('tf_upload.jpg')->size(400);

        $order->participant = TrxParticipant::factory()->make([
            'trx_order_id' => null,
        ]);

        $response= $this->actingAs($this->user)->postJson('/api/order', $order->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($order->getTable(), [
            'id' => $response['id'],
        ]);

        $this->assertDatabaseHas('tre_trx_order_participants', [
            'trx_order_id' => $response['id'],
        ]);
    }

    public function test_user_can_create_free_order()
    {
        $trxEvent= TrxEvent::factory()->create();
        $trxClass= TrxClass::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
            'price' => 0,
            'price_discount'=> 0
        ]);
        $trxSchedule = TrxSchedule::factory()->create([
            'trx_train_event_id' => $trxEvent->id,
            'class_public_id' => $trxClass->type_class=='1' ? $trxClass->id : null,
            'class_inhouse_id' => $trxClass->type_class=='2' ? $trxClass->id : null,
            'is_active' => '1',
        ]);
        $order = TrxOrder::factory()->make([
            'schedule_id'=>$trxSchedule->id,
            'class_id' => $trxClass->id,
            'quantity' => 1,
        ]);

        $order->participant = TrxParticipant::factory()->make([
            'trx_order_id' => null,
        ]);

        $order->tf_upload = UploadedFile::fake()->image('image.jpg')->size(100);

        $response= $this->actingAs($this->user)->postJson("/api/order/event/free", $order->toArray());

        $response->assertStatus(201);

        $this->assertDatabaseHas($order->getTable(), [
            'id' => $response['id'],
        ]);

        $this->assertDatabaseHas('tre_trx_order_participants', [
            'trx_order_id' => $response['id'],
        ]);
    }

    /**
     * @group ignore
     */
    public function test_user_can_tf_upload_order()
    {
        $order = TrxOrder::factory()->create();
        $order->tf_upload = UploadedFile::fake()->create('tf_upload.jpg')->size(400);

        $response= $this->actingAs($this->user)
                         ->postJson('/api/order/'.$order->id, $order->toArray());
        
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $order->id,
                ]);
    }

    public function test_user_can_delete_order()
    {
        $order = TrxOrder::factory()->create();

        $order->participants = json_encode(TrxParticipant::factory()->count(2)->create([
            'trx_order_id' => $order->id,
        ]));

        $response= $this->actingAs($this->user)
                         ->delete('/api/order/'.$order->id);
     
        $response->assertStatus(204);

        $this->assertDatabaseMissing($order->getTable(), [
            'id' => $order->id,
        ]);

        $this->assertDatabaseMissing('tre_trx_order_participants', [
            'trx_order_id' => $order->id,
        ]);
    }
}
