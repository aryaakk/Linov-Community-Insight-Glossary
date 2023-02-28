<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_detail_view_threads';
    protected $guarded = [];
}
