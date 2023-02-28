<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Polling extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_thread_polls';
    protected $guarded = [];

    public function scopeWithDuration($query)
    {
        $query->selectRaw('name duration_name, (created_at + INTERVAL convert_day DAY) end_date')->leftJoin(DB::raw('(select id as duration_id, name, convert_day from hom_duration_polls) hom_duration_polls'), 'hom_thread_polls.duration_poll_id', '=', 'hom_duration_polls.duration_id'); 
    }

    public function scopeWithPollStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN poll_status THEN 1
            ELSE 0
            END as poll_status
        ")->leftJoin(DB::raw("
        (select thread_poll_id, TRUE poll_status from hom_thread_detail_polls left join hom_thread_detail_voters 
        on hom_thread_detail_voters.detail_poll_id=hom_thread_detail_polls.id 
        WHERE user_id='".auth()->id()."') detail_polls"), 'hom_thread_polls.id', '=', 'detail_polls.thread_poll_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function details()
    {
        return $this->hasMany(PollDetail::class, 'thread_poll_id', 'id');
    }
}
