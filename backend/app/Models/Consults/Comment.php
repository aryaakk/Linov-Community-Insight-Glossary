<?php

namespace App\Models\Consults;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $table = 'con_comment_threads';
    protected $guarded = [];

    protected $appends = [
        'human_date',
    ];

    public function scopeLimitComment($query, $limit)
    {
        return $query->selectRaw("SUBSTRING(con_comment_threads.comment, 1, $limit) as comment");
    }

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
            COALESCE(total_reply,0) as total_reply
        ")
        ->leftjoin(DB::raw('(select count(id) total_reply, con_comment_thread_id from con_comment_threads where deleted_at IS NULL group by con_comment_thread_id) comment'), 'comment.con_comment_thread_id','=','con_comment_threads.id');
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
        return $this->hasMany(Comment::class, 'con_comment_thread_id', 'id')
                    ->selectRaw('id,consul_thread_id,con_comment_threads.con_comment_thread_id,comment,user_id,created_at')
                    ->withCountData()
                    ->with('images', 'files')
                    ->orderBy('created_at', 'ASC');
    }

    /**
     * Get the comments for the blog post.
     */
    public function images()
    {
        return $this->hasMany(Attachment::class,'con_comment_thread_id', 'id')->where('type', 1)->selectRaw('id,con_comment_thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function files()
    {
        return $this->hasMany(Attachment::class,'con_comment_thread_id', 'id')->where('type', 2)->selectRaw('id,con_comment_thread_id,file,file_type,size');
    }
}
