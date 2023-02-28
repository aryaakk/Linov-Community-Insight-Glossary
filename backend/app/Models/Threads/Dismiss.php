<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dismiss extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_dismiss_threads';
    protected $guarded = [];
}
