<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory, Uuids, SoftDeletes;
    
    protected $table = 'hom_comment_threads';
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

    public function scopeWithCountData($query)
    {
        return $query->selectRaw("
            COALESCE(total_love, 0) as total_love,
            COALESCE(total_reply,0) as total_reply
        ")
        ->leftjoin(DB::raw('(select count(id) total_love, comment_thread_id from hom_love_threads group by comment_thread_id) love'), 'love.comment_thread_id','=','hom_comment_threads.id')
        ->leftjoin(DB::raw('(select count(id) total_reply, comment_thread_id from hom_comment_threads where deleted_at IS NULL group by comment_thread_id) comment'), 'comment.comment_thread_id','=','hom_comment_threads.id');
    }


    public function scopeWithLoveStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN hom_love_threads.love_status THEN 1
            ELSE 0
            END as love_status
        ")->leftJoin(DB::raw("(select comment_thread_id, TRUE love_status from hom_love_threads WHERE user_id='".auth()->id()."') hom_love_threads"), 'hom_comment_threads.id', '=', 'hom_love_threads.comment_thread_id');
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

    public function subcomment()
    {
        return $this->hasMany(Comment::class, 'comment_thread_id', 'id')
                    ->selectRaw('id,thread_id,hom_comment_threads.comment_thread_id,comment,user_id,created_at')
                    ->withCountData()
                    ->withLoveStatus()
                    ->with('images', 'files')
                    ->orderBy('created_at', 'ASC');
    }

        /**
     * Get the comments for the blog post.
     */
    public function images()
    {
        return $this->hasMany(Attachment::class,'comment_thread_id', 'id')->where('type', 1)->selectRaw('id,comment_thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function files()
    {
        return $this->hasMany(Attachment::class,'comment_thread_id', 'id')->where('type', 2)->selectRaw('id,comment_thread_id,file,file_type,size');
    }
}
