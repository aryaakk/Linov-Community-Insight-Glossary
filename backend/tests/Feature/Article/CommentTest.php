<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{    
    use DatabaseTransactions;

    protected $article;

    public function setUp(): void
    {
        parent::setUp();
        $this->article = Article::factory()->create();
    }

    public function test_user_can_get_comment_by_id()
    {
        Comment::factory()->count(4)->create([
            'article_id' => $this->article->id,
            'status' => 'approved',
        ]);

        $response = $this->get("/api/article/{$this->article->slug_id}/show");

        $response->assertStatus(200)
                ->assertJsonCount(4, 'data.comments');
    }

    public function test_user_can_not_get_comment_by_id()
    {
        Comment::factory()->count(1)->create([
            'article_id' => $this->article->id,
            'status' => 'reject',
        ]);

        $response = $this->get("/api/article/{$this->article->slug_id}/show");

        $response->assertStatus(200)
                ->assertJsonCount(0, 'data.comments');
    }

    public function test_user_can_post_comment()
    {
        $comment = Comment::factory()
                    ->make(['article_id'=> $this->article->id])
                    ->only('comment','article_id');

        $response = $this->actingAs($this->user)->postJson("/api/article/comment",$comment);

        $response->assertStatus(201)
                 ->assertJson([
                    'comment'=>$comment['comment']
                 ]);
    }

    public function test_admin_can_get_artikel_comments()
    {
        Comment::factory()->count(10)->create();

        $response = $this->actingAs($this->user)->getJson('/api/article/comment');

        $response->assertStatus(200)
                ->assertJson(['total'=>10])
                ->assertJsonCount(10, 'data');
    }

    public function test_admin_can_approve_comment()
    {
        $comments = Comment::factory()->count(2)->create([
            'article_id' => $this->article->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/article/comment/approved",[
            'ids' => $comments->pluck('id')->toArray()
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function test_admin_can_reject_comment()
    {
        $comments = Comment::factory()->count(2)->create([
            'article_id' => $this->article->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/article/comment/reject",[
            'ids' => $comments->pluck('id')->toArray()
        ]);

        $response->assertStatus(200)
                ->assertJsonCount(2);
    }

    public function test_user_can_delete_article_comment()
    {
        $comments = Comment::factory()->count(2)->create([
            'article_id' => $this->article->id
        ]);
        $response = $this->actingAs($this->user)->postJson("/api/article/comment/delete",[
            'ids'=>$comments->pluck('id')->toArray()
        ]);

        $response->assertStatus(200)
                ->assertJsonCount(2);

        $this->assertDatabaseMissing($comments->first()->getTable(), [
                'id' => $comments->first()->id,
        ]);
    }
}
