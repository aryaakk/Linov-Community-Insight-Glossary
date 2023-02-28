<?php

namespace App\Models\Consults;

use App\Traits\Paging;
use App\Traits\Slugable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Thread extends Model
{
    use HasFactory, Uuids, Paging, SoftDeletes, Slugable;

    protected $table = 'con_consul_threads';
    protected $guarded = [];
    protected $appends = [
        'human_date',
    ];

    /**
     * Get humanize date
     *
     * @param  string  $value
     * @return string
     */
    public function getHumanDateAttribute($value)
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function scopeWithLoveStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN con_love_threads.love_status THEN 1
            ELSE 0
            END as love_status
        ")->leftJoin(DB::raw("(select consul_thread_id, TRUE love_status from con_love_threads WHERE user_id='".auth()->id()."') con_love_threads"), 'con_consul_threads.id', '=', 'con_love_threads.consul_thread_id');
    }

    public function scopeWithCountData($query)
    {
        return $query->selectRaw("
            COALESCE(total_love, 0) as total_love,
            COALESCE(total_comment,0) as total_comment
        ")
        ->leftjoin(DB::raw('(select count(id) total_love, consul_thread_id from con_love_threads group by consul_thread_id) love'), 'love.consul_thread_id','=','con_consul_threads.id')
        ->leftjoin(DB::raw('(select count(id) total_comment, consul_thread_id from con_comment_threads where deleted_at IS NULL group by consul_thread_id) comment'), 'comment.consul_thread_id','=','con_consul_threads.id');
    }

    public function scopeLimitDescription($query, $limit)
    {
        return $query->selectRaw("SUBSTRING(con_consul_threads.description, 1, $limit) as description");
    }

    /**
     * Get the comments for the blog post.
     */
    public function types()
    {
        return $this->hasMany(\App\Models\Threads\Type::class, 'id', 'category_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function smallloves()
    {
        return $this->hasMany(Love::class, 'id', 'consul_thread_id')->withProfile()->limit(3);
    }

    /**
     * Get the comments for the blog post.
     */
    public function images()
    {
        return $this->hasMany(Attachment::class, 'consul_thread_id', 'id')->where('type', 1)->selectRaw('id,consul_thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function files()
    {
        return $this->hasMany(Attachment::class, 'consul_thread_id', 'id')->where('type', 2)->selectRaw('id,consul_thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id')
        ->selectRaw("com_users.id, name, profiles.photo, (CASE WHEN role_id='389963c2-8abf-461a-b2fc-cdf989679cc1' OR is_consultant='1' THEN '1' ELSE '0' END) badge")
        ->leftJoin(DB::raw("(select user_id, CONCAT('".url('/').'/'."', photo) photo from com_user_profiles) profiles"), 'com_users.id', '=', 'profiles.user_id');
    }
}
