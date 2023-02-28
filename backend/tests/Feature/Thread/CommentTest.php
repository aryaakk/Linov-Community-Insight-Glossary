<?php

namespace Tests\Feature\Thread;

use App\Models\Threads\Comment;
use App\Models\Threads\Thread;
use App\Models\Threads\TypeQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('public/comment/photos');
        Storage::disk('oss')->deleteDirectory('public/comment/upfiles');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_comment()
    {
        $thread = Thread::factory()->create();

        Comment::factory()->count(5)->create([
            'thread_id' => $thread->id,
        ]);
        $response = $this->actingAs($this->user)->get("/api/comments/$thread->id");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_subcomment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->create([
            'thread_id' => $thread->id,
        ]);

        Comment::factory()->count(5)->create([
            'thread_id' => $thread->id,
            'comment_thread_id' => $comment->id
        ]);

        $response = $this->actingAs($this->user)->get("/api/comments/$thread->id");

        $response->assertStatus(200)
                ->assertJsonCount(5, '0.subcomment');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_comment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->make([
            'thread_id' => $thread->id,
        ]);

        $comment->mode = 'thread';

        $response= $this->actingAs($this->user)->postJson("/api/comments/$thread->id", $comment->toArray());

        $response->assertStatus(201)
                ->assertJson(['comment' => $comment->comment]);

        $this->assertDatabaseHas($comment->getTable(), [
            'comment' => $comment->comment,
        ]);

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
    public function test_user_can_create_sub_comment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id'   => User::factory()->create()->id
        ]);

        $subcomment= Comment::factory()->make([
            'thread_id' => $thread->id
        ]);

        $subcomment->mode = 'comment';

        $response= $this->actingAs($this->user)->postJson("/api/comments/$comment->id", $subcomment->toArray());

        $response->assertStatus(201)
                ->assertJson(['comment' => $subcomment->comment]);

        $this->assertDatabaseHas($subcomment->getTable(), [
            'comment' => $subcomment->comment,
        ]);

        $this->assertDatabaseHas('com_notifications', [
            'notifiable_id' => $comment->user_id,
            'detail_id' => $comment->id
        ]);
    }

    public function test_user_can_create_file_comment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->make([
            'thread_id' => $thread->id,
        ]);

        $comment->mode = 'thread';
        $comment->photos  = [UploadedFile::fake()->create('test.jpg', 100)];
        $comment->upfiles = [UploadedFile::fake()->create('test.pdf', 100)];

        $response= $this->actingAs($this->user)->postJson("/api/comments/$thread->id", $comment->toArray());

        $response->assertStatus(201)
                ->assertJson(['comment' => $comment->comment]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_toggle_love_comment()
    {
        $comment= Comment::factory()->create([
            'user_id'  => User::factory()->create()->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/comments/$comment->id/love");

        $response->assertStatus(200)
                ->assertJson(['love_status' => true]);

        $response = $this->actingAs($this->user)->postJson("/api/comments/$comment->id/love");

        $response->assertStatus(200)
                ->assertJson(['love_status' => false]);

        $this->assertDatabaseHas('com_notifications', [
            'notifiable_id' => $comment->user_id,
            'detail_id' => $comment->id
        ]);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_comment()
    {
        $comment= Comment::factory()->create([
            'user_id'  => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/comments/$comment->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted($comment->getTable(), [
                'id' => $comment->id,
        ]);
    }
}
