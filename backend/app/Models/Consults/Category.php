<?php

namespace App\Models\Consults;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'com_category_consultants';
    protected $guarded = [];
}
