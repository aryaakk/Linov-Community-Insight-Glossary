<?php

namespace Database\Factories\Consults;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->randomNumber(6, false),
            'name' => $this->faker->sentence(2),
            'is_active'=> '1',
        ];
    }
}
