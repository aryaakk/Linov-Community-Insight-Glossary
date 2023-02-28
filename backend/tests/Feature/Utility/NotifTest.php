<?php

namespace Tests\Feature\Utility;

use App\Models\ComNotification;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotifTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_notif()
    {
        ComNotification::factory()->count(3)->create([
            'notifiable_id'=>$this->user->id,
            'created_by' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($this->user)->get('/api/notifications');

        $response->assertStatus(200)
                ->assertJsonPath('total', 3)
                ->assertJsonCount(3, 'data');
    }

    
    public function test_user_can_read_notif()
    {
        $notif = ComNotification::factory()->create([
            'notifiable_id'=>$this->user->id,
            'created_by' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/notifications', [$notif->id]);

        $response->assertStatus(200)
                 ->assertJson([
                    'total' => 1
                 ]);

        $this->assertDatabaseHas($notif->getTable(), [
            'id' => $notif->id,
        ]);
    }

    public function test_user_can_read_all_notif()
    {
        $notif = ComNotification::factory()->count(3)->create([
            'notifiable_id'=>$this->user->id,
            'created_by' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/notifications', $notif->pluck('id')->toArray());

        $response->assertStatus(200)
                 ->assertJson([
                    'total' => 3
                 ]);

        $this->assertDatabaseHas($notif->first()->getTable(), [
            'notifiable_id' => $this->user->id,
        ]);
    }
}
