<?php

namespace Database\Factories\Threads;

use App\Models\Threads\PollDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollVoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail_poll_id' => PollDetail::factory()->create()->id,
            'user_id' => User::factory()->create()->id
        ];
    }
}
