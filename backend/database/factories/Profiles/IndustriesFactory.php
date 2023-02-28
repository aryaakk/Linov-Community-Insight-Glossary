<?php

namespace Database\Factories\Profiles;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndustriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->randomNumber(6),
            'name' => $this->faker->unique()->randomNumber(6),
            'is_active' => '1'
        ];
    }
}
