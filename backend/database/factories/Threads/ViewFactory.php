<?php

namespace Database\Factories\Threads;

use App\Models\Threads\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id' => null,
            'user_id' => User::factory()->create()->id
        ];
    }
}
