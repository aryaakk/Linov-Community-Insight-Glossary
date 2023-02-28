<?php

namespace App\Models\Profiles;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_social_medias';
    protected $guarded = [];

    /**
     * Get the comments for the blog post.
     */
    public function data()
    {
        return $this->hasOne(UserSocial::class, 'social_media_id', 'id')->where('user_id', auth()->id());
    }
}
