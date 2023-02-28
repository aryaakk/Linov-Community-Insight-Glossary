<?php

namespace Database\Factories\Threads;

use App\Models\Threads\Polling;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_poll_id' => Polling::factory()->create()->id,
            'poll_name' => $this->faker->sentence(3),
            'counter' => $this->faker->randomNumber(2, false)
        ];
    }
}
