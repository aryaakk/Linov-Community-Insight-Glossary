<?php

namespace Database\Seeders;

use App\Models\Article\Article;
use App\Models\Glossary\Glossary;
use Illuminate\Database\Seeder;

class GlossarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = Article::factory()->count(20)->create([
            'status' => 'published',
        ]);
        foreach ($articles->random(6) as $article) {
            Glossary::factory()->create([
                'article_id' => $article->id
            ]);
        }
    }
}
