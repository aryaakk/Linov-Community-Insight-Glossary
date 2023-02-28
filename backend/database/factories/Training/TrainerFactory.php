<?php

namespace Database\Factories\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
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
            'name' => $this->faker->name,
            'description' => $this->faker->text(200),
            'is_active' => $this->faker->boolean,
            'photo' => $this->faker->imageUrl(200, 200),
            'is_list_prior'=> $this->faker->boolean,
            'num'=> $this->faker->numberBetween(1, 4),
            'is_active'=> $this->faker->randomElement(['0', '1']),
            'created_by'=> User::first()->id,
        ];
    }
}
