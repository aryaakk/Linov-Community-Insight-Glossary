<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\PhoneCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
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
            'phone_code_id'=> PhoneCode::factory()->create()->id,
            'name' => $this->faker->city(),
            'is_active'=>'1'
        ];
    }
}
