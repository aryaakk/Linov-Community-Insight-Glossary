<?php

namespace App\Models\Consults;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Love extends Model
{
    use HasFactory, Uuids;

    protected $table = 'con_love_threads';
    protected $guarded = [];

    /**
     * Get the comments for the blog post.
     */

     public function scopeWithProfile($query){
        return $query->leftJoin(DB::raw('(select com_user_profiles.user_id, name, photo from com_users left join com_user_profiles on com_user_profiles.user_id=com_users.id) users'), 'con_love_threads.user_id', '=', 'users.user_id')->orderBy('con_love_threads.created_at', 'DESC');
     }
}
