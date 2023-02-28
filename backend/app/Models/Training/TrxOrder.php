<?php

namespace App\Models\Training;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TrxOrder extends Model
{
    use HasFactory, Uuids, Paging, Searchable;

    protected $table = 'tre_trx_orders';
    protected $guarded = [];
    protected $appends = ['tf_upload_url'];

    public function getTfUploadUrlAttribute($value){
        return $this->tf_upload ? Storage::disk('oss')->temporaryUrl($this->tf_upload, now()->addMinutes(5)) : '';
    }

    public function scopeWithEvent($query){
        return $query->selectRaw('tre_trx_orders.id,class_id,quantity,tre_trx_orders.price,tre_trx_orders.discount,total_price,tre_trx_orders.status,type,title,tf_upload,schedule_id,slug_id')
                    ->leftJoin('tre_trx_classes', 'tre_trx_classes.id', '=', 'tre_trx_orders.class_id')
                    ->leftJoin('tre_trx_train_events', 'tre_trx_train_events.id', '=', 'tre_trx_classes.trx_train_event_id');
    }

    public function schedule()
    {
        return $this->hasOne(TrxSchedule::class,'id','schedule_id');
    }


}
