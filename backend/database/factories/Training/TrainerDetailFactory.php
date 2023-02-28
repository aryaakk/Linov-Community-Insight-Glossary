<?php

namespace Database\Factories\Training;

use App\Models\Profiles\City;
use App\Models\Profiles\Industries;
use App\Models\Training\Company;
use App\Models\Training\CompanyType;
use App\Models\Training\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerDetailFactory extends Factory
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
            'company' => $this->faker->companySuffix(),
            'industry_id'=> Industries::factory()->create()->id,
            'city_id'=> City::factory()->create()->id,
            'position'=> $this->faker->word(),
            'description' => $this->faker->text(200),
            'is_current' => $this->faker->randomElement(['1', '2']),
            'is_active' => $this->faker->randomElement(['1', '0']),
            'start_date'=> $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 years')->format('Y-m-d'),
            'created_by'=> $this->faker->uuid()
        ];
    }
}
