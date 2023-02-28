<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BaseSeeder::class,
            UtilitySeeder::class,
            ArticleSeeder::class,
            ThreadSeeder::class,
            ConsultationSeeder::class,
            TrainEventSeeder::class
        ]);
    }
}
