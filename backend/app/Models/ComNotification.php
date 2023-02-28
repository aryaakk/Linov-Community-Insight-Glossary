<?php

namespace App\Models;

use App\Traits\Paging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class ComNotification extends DatabaseNotification
{
    use HasFactory, Paging;
    
    protected $table = 'com_notifications';
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

    /**
     * Get the comments for the blog post.
     */
    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->leftJoin(DB::raw("(select user_id, CONCAT('".url('/').'/'."', photo) photo from com_user_profiles) profiles"), 'com_users.id', '=', 'profiles.user_id');
    }
}
