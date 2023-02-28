<?php

namespace Database\Factories\Threads;

use App\Models\Threads\PollDuration;
use App\Models\Threads\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id' => Thread::factory()->create()->id,
            'description' => $this->faker->sentence(5, false),
            'duration_poll_id'=> PollDuration::factory()->create()->id,
        ];
    }
}
