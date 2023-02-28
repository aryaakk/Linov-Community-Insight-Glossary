<?php

namespace Database\Factories\Threads;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6, false),
            'user_id' => User::factory()->create()->id,
            'description' => $this->faker->sentence(20, false),
            'is_active' => '1'
        ];
    }
}
