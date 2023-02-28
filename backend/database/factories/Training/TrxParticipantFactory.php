<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_order_id' => TrxOrder::factory()->create()->id,
            'is_user' => $this->faker->randomElement(['0', '1']),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_code_id' => $this->faker->uuid,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
