<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;

class Skill extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_skills';
    protected $guarded = [];
}
