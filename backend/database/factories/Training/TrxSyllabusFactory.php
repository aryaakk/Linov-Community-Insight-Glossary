<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxSyllabusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_train_event_id' => TrxEvent::factory()->create()->id,
            'title' => $this->faker->word,
            'sub_title' => $this->faker->word,
            'seq' => $this->faker->numberBetween(1, 100),
        ];
    }
}
