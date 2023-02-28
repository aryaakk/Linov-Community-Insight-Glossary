<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\Social;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> $this->faker->sentence(1),
            'icon'=> $this->faker->imageUrl(36,36),
            'url' => $this->faker->url(),
            'is_active'=> '1'
        ];
    }
}
