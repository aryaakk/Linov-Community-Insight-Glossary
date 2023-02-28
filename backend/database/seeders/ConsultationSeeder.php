<?php

namespace Database\Seeders;

use App\Models\Consults\Comment;
use App\Models\Consults\Love;
use App\Models\Consults\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $threads = Thread::factory()->count(15)->create();
        $users   = User::pluck('id');
        foreach($threads as $thread){
            Love::factory()->count(rand(0,3))->create([
                'consul_thread_id'=>$thread->id
            ]);

            Comment::factory()->count(rand(0,3))->create([
                'consul_thread_id'=>$thread->id,
                'user_id' => $users->random()
            ]);
        }
    }
}
