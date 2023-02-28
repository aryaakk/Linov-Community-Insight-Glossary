<?php

namespace App\Models\Profiles;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_majors';
    protected $guarded = [];
}
