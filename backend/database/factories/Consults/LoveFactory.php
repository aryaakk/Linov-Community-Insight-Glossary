<?php

namespace Database\Factories\Consults;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'consul_thread_id' => null,
            'user_id' => User::factory()->create()->id
        ];
    }
}
