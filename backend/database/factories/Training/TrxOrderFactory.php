<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxClass;
use App\Models\Training\TrxSchedule;
use App\Models\Utility\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
            'user_id'  => $this->faker->uuid(),
            'class_id' => TrxClass::factory()->create()->id,
            'schedule_id'=> TrxSchedule::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2),
            'discount' => $this->faker->randomFloat(2),
            'total_price' => $this->faker->randomFloat(2),
            'note' => $this->faker->text(100),
            'position'=> $this->faker->word(),
            'known_from'=> $this->faker->word(),
            'is_confirm_agree' => $this->faker->randomElement(['0', '1']),
            'account_name' => $this->faker->name,
            'account_bank' => $this->faker->randomNumber(5, true),
            'bank_id' => Bank::factory()->create()->id,
            'tf_upload' => $this->faker->imageUrl(100, 100),
            'status' => $this->faker->randomElement(['order', 'paid', 'canceled']),
        ];
    }
}
