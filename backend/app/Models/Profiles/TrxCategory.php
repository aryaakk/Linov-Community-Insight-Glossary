<?php

namespace App\Models\Profiles;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxCategory extends Model
{
    use HasFactory, Uuids;

    protected $table = 'con_trx_category_consultants';
    protected $guarded = [];
}
