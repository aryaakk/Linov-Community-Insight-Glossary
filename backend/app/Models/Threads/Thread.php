<?php

namespace App\Models\Threads;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Slugable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Thread extends Model
{
    use HasFactory, Uuids, Paging, SoftDeletes, Slugable, Searchable;

    protected $table = 'hom_threads';
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
                WHEN hom_love_threads.love_status THEN 1
            ELSE 0
            END as love_status
        ")->leftJoin(DB::raw("(select thread_id, TRUE love_status from hom_love_threads WHERE user_id='".auth()->id()."') hom_love_threads"), 'hom_threads.id', '=', 'hom_love_threads.thread_id');
    }

    public function scopeWithBookmarkStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN hom_save_threads.bookmark_status THEN 1
            ELSE 0
            END as bookmark_status
        ")->leftJoin(DB::raw("(select thread_id, TRUE bookmark_status from hom_save_threads WHERE user_id='".auth()->id()."') hom_save_threads"), 'hom_threads.id', '=', 'hom_save_threads.thread_id');
    }

    public function scopeWithDismisStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN hom_dismiss_threads.dismiss_status THEN 1
            ELSE 0
            END as dismiss_status
        ")->leftJoin(DB::raw("(select thread_id, TRUE dismiss_status from hom_dismiss_threads WHERE user_dismisser_id='".auth()->id()."') hom_dismiss_threads"), 'hom_threads.id', '=', 'hom_dismiss_threads.thread_id');
    }

    public function scopeWithReportStatus($query)
    {
        return $query->selectRaw("
            CASE
                WHEN hom_report_threads.report_status THEN 1
            ELSE 0
            END as report_status
        ")->leftJoin(DB::raw("(select thread_id, TRUE report_status, COUNT(thread_id) total_report from hom_report_threads group by thread_id) hom_report_threads"), 'hom_threads.id', '=', 'hom_report_threads.thread_id');
    }

    public function scopeWithCommentStatus($query, $id=null)
    {
        $id = $id ? $id : auth()->id();
        return $query->selectRaw("
            CASE
                WHEN hom_comment_threads.comment_status THEN 1
            ELSE 0
            END as comment_status
        ")->leftJoin(DB::raw("(select thread_id, TRUE comment_status from hom_comment_threads WHERE user_id='$id') hom_comment_threads"), 'hom_threads.id', '=', 'hom_comment_threads.thread_id');
    }

    public function scopeWithCountData($query)
    {
        return $query->selectRaw("
            COALESCE(total_view,0)+100 as total_view,
            COALESCE(total_love, 0) as total_love,
            COALESCE(total_comment,0) as total_comment
        ")
        ->leftjoin(DB::raw('(select count(id) total_view, thread_id from hom_detail_view_threads group by thread_id) view'), 'view.thread_id','=','hom_threads.id')
        ->leftjoin(DB::raw('(select count(id) total_love, thread_id from hom_love_threads group by thread_id) love'), 'love.thread_id','=','hom_threads.id')
        ->leftjoin(DB::raw('(select count(id) total_comment, thread_id from hom_comment_threads where deleted_at IS NULL group by thread_id) comment'), 'comment.thread_id','=','hom_threads.id');
    }

    public function scopeLimitDescription($query, $limit)
    {
        return $query->selectRaw("SUBSTRING(hom_threads.description, 1, $limit) as description");
    }

    public function scopeNotReported($query)
    {
        $query->leftJoin(DB::raw("(select thread_id, level from hom_report_threads WHERE user_reporter_id='".auth()->id()."') hom_report_threads"), 'hom_threads.id', '=', 'hom_report_threads.thread_id')->whereNull('level');
    }

    /**
     * Get the comments for the blog post.
     */
    public function types()
    {
        return $this->hasMany(TypeQuestion::class)->withQuestion();
    }

    /**
     * Get the comments for the blog post.
     */
    public function smallloves()
    {
        return $this->hasMany(Love::class)->withProfile()->limit(3);
    }

    /**
     * Get the comments for the blog post.
     */
    public function loves()
    {
        return $this->hasMany(Love::class)->withProfile();
    }

    /**
     * Get the comments for the blog post.
     */
    public function image()
    {
        return $this->hasOne(Attachment::class)->where('type', 1)->selectRaw('id,thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function images()
    {
        return $this->hasMany(Attachment::class)->where('type', 1)->selectRaw('id,thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function files()
    {
        return $this->hasMany(Attachment::class)->where('type', 2)->selectRaw('id,thread_id,file,file_type,size');
    }

    /**
     * Get the comments for the blog post.
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function pollings()
    {
        return $this->hasOne(Polling::class)
        ->selectRaw('id,thread_id,duration_poll_id duration_id,description')
        ->withSum('details as total_vote', 'counter')
        ->withDuration()
        ->withPollStatus();
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
