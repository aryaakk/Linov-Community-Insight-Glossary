<?php

namespace App\Models\Profiles;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class State extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_states';
    protected $guarded = [];

    public function scopeWithCountry($query)
    {
        $query->leftJoin(DB::raw("(select id country_id, name country_name from com_phone_codes) country"), 'country.country_id', 'com_states.phone_code_id');
    }
}
