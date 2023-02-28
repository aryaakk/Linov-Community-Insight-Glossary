<?php

namespace App\Models\Profiles;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrxConsultant extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'con_trx_consultants';
    protected $guarded = [];

    public function scopeWithProfile($query)
    {
        $query
        ->leftJoin('com_user_profiles', 'com_user_profiles.user_id', 'con_trx_consultants.user_id')
        ->leftJoin(DB::raw("(select com_cities.id city_id, com_cities.name city_name, com_states.id state_id, com_states.name state_name, phone_code_id country_id, com_phone_codes.name country_name from com_cities 
        left join com_states on com_states.id=com_cities.state_id
        left join com_phone_codes on com_phone_codes.id=com_states.phone_code_id) region"), 'region.city_id', 'com_user_profiles.city_id');
    }

    public function categories()
    {
        return $this->hasMany(TrxCategory::class, 'trx_consultant_id', 'id')
                    ->leftJoin('com_category_consultants', 'com_category_consultants.id', 'con_trx_category_consultants.category_consultant_id')
                    ->selectRaw('com_category_consultants.id,trx_consultant_id,name');
    }

    public function experiences()
    {
        return $this->hasMany(UserExperience::class, 'user_id', 'user_id')
                    ->selectRaw('user_id,company,position,description,is_current,start_date,end_date');
    }

    public function educations()
    {
        return $this->hasMany(UserEducation::class, 'user_id', 'user_id')
                    ->selectRaw('com_user_educations.id,user_id,is_current,start_date,end_date,other_title,other_university,other_major,com_title_degrees.id title_degree_id, com_title_degrees.name degree_name,com_universities.id university_id, com_universities.name university_name, com_majors.id major_id, com_majors.name major_name')
                    ->leftJoin('com_title_degrees', 'com_title_degrees.id', '=', 'com_user_educations.title_degree_id')
                    ->leftJoin('com_universities', 'com_universities.id', '=', 'com_user_educations.university_id')
                    ->leftJoin('com_majors', 'com_majors.id', '=', 'com_user_educations.major_id');
    }

    /**
     * Get the experiences for the blog post.
     */
    public function certificates()
    {
        return $this->hasMany(UserCertificate::class, 'user_id', 'user_id')
                    ->selectRaw('id,user_id,title,organization,link,is_novalidate');
    }

    public function attachments(){
        return $this->hasMany(TrxAttachment::class, 'trx_consultant_id', 'id')
                    ->selectRaw('id,trx_consultant_id,category,file_attach,file_type');
    }
}
