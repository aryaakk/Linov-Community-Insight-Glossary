<?php

namespace Database\Factories\Threads;

use Illuminate\Database\Eloquent\Factories\Factory;

class PollDurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'convert_day' => $this->faker->randomNumber(2),
            'is_active' => '1'
        ];
    }
}
