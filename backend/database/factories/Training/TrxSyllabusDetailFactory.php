<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxSyllabus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxSyllabusDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'syllabus_id'=>TrxSyllabus::factory()->create()->id,
            'icon' => $this->faker->randomLetter,
            'head' => $this->faker->words(5, true),
            'description' => $this->faker->paragraph,
            'seq' => $this->faker->numberBetween(1, 100),
        ];
    }
}
