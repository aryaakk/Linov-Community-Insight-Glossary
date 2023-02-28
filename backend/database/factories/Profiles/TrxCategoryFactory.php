<?php

namespace Database\Factories\Profiles;

use App\Models\Consults\Category;
use App\Models\Profiles\TrxConsultant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrxCategoryFactory extends Factory
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
            'category_consultant_id' => Category::factory()->create()->id,
        ];
    }
}
