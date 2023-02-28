<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_train_event_id'=>TrxEvent::factory()->create()->id,
            'type_class' => $this->faker->randomElement(['1', '2']),
            'seq' => $this->faker->numberBetween(1, 100),
            'last_order_date' => $this->faker->dateTimeBetween('now', '3 month')->format('Y-m-d'),
            'description' => $this->faker->text(100),
            'min_participant' => $this->faker->numberBetween(1, 10),
            'max_participant' => $this->faker->numberBetween(10, 100),
            'min_order' => $this->faker->numberBetween(1, 10),
            'max_order' => $this->faker->numberBetween(1, 20),
            'kurs_dollar' => $this->faker->randomElement(['0', '1']),
            'price' => $this->faker->randomFloat(2, 0, 1000000),
            'is_discount' => $this->faker->randomElement(['0', '1']),
            'kurs_dollar_discount' => $this->faker->randomElement(['0', '1']),
            'price_discount' => $this->faker->randomFloat(2, 0, 100000),
            'is_scheduled' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
