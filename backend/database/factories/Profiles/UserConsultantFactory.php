<?php

namespace Database\Factories\Profiles;

use App\Models\Consults\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserConsultantFactory extends Factory
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
            'category_consultant_id'=>Category::factory()->create()->id,
            'is_active' => $this->faker->randomElement(['0','1'])
        ];
    }
}
