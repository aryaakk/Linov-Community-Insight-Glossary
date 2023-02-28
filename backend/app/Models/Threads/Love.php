<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Love extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_love_threads';
    protected $guarded = [];

    /**
     * Get the comments for the blog post.
     */

     public function scopeWithProfile($query){
        return $query->leftJoin(DB::raw("(select com_user_profiles.user_id, name, CONCAT('".url('/').'/'."', photo) photo from com_users left join com_user_profiles on com_user_profiles.user_id=com_users.id) users"), 'hom_love_threads.user_id', '=', 'users.user_id')->orderBy('hom_love_threads.created_at', 'DESC');
     }
}
