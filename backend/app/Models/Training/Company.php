<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Uuids;

    protected $table = 'com_companies';
    protected $guarded = [];
}
