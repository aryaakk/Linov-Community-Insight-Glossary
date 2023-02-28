<?php

namespace Database\Factories\Training;

use App\Models\Training\Provider;
use App\Models\Training\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trainer_id' => Trainer::factory()->create()->id,
            'provider_id' => Provider::factory()->create()->id,
            'is_active' => $this->faker->randomElement(['0', '1']),
            'created_by' => $this->faker->uuid,
        ];
    }
}
