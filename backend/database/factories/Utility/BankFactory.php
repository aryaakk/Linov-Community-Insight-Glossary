<?php

namespace Database\Factories\Utility;

use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
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
            'icon' => $this->faker->imageUrl(100, 100),
            'bank_name' => $this->faker->unique()->companySuffix(),
            'is_active' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
