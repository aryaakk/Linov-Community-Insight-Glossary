<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollDuration extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_duration_polls';
    protected $guarded = [];
}
