<?php

namespace Tests\Feature\Consults;

use App\Models\Consults\Category;
use App\Models\Consults\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('private/trx-consultant/upfiles');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_consult_thread()
    {
        $threads = Thread::factory()->count(5)->create();

        $response= $this->actingAs($this->user)->get('/api/consulting');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5)
                ->assertJsonCount(5, 'data');
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_consult_thread()
    {
        $thread = Thread::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/consulting/$thread->slug_id/show");

        $response->assertStatus(200)
                ->assertJsonPath('id', $thread->id);

    }

    //     /**
    //  * A basic feature test example.
    //  *
    //  * @return void
    //  */
    // public function test_user_can_get_all_consultant()
    // {
    //     User::factory()->count(5)->create([
    //         'is_consultant'=>'1', 
    //         'category_consultant_id' => Category::factory()->create()->id
    //     ]);

    //     $response= $this->actingAs($this->user)->get('/api/consultant');

    //     $response->assertStatus(200)
    //             ->assertJsonCount(5);
    // }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_consult_thread()
    {
        $thread = Thread::factory()->make();

        $response= $this->actingAs($this->user)->postJson('/api/consulting', $thread->toArray());

        $response->assertStatus(201)
                ->assertJson(['title' => $thread->title]);

        $this->assertDatabaseHas($thread->getTable(), [
            'title' => $thread->title,
        ]);
    }

    public function test_user_can_create_file_consult_thread()
    {
        $thread = Thread::factory()->make();
        $thread->photos  = [UploadedFile::fake()->create('test.jpg', 100)];
        $thread->upfiles = [UploadedFile::fake()->create('test.pdf', 100)];

        $response= $this->actingAs($this->user)->postJson('/api/consulting', $thread->toArray());

        $response->assertStatus(201)
                ->assertJson(['title' => $thread->title]);

        $this->assertDatabaseHas($thread->getTable(), [
            'title' => $thread->title,
        ]);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_toggle_love_consult_thread()
    {
        $thread = Thread::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/consulting/{$thread->id}/love");

        $response->assertStatus(200)
                ->assertJson(['love_status' => true]);

        $response = $this->actingAs($this->user)->postJson("/api/consulting/{$thread->id}/love");

        $response->assertStatus(200)
                ->assertJson(['love_status' => false]);

        $this->assertDatabaseHas('com_notifications', [
            'notifiable_id' => $thread->user_id,
            'detail_id' => $thread->id
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_consult_thread()
    {
        $thread = Thread::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/consulting/'.$thread->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted($thread->getTable(), [
                'id' => $thread->id,
        ]);
    }
}
