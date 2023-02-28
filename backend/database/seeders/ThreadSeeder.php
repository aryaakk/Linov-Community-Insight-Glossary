<?php

namespace Database\Seeders;

use App\Models\Threads\Comment;
use App\Models\Threads\Bookmark;
use App\Models\Threads\Dismiss;
use App\Models\Threads\Love;
use App\Models\Threads\PollDetail;
use App\Models\Threads\Polling;
use App\Models\Threads\PollVoter;
use App\Models\Threads\Report;
use App\Models\Threads\Thread;
use App\Models\Threads\TypeQuestion;
use App\Models\Threads\View;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $threads = Thread::factory()->count(15)->create();
            $users   = User::pluck('id');

            foreach ($threads as $key => $thread) {
                TypeQuestion::factory()->create([
                    'thread_id' => $thread->id,
                ]);
    
                View::factory()->count(rand(1,3))->create([
                    'thread_id' => $thread->id,
                ]);

                Love::factory()->count(rand(0,3))->create([
                    'thread_id' => $thread->id,
                ]);

                Bookmark::factory()->count(rand(0,3))->create([
                    'thread_id' => $thread->id,
                ]);

                Dismiss::factory()->count(rand(0,2))->create([
                    'thread_id' => $thread->id,
                ]);

                Report::factory()->count(rand(0,1))->create([
                    'thread_id' => $thread->id,
                ]);

                Comment::factory()->count(rand(0,4))->create([
                    'thread_id' => $thread->id,
                    'user_id' => $users->random()
                ]);
                
                if($key%2==0){
                    $polling= Polling::factory()->create([
                        'thread_id' => $thread->id,
                    ]);
            
                    $details = PollDetail::factory()->count(rand(2,3))->create([
                        'thread_poll_id' => $polling->id
                    ]);

                    foreach($details as $detail){
                        PollVoter::factory()->create([
                            'detail_poll_id' => $detail->id
                        ]);
                    }
                }
            }
    
        });
    }
}
