<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrxClass extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trx_classes';
    protected $guarded = [];


    public function pub_schedule()
    {
        return $this->hasMany(TrxSchedule::class, 'class_public_id', 'id')
                    ->selectRaw('id,class_public_id,date,start_hour,end_hour')
                    ->where('is_active', '1');
    }

    public function inh_schedule()
    {
        return $this->hasMany(TrxSchedule::class, 'class_inhouse_id', 'id')
                    ->selectRaw('id,class_inhouse_id,date,start_hour,end_hour')
                    ->where('is_active', '1');
    }

    public function scopeWithSchedule($query){
        $query->leftjoin(DB::raw("(SELECT id, date, end_date, DATEDIFF(end_date, date)+1 day,start_hour, end_hour,
    COALESCE(order_count,0) order_count, COALESCE(order_sum,0) order_sum, COALESCE(order_total,0) order_total,
                (CASE 
                WHEN class_public_id IS NULL THEN class_inhouse_id 
                ELSE class_public_id 
                END) class_id
            FROM tre_trx_schedules 
            left join (
                select schedule_id, count(id) order_count, sum(quantity) order_sum from tre_trx_orders where user_id='".auth()->id()."' group by schedule_id
            ) order_data on order_data.schedule_id=tre_trx_schedules.id
            left join(
                select schedule_id, sum(quantity) order_total from tre_trx_orders group by schedule_id
            ) order_summary on order_summary.schedule_id=tre_trx_schedules.id
            where is_active='1' order by date) schedules"),'tre_trx_classes.id', '=', 'schedules.class_id');
    }
}
