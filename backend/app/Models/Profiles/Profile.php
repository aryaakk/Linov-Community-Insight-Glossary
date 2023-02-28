<?php

namespace App\Models\Profiles;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use HasFactory, Uuids;

    protected $table = 'com_user_profiles';
    protected $guarded = [];
    protected $appends = [
        'photo_url',
    ];
    protected $casts =['skills'=>'array'];

    public function getPhotoUrlAttribute()
    {
        return asset($this->photo);
    }

    /**
     * Get the comments for the blog post.
     */
    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id')->where('is_active', '1');
    }

    /**
     * Get the comments for the blog post.
     */
    public function industry()
    {
        return $this->hasOne(Industries::class, 'id', 'industry_id')->where('is_active', '1');
    }

    /**
     * Get the comments for the blog post.
     */
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id')->where('com_cities.is_active', '1');
    }
}
