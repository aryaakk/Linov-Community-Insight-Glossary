<?php

namespace Database\Factories\Profiles;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxConsultantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_code' => 'trx-consult',
            'trx_date' => $this->faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d H:i:s'),
            'user_id'  => User::factory()->create()->id,
            'reason'   => $this->faker->sentence(4),
            'is_cv'    => $this->faker->randomElement(['0', '1']),
            'status'   => $this->faker->randomElement(['waiting', 'approved', 'rejected']),
            'is_active'=> '1'
        ];
    }
}
