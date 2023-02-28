<?php

namespace Database\Factories\Profiles;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'ip_address'=> $this->faker->ipv4,
            'user_agent'=> $this->faker->userAgent(),
            'is_allowed'=> '1'
        ];
    }
}
