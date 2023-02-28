<?php

namespace Database\Factories\Profiles;

use App\Models\Profiles\TrxConsultant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trx_consultant_id' => TrxConsultant::factory()->create()->id,
            'category' => $this->faker->randomElement(['1','2','3','4']),
            'file_attach'=> $this->faker->imageUrl(300, 300),
            'file_type' => $this->faker->fileExtension(),
            'is_success'=> '1'
        ];
    }
}
