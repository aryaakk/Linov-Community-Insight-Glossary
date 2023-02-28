<?php

namespace Database\Factories\Profiles;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=> User::factory()->create()->id,
            'is_email'=> $this->faker->randomElement(['0', '1']),
            'is_newsletter'=> $this->faker->randomElement(['0', '1']),
        ];
    }
}
