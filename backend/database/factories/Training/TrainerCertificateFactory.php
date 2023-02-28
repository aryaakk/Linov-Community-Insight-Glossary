<?php

namespace Database\Factories\Training;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'organization' => $this->faker->word(),
            'link' => $this->faker->url(),
            'is_novalidate' => $this->faker->boolean(40)
        ];
    }
}
