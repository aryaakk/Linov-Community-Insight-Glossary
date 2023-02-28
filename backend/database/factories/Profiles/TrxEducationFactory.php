<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\Degree;
use App\Models\Profiles\Major;
use App\Models\Profiles\TrxConsultant;
use App\Models\Profiles\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxEducationFactory extends Factory
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
            'title_degree_id' => Degree::factory()->create()->id,
            'other_title' => $this->faker->sentence(2),
            'university_id' => University::factory()->create()->id,
            'other_university'=> $this->faker->sentence(2),
            'major_id'=> Major::factory()->create()->id,
            'other_major'=> $this->faker->sentence(2),
            'is_current' => $this->faker->randomElement(['0','1']),
            'start_date' => $this->faker->dateTimeBetween('-3 year', 'now -2 year')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('-2 year', 'now')->format('Y-m-d'),
        ];
    }
}
