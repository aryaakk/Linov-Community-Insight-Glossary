<?php

namespace Database\Seeders;

use App\Models\Utility\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            
            Bank::factory()->count(5)->create();
    
        });
    }
}
