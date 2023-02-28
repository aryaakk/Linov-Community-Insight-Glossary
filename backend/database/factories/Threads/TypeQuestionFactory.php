<?php

namespace Database\Factories\Threads;

use App\Models\Threads\Thread;
use App\Models\Threads\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id' => null,
            'type_question_id' => $this->faker->randomElement(Type::pluck('id')->toArray()),
            'main_type' => '0',
        ];
    }
}
