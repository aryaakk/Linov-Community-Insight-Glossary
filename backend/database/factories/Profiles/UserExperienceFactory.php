<?php

namespace Database\Factories\Profiles;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company' => $this->faker->companySuffix(),
            'description' => $this->faker->text(200),
            'is_current' => $this->faker->randomElement(['1', '2']),
            'is_active' => '1',
            'start_date'=> $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 years')->format('Y-m-d'),
            'created_by'=> $this->faker->uuid()
        ];
    }
}
