<?php

namespace Database\Factories\Consults;

use App\Models\Consults\Thread;
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
            'consul_thread_id'=> Thread::factory()->create()->id,
            'user_id' => User::first()->id,
            'comment' => $this->faker->sentence(6),
        ];
    }
}
