<?php

namespace Database\Factories\Glossary;

use App\Models\Article\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlossaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'article_id' => Article::factory()->create()->id,
            'title' => $title,
            'letter' => substr($title, 0, 1),
        ];
    }
}
