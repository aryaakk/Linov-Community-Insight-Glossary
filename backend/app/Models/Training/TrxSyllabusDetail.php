<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxSyllabusDetail extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trx_syllabus_details';
    protected $guarded = [];
}
