<?php

namespace Database\Factories\Training;

use App\Models\Threads\Type;
use App\Models\Training\Provider;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['training', 'event']);
        $title = $this->faker->sentence;
        return [
            'trx_code' => $type == 'training' ? 'TR-TN' : 'TR-EN',
            'slug_id' => Str::slug($title).'-'.rand(1, 100),
            'trx_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'type' => $type,
            'provider_id'=> Provider::factory()->create()->id,
            'title' => $this->faker->sentence,
            'sustainable'=> $this->faker->words(5, true),
            'summary' => $this->faker->paragraphs(5, true),
            'location' => $this->faker->words(1, true),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'type_question_id'=>$this->faker->randomElement(Type::pluck('id')->toArray()),
            'banner'=> $this->faker->imageUrl(640, 480),
            'banner_card'=> $this->faker->imageUrl(740, 380),
            'banner_slide'=> $this->faker->imageUrl(1280, 640),
            'is_ads' => $this->faker->randomElement(['0', '1']),
            'num_ads'=> $this->faker->numberBetween(1, 5),
            // 'ads_start_date'=> $this->faker->dateBetween('-1 month', 'now')->format('Y-m-d'),
            // 'ads_end_date'=> $this->faker->dateBetween('+1 month', 'now')->format('Y-m-d'),
            'published_date'=> $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['published', 'banned', 'canceled']),
        ];
    }
}
