<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\Social;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSocialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'social_media_id'=>Social::factory()->create()->id,
            'url' => $this->faker->url()
        ];
    }
}
