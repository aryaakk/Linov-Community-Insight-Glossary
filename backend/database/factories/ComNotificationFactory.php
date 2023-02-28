<?php

namespace Database\Factories;

use App\Models\User;
use App\Notifications\CommentThread;
use App\Notifications\LikeThread;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid(),
            'type' => $this->faker->randomElement([CommentThread::class, LikeThread::class]),
            'notifiable_type'=>User::class,
            'notifiable_id' => User::factory()->create()->id,
            'message_title' => $this->faker->sentence(2),
            'message' => $this->faker->sentence(5),
            'icon' => $this->faker->imageUrl(36,36),
            'detail_id'=> $this->faker->uuid(),
            'path' => '/thread/'.$this->faker->uuid(),
        ];
    }
}
