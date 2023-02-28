<?php

namespace Database\Factories\Training;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyTypeFactory extends Factory
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
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->text(200),
            'is_active'=> $this->faker->randomElement(['0', '1']),
        ];
    }
}
