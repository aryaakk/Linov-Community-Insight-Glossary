<?php

namespace App\Models\Profiles;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_cities';
    protected $guarded = [];

    public function scopeWithState($query)
    {
        $query->leftJoin(DB::raw("(select com_states.id state_id, CONCAT(com_states.name,', ',com_phone_codes.name) state_name, phone_code_id from com_states left join com_phone_codes on com_phone_codes.id=com_states.phone_code_id) state"), 'state.state_id', 'com_cities.state_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id')->where('is_active', '1');
    }

    /**
     * Get the comments for the blog post.
     */
    public function country()
    {
        return $this->hasOneThrough(PhoneCode::class,State::class,'id', 'id', 'state_id', 'phone_code_id')->where('com_phone_codes.is_active', '1');
    }
}
