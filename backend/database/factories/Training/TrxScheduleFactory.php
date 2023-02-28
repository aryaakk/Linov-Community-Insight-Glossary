<?php

namespace Database\Factories\Training;

use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $class = TrxClass::factory()->create();
        return [
            'trx_train_event_id' => TrxEvent::factory()->create()->id,
            'class_public_id' => $class->type_class=='1' ? $class->id : null,
            'class_inhouse_id' => $class->type_class=='2' ? $class->id : null,
            'date' => now()->addDay(1)->toDateString(),
            'end_date' => $this->faker->dateTimeBetween('now + 1 day', '+10 days')->format('Y-m-d'),
            'is_same_hour' => '0',
            'schedule_id' => null,
            'start_hour' => $this->faker->time('H:i:s'),
            'end_hour' => $this->faker->time('H:i:s'),
            'is_active' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
