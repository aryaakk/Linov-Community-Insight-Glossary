<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\TrxConsultant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_consultant_id' => TrxConsultant::factory()->create()->id,
            'name' => $this->faker->sentence(2),
            'position'=> $this->faker->sentence(1),
            'is_current' => $this->faker->randomElement(['0','1']),
            'period_start' => $this->faker->dateTimeBetween('-3 year', 'now -2 year')->format('Y-m-d'),
            'period_end' => $this->faker->dateTimeBetween('-2 year', 'now')->format('Y-m-d'),
            'job_desc' => $this->faker->sentence(6)
        ];
    }
}
