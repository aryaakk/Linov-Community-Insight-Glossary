<?php

namespace Database\Factories\Training;

use App\Models\Training\Trainer;
use App\Models\Training\TrxEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxTrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_train_event_id' => TrxEvent::factory()->create()->id,
            'trainer_id' => Trainer::factory()->create()->id,
        ];
    }
}
