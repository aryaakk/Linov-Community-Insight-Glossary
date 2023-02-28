<?php

namespace Database\Factories\Threads;

use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->randomNumber(8, false),
            'name' => $this->faker->sentence(2, false),
            'color'=> $this->faker->hexColor(),
            'is_active'=> '1'
        ];
    }
}
