<?php

namespace Database\Factories\Training;

use App\Models\Profiles\Degree;
use App\Models\Profiles\Major;
use App\Models\Profiles\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerEducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title_degree_id' => Degree::factory()->create()->id,
            'university_id' => University::factory()->create()->id,
            'major_id' => Major::factory()->create()->id,
            'other_title' => $this->faker->word(1, true),
            'other_university' => $this->faker->company(),
            'other_major' => $this->faker->word(2, true),
            'start_date' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d H:i:s'),
            'end_date' =>  now()->toDateTimeString(),
        ];
    }
}
