<?php

namespace App\Models\Training;

use App\Traits\Paging;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Provider extends Model
{
    use HasFactory, Uuids, Paging;

    protected $table = 'tre_providers';
    protected $guarded = [];

    protected $appends = [
        'logo_url',
        'logo2_url'
    ];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::disk('oss')->temporaryUrl($this->logo, now()->addHours(1)) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function getLogo2UrlAttribute()
    {
        return $this->logo_2 ? Storage::disk('oss')->temporaryUrl($this->logo_2, now()->addHours(1)): 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function state(){
        return $this->belongsTo('App\Models\Profiles\State', 'state_id', 'id')
                    ->leftJoin(DB::raw("(select id country_id, name country_name from com_phone_codes) countries"), 'countries.country_id', '=', 'com_states.phone_code_id');
    }
}
