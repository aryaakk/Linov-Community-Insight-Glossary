<?php

namespace Tests\Feature\Consults;

use App\Models\Consults\Comment;
use App\Models\Consults\Thread;
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
        Storage::disk('oss')->deleteDirectory('public/consulting/thread-photos');
        Storage::disk('oss')->deleteDirectory('public/consulting/thread-upfiles');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_const_comment()
    {
        $thread = Thread::factory()->create();

        Comment::factory()->count(5)->create([
            'consul_thread_id' => $thread->id,
        ]);

        $response = $this->actingAs($this->user)->get("/api/consulting/comments/$thread->id");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_save_const_comment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->make([
            'consul_thread_id' => $thread->id,
            'user_id' => User::factory()->create()->id
        ]);

        $comment->mode = 'thread';

        $response= $this->actingAs($this->user)->postJson("/api/consulting/comments/$thread->id", $comment->toArray());

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

    public function test_user_can_create_const_file_comment()
    {
        $thread = Thread::factory()->create();

        $comment= Comment::factory()->make([
            'consul_thread_id' => $thread->id,
        ]);

        $comment->mode = 'thread';
        $comment->photos  = [UploadedFile::fake()->create('test.jpg', 100)];
        $comment->upfiles = [UploadedFile::fake()->create('test.pdf', 100)];

        $response= $this->actingAs($this->user)->postJson("/api/consulting/comments/$thread->id", $comment->toArray());

        $response->assertStatus(201)
                ->assertJson(['comment' => $comment->comment]);
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

        $response = $this->actingAs($this->user)->deleteJson("/api/consulting/comments/$comment->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted($comment->getTable(), [
                'id' => $comment->id,
        ]);
    }
}
