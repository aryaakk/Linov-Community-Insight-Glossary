<?php

namespace Database\Factories\Consults;

use App\Models\Consults\Category;
use App\Models\Threads\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6, false),
            'user_id' => User::factory()->create()->id,
            'description' => $this->faker->sentence(20, false),
            'category_id' => Type::first()->id,
            'user_consul_id'=> User::factory()->create([
                'is_consultant'=>'1', 
                'category_consultant_id' => Category::factory()->create()->id
            ])->id,
            'is_active' => '1'
        ];
    }
}
