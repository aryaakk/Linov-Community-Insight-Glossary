<?php

namespace Database\Factories\Threads;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['0', '1']),
            'file' => $this->faker->image(null, 480, 480),
            'file_type' => $this->faker->fileExtension(),
            'size' => $this->faker->randomNumber(4, false),
        ];
    }
}
