<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\City;
use App\Models\Profiles\Industries;
use App\Models\Profiles\PhoneCode;
use App\Models\Profiles\Position;
use App\Models\Profiles\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
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
            'phone_code_id' => PhoneCode::factory()->create()->id,
            'phone' => $this->faker->phoneNumber(),
            'company_name' => $this->faker->company(),
            'industry_id' => Industries::factory()->create()->id,
            'position_id' => Position::factory()->create()->id,
            'photo' => $this->faker->imageUrl(400, 400),
            'city_id' => City::factory()->create()->id,
            'birthplace'=> $this->faker->city(),
            'birthdate' => $this->faker->dateTimeBetween('- 30 year', 'now -15 year')->format('Y-m-d'),
            'skills'  => Skill::factory()->count(1)->create()->pluck('name'),
            'about_me' => $this->faker->paragraph(),
            'postal_code' => $this->faker->postcode(),
        ];
    }
}
