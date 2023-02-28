<?php

namespace Database\Factories\Profiles;

use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
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
            'name' => $this->faker->company(),
            'is_active' => '1',
            'created_by' => $this->faker->uuid,
        ];
    }
}
