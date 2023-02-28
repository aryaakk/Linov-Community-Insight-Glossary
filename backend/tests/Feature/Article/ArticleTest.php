<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleView;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_premium_article()
    {
        $article = Article::factory()->count(4)->create([
            'is_ads'=>'1',
            'status'=>'published'
        ]);

        $response = $this->get('/api/article/premium');

        $response->assertStatus(200)
                ->assertJsonCount(4);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_popular_article()
    {
        $articles = Article::factory()->count(5)->create([
            'status'=>'published',
            'is_ads'=>'0'
        ]);
    
        foreach($articles->take(4) as $article){
            ArticleView::factory(rand(1, 5))->create([
                'article_id'=>$article->id,
            ]);
        }

        $response = $this->get('/api/article/popular');

        $response->assertStatus(200)
                ->assertJsonCount(4);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_article()
    {
        Article::factory()->count(5)->create([
            'status'=> 'published',
            'is_ads'=>'0'
        ]);
        
        $response = $this->get('/api/article');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_one_article()
    {
        $article = Article::factory()->create();

        $response = $this->get("/api/article/$article->slug_id/show");
  
        $response->assertStatus(200)
                ->assertJsonPath('data.slug_id', $article->slug_id);
    }

    public function test_user_can_get_one_premium_article()
    {
        $article = Article::factory()->create(['trx_code'=>'ART-PRE']);

        $response= $this->get("/api/article/$article->slug_id/show");

        $response->assertStatus(200)
                ->assertJsonPath('data.slug_id', $article->slug_id)
                ->assertJsonPath('data.trx_code', $article->trx_code);
    }

    public function test_user_can_preview_one_article()
    {
        $article = Article::factory()->make();
        unset($article->banner);
        $data = $this->actingAs($this->user)->postJson("/api/article/preview", $article->toArray());

        $response = $this->get("/api/article/".$data->json()['previewid']."/show?preview=true");

        $response->assertStatus(200)
                ->assertJsonPath('data.title', $article->title);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_article(){
        $article = Article::factory()->make();
        $article->banner = UploadedFile::fake()->image('photo.jpg',100, 100)->size(100);

        $response= $this->actingAs($this->user)->postJson("/api/article", $article->toArray());
   
        $response->assertStatus(201)
                ->assertJson([
                    'title' => $article->title, 'open_discuss'=>$article->open_discuss
                ]);

        $this->assertDatabaseHas($article->getTable(), [
            'title' => $article->title,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_article(){
        $article = Article::factory()->create();
        $article->title = 'new_code';
        unset($article->banner);

        $response= $this->actingAs($this->user)->putJson("/api/article/$article->id", $article->toArray());

        $response->assertStatus(200)
                ->assertJson(['title' => $article->title]);

        $this->assertDatabaseHas($article->getTable(), [
            'title' => $article->title,
        ]);
    }

    public function test_user_can_save_preview_article(){
        $article = Article::factory()->make();
        $article->banner = UploadedFile::fake()->image('photo.jpg',100, 100)->size(100);

        $response= $this->actingAs($this->user)->postJson("/api/article/preview", $article->toArray());

        $response->assertStatus(200)
                ->assertJson(['status' => 'OK']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_article(){
        $article = Article::factory()->create();

        $response = $this->actingAs($this->user)->postJson("/api/article/deletes",[$article->id]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing($article->getTable(), [
                'id' => $article->id,
        ]);
    }

}
