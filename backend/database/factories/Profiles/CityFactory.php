<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
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
            'state_id'=> State::factory()->create()->id,
            'name' => $this->faker->city(),
            'is_active'=> '1'
        ];
    }
}
