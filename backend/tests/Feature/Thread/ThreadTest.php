<?php

namespace Tests\Feature\Thread;

use App\Models\Threads\Love;
use App\Models\Threads\PollDetail;
use App\Models\Threads\PollDuration;
use App\Models\Threads\Polling;
use App\Models\Threads\Report;
use App\Models\Threads\Thread;
use App\Models\Threads\Type;
use App\Models\Threads\TypeQuestion;
use App\Models\Threads\View;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('public/threads/photos');
        Storage::disk('oss')->deleteDirectory('public/threads/upfiles');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_most_discuss()
    {
        $threads = Thread::factory()->count(5)->create();

        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
            ]);

            View::factory()->count(rand(1,3))->create([
                'thread_id' => $thread->id,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/threads/most-discuses');

        $response->assertStatus(200)
                ->assertJson(function($json) {
                $json->has(3);
                });
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_thread()
    {
        $threads = Thread::factory()->count(5)->create();
        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/threads');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_thread()
    {
        $thread = Thread::factory()->create();

        $response = $this->actingAs($this->user)->get("/api/threads/$thread->slug_id/show");

        $response->assertStatus(200)
                ->assertJsonPath('id', $thread->id);
    }

     /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_related_thread()
    {
        $threads = Thread::factory()->count(4)->create();

        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
                'type_question_id' => Type::first()->id
            ]);
        }
        
        $response = $this->actingAs($this->user)->get("/api/threads/{$threads[0]->id}/related");

        $response->assertStatus(200)
                ->assertJson(function($json) {
                $json->has(3);
                });
    }

    public function test_admin_can_get_reported_thread()
    {
        $threads = Thread::factory()->count(3)->create();
        foreach ($threads as $key => $thread) {
            Report::factory()->create([
                'thread_id' => $thread->id,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/threads/reported');

        $response->assertStatus(200)
                ->assertJsonPath('total', 3);
    }

    public function test_admin_can_get_report_chart_thread()
    {
        $thread = Thread::factory()->create();

        Report::factory()->count(3)->create(['thread_id'=>$thread->id]);

        $response = $this->actingAs($this->user)->get("/api/threads/$thread->id/chart");

        $response->assertStatus(200)
                ->assertJsonPath('datas.0', 3);
    }

    public function test_admin_can_get_reporter_of_thread()
    {
        $thread = Thread::factory()->create();

        Report::factory()->count(3)->create(['thread_id'=>$thread->id]);
        
        $response = $this->actingAs($this->user)->get("/api/threads/$thread->id/reporters");

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_toggle_visibility()
    {
        $thread = Thread::factory()->create();
        
        $response = $this->actingAs($this->user)->get("/api/threads/$thread->id/visibility");

        $response->assertStatus(200);
        $this->assertNotNull($response->json('deleted_at'));

        $response = $this->actingAs($this->user)->get("/api/threads/$thread->id/visibility");

        $response->assertStatus(200)
                ->assertJson(['deleted_at'=>null]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_thread()
    {
        $thread = Thread::factory()->make();
        $thread->question_id = Type::limit(2)->pluck('id')->toArray();

        $response= $this->actingAs($this->user)->postJson('/api/threads', $thread->toArray());

        $response->assertStatus(201)
                ->assertJson(['title' => $thread->title]);

        $this->assertDatabaseHas($thread->getTable(), [
            'title' => $thread->title,
        ]);
    }

    public function test_user_can_create_file_thread()
    {
        $thread = Thread::factory()->make();
        $thread->question_id = Type::limit(2)->pluck('id')->toArray();
        $thread->photos  = [UploadedFile::fake()->create('test.jpg', 100)];
        $thread->upfiles = [UploadedFile::fake()->create('test.pdf', 100)];

        $response= $this->actingAs($this->user)->postJson('/api/threads', $thread->toArray());

        $response->assertStatus(201)
                ->assertJson(['title' => $thread->title]);

        $this->assertDatabaseHas($thread->getTable(), [
            'title' => $thread->title,
        ]);
    }

    public function test_user_can_edit_thread()
    {
        $thread = Thread::factory()->create();
        $thread->question_id = Type::limit(2)->pluck('id')->toArray();
        $thread->title = "New title";

        $response= $this->actingAs($this->user)->postJson("/api/threads/$thread->id/edit", $thread->toArray());

        $response->assertStatus(201)
                ->assertJson(['title' => $thread->title]);

        $this->assertDatabaseHas($thread->getTable(), [
            'title' => $thread->title,
        ]);
    }

    public function test_user_can_edit_thread_with_file()
    {
        $thread = Thread::factory()->create();
        $thread->question_id = Type::limit(2)->pluck('id')->toArray();
        $thread->title = "New title";
        $thread->photos  = [UploadedFile::fake()->create('test.jpg', 100)];
        $thread->upfiles = [UploadedFile::fake()->create('test.pdf', 100)];

        $response= $this->actingAs($this->user)->postJson("/api/threads/$thread->id/edit", $thread->toArray());

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
    public function test_user_can_report_thread()
    {
        $thread = Thread::factory()->create();
        TypeQuestion::factory()->create([
            'thread_id' => $thread->id,
            'type_question_id' => Type::first()->id
        ]);
        $report = Report::factory()->make([
            'thread_id' => $thread->id,
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/report", $report->toArray());

        $response->assertStatus(200)
                ->assertJson(['thread_id' => $thread->id]);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_types()
    {
        $totalType = Type::count();

        $response = $this->actingAs($this->user)->get("/api/types");

        $response->assertStatus(200)
                ->assertJson(function($json) use($totalType){
                    $json->has($totalType);
                });
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_toggle_love()
    {
        $thread = Thread::factory()->create();
        TypeQuestion::factory()->create([
            'thread_id' => $thread->id,
            'type_question_id' => Type::first()->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/love");

        $response->assertStatus(200)
                ->assertJson(['love_status' => true]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/love");

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
    public function test_user_can_toggle_bookmark()
    {
        $thread = Thread::factory()->create();
        TypeQuestion::factory()->create([
            'thread_id' => $thread->id,
            'type_question_id' => Type::first()->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/bookmark");

        $response->assertStatus(200)
                ->assertJson(['bookmark_status' => true]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/bookmark");

        $response->assertStatus(200)
                ->assertJson(['bookmark_status' => false]);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_toggle_dissmis()
    {
        $thread = Thread::factory()->create();
        TypeQuestion::factory()->create([
            'thread_id' => $thread->id,
            'type_question_id' => Type::first()->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/dismiss");

        $response->assertStatus(200)
                ->assertJson(['dismiss_status' => true]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/dismiss");

        $response->assertStatus(200)
                ->assertJson(['dismiss_status' => false]);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_polling_vote()
    {
        $thread = Thread::factory()->create();
        $polling= Polling::factory()->create([
            'thread_id' => $thread->id,
        ]);

        $detail = PollDetail::factory()->create([
            'thread_poll_id' => $polling->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/threads/{$thread->id}/polling", [
            'poll_id'  => $polling->id,
            'detail_id'=> $detail->id,
        ]);

        $response->assertStatus(200)
                ->assertJson(['id'=>$polling->id,'thread_id'=>$thread->id,]);
    }

    public function test_user_can_get_polling_duration()
    {
        PollDuration::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get("/api/poll-duration");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }


        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_thread()
    {
        $thread = Thread::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson('/api/threads/'.$thread->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted($thread->getTable(), [
                'id' => $thread->id,
        ]);
    }
}
