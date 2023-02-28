<?php

namespace Database\Factories\Article;

use App\Models\Article\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => Article::factory()->create()->id,
            'comment_article_id'=>null,
            'user_id' => User::factory()->create()->id,
            'comment' => $this->faker->word(5),
            'status' => $this->faker->randomElement(['publish','waiting','spamed','reject']),
        ];
    }
}
