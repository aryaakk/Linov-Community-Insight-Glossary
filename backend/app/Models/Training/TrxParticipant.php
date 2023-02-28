<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxParticipant extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trx_order_participants';
    protected $guarded = [];
}
