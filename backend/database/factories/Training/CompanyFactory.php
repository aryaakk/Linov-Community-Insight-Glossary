<?php

namespace Database\Factories\Training;

use App\Models\Profiles\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
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
            'model'=> $this->faker->randomElement(['1', '2']),
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'description'=> $this->faker->text(200),
            'city_id' => City::first()->id ?? null,
            'is_active'=> $this->faker->randomElement(['0', '1']),
        ];
    }
}
