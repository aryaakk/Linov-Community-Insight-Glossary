<?php

namespace Database\Factories\Threads;

use App\Models\Threads\Thread;
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
            'thread_id'=> Thread::factory()->create()->id,
            'user_id' => User::first()->id,
            'comment' => $this->faker->sentence(6),
        ];
    }
}
