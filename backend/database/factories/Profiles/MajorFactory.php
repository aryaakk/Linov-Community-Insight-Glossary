<?php

namespace Database\Factories\Profiles;

use Illuminate\Database\Eloquent\Factories\Factory;

class MajorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->randomNumber(6),
            'name' => $this->faker->randomLetter(),
            'is_active' => '1',
            'created_by' => $this->faker->uuid,
        ];
    }
}
