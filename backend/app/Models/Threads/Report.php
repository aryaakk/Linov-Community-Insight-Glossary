<?php

namespace App\Models\Threads;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'hom_report_threads';
    protected $guarded = [];
}
