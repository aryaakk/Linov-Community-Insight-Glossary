<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxSchedule extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trx_schedules';
    protected $guarded = [];

    public function classPublic()
    {
        return $this->belongsTo(TrxClass::class, 'class_public_id')
                    ->selectRaw('id,type_class,max_participant,price,is_discount,price_discount');
    }

    public function classInhouse()
    {
        return $this->belongsTo(TrxClass::class, 'class_inhouse_id')
                    ->selectRaw('id,type_class,max_participant,price,is_discount,price_discount');
    }

    public function event()
    {
        return $this->belongsTo(TrxEvent::class, 'trx_train_event_id');
    }
}
