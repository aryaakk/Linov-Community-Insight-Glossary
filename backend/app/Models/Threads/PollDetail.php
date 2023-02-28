<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollDetail extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_thread_detail_polls';
    protected $guarded = [];
}
