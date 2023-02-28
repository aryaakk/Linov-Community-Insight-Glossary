<?php

namespace App\Models\Profiles;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    use HasFactory, Uuids;

    protected $table = 'com_user_educations';
    protected $guarded = [];

    public function university_id(){
        return $this->hasOne(University::class, 'university_id','id');
    }

    public function major_id(){
        return $this->hasOne(University::class, 'major_id','id');
    }

    public function title_degree_id(){
        return $this->hasOne(University::class, 'title_degree_id','id');
    }
}
