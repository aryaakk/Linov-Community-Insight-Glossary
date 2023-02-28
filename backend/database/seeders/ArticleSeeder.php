<?php

namespace Database\Seeders;

use App\Models\Article\Article;
use App\Models\Article\ArticleView;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = Article::factory()->count(20)->create([
            'status'=>'published',
        ]);
    
        foreach($articles->random(6) as $article){
            ArticleView::factory(rand(1, 5))->create([
                'article_id'=>$article->id,
            ]);
        }

    }
}
