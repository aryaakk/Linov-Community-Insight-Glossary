<?php

namespace Database\Factories\Training;

use App\Models\Profiles\State;
use App\Models\Training\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
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
            'company_id' => Company::factory()->create()->id,
            'name' => $this->faker->companySuffix(),
            'state_id'=> State::factory()->create()->id,
            'tagline' => $this->faker->sentence,
            'summary' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(300,300),
            'logo_2'=> $this->faker->imageUrl(600,600),
            'is_list_prior'=> $this->faker->randomElement(['1', '2']),
            'num' => $this->faker->numberBetween(1, 4),
            'is_active' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
