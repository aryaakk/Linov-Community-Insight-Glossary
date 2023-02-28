<?php

namespace App\Models\Article;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'art_comments';

    protected $guarded =[];

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id')
        ->selectRaw('id,name,photo')
        ->leftJoin(DB::raw("(select user_id, CONCAT('".url('/').'/'."', photo) photo from com_user_profiles) profiles"), 'com_users.id', '=', 'profiles.user_id');
    }
}
