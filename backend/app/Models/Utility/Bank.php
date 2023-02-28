<?php

namespace App\Models\Utility;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'utl_banks';
    protected $guarded = [];
}
