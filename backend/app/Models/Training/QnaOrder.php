<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QnaOrder extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_qna_orders';
    protected $guarded = [];
}
