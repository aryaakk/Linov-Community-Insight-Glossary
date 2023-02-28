<?php

namespace Database\Factories\Article;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
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
            'trx_code'=> $this->faker->randomElement(['ART-INT', 'ART-PRE']),
            'user_id' => User::factory()->create()->id,
            'title' => $title,
            'trx_date'=> $this->faker->dateTimeBetween('-1 years', 'now'),
            'detail' => $this->faker->sentence(100),
            'is_ads' => $this->faker->randomElement(['0', '1']),
            'open_discuss'=> $this->faker->randomElement(['0', '1']),
            // 'banner' => $this->faker->imageUrl(),
            'published_date' => $this->faker->dateTimeBetween('-1 years', 'now -10 days')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['drafted', 'published']),
            'category'=> [$this->faker->randomElement(['HR Administration', 'Comben'])]
        ];
    }
}
