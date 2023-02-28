<?php

namespace App\Models\Training;

use App\Models\Profiles\UserSocial;
use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Trainer extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'tre_trainers';
    protected $guarded = [];

    protected $casts =['skills'=>'array'];

    protected $appends = [
        'photo_url',
    ];

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::disk('oss')->temporaryUrl($this->photo, now()->addHours(1)) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function experiences()
    {
        return $this->hasMany(TrainerDetail::class,'trainer_id','id')
        ->selectRaw('tre_trainer_details.id,trainer_id,is_current,position,start_date,end_date,company,city_id,city_name,state_name,country_name')
        ->leftJoin(DB::raw("(
            select com_cities.id, com_cities.name city_name,com_states.name state_name, com_phone_codes.name country_name from com_cities 
            left join com_states on com_states.id=com_cities.state_id
            left join com_phone_codes on com_phone_codes.id=com_states.phone_code_id
        ) city"), 'city.id', '=', 'tre_trainer_details.city_id');
    }

    public function educations()
    {
        return $this->hasMany(TrainerEducation::class)
        ->selectRaw('tre_train_educations.id,trainer_id,is_current,start_date,end_date,other_title,other_university,other_major,com_title_degrees.id title_degree_id, com_title_degrees.name degree_name,com_universities.id university_id, com_universities.name university_name, com_majors.id major_id, com_majors.name major_name')
        ->leftJoin('com_title_degrees', 'com_title_degrees.id', '=', 'tre_train_educations.title_degree_id')
        ->leftJoin('com_universities', 'com_universities.id', '=', 'tre_train_educations.university_id')
        ->leftJoin('com_majors', 'com_majors.id', '=', 'tre_train_educations.major_id');
    }

    public function providers()
    {
        return $this->hasMany(TrainerProvider::class)
                 ->leftJoin('tre_providers', 'tre_providers.id', '=', 'tre_train_providers.provider_id')->selectRaw('tre_providers.id,trainer_id,name,logo');
    }

    public function socials()
    {
        return $this->hasMany(UserSocial::class, 'user_id', 'id')
                    ->leftJoin(DB::raw("(select id social_id,name,icon from com_social_medias where is_active='1') social"), 'social.social_id', 'com_user_socials.social_media_id');
    }

    public function certificates()
    {
        return $this->hasMany(TrainerCertificate::class, 'trainer_id', 'id');
    }
}
