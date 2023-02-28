<?php

namespace Tests\Feature\Glossary;

use App\Models\Article\Article;
use App\Models\Glossary\Glossary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use PDO;
use Tests\TestCase;

class GlossaryTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_all_glossary()
    {
        $gloss = Glossary::factory()->count(5)->create();
        // dd($gloss);
        $response = $this->get('api/glossary');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_glossary()
    {
        $title_gloss =  $this->faker()->sentence();
        $glossary = Article::factory()->make();
        $glossary->title_gloss = $title_gloss;
        $glossary->letter = substr($title_gloss, 0, 1);
        // dd($glossary);
        // $glossary->banner = UploadedFile::fake()->image('photo.jpg',100, 100)->size(100);
        // dd($this->user);
        $response = $this->actingAs($this->user)->postJson("/api/glossary", $glossary->toArray());

        $response->assertStatus(201);
    }

    public function test_admin_can_update_glossary()
    {
        $title_gloss =  $this->faker()->sentence();
        $glossary = Article::factory()->create();
        $id_glossary = Glossary::factory()->create()->id;
        $glossary->title = 'new_code';
        $glossary->title_gloss = $title_gloss;
        $glossary->letter = substr($title_gloss, 0, 1);
        unset($glossary->banner);

        $response = $this->actingAs($this->user)->putJson("/api/glossary/$id_glossary", $glossary->toArray());

        $response->assertStatus(200);
    }

    public function test_user_can_filter_glossary_by_category()
    {
        $title_gloss =  $this->faker()->sentence();
        $article = Article::factory()->create([
            'category' => [
                'Comben',
                'Perfomance & Development',
                'HR Administration'
            ]
        ]);
        $glossary = Glossary::factory()->create([
            'article_id' => $article->id,
            'title' => $title_gloss,
            'letter' => substr($title_gloss, 0, 1)
        ]);
        $response = $this->get('api/glossary?filter[]=Comben');
        $response->assertStatus(200);
    }

    public function test_user_can_search_glossary()
    {
        $title_gloss =  $this->faker()->sentence();
        $article = Article::factory()->create();
        $glossary = Glossary::factory()->create([
            'article_id' => $article->id,
            'title' => $title_gloss,
            'letter' => substr($title_gloss, 0, 1)
        ]);
        var_dump($article->detail);
        $response = $this->get('api/glossary?search='.$article->detail);
        // dd($response->json());
        $response->assertStatus(200);
    }
}
