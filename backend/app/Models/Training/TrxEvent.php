<?php

namespace App\Models\Training;

use App\Traits\Paging;
use App\Traits\Slugable;
use App\Traits\Uuids;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrxEvent extends Model
{
    use HasFactory, Uuids, Paging, Slugable;
    
    protected $table = 'tre_trx_train_events';
    protected $guarded = [];

    protected $appends = [
        'banner_url',
        'banner_card_url',
        'banner_slide_url',
    ];

    public function getBannerUrlAttribute()
    {
        return $this->banner ? Storage::disk('oss')->temporaryUrl($this->banner, now()->addHours(1)) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function getBannerCardUrlAttribute()
    {
        return $this->banner_card ? Storage::disk('oss')->temporaryUrl($this->banner_card, now()->addHours(1)) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function getBannerSlideUrlAttribute()
    {
        return $this->banner_slide ? Storage::disk('oss')->temporaryUrl($this->banner_slide, now()->addHours(1)) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function scopeWithNumTrx($query)
    {
        return $query->selectRaw("COALESCE(num_trx, 0) num_trx")
                ->leftJoin(DB::raw("(select trx_train_event_id, SUM(quantity) num_trx from tre_trx_orders left join tre_trx_schedules ON tre_trx_schedules.id=tre_trx_orders.schedule_id) orders"), 'tre_trx_train_events.id', '=', 'tre_trx_train_events.id');
    }

    public function scopeWithClass($query){
        return $query->selectRaw('price, date, start_hour, end_hour,type_class')
                ->join(DB::raw("
                (select trx_train_event_id event_id, type_class,price, date, start_hour, end_hour from tre_trx_classes join 
                    (SELECT trx_train_event_id event_id, min(date) date, start_hour, end_hour,
                        (CASE 
                        WHEN class_public_id IS NULL THEN class_inhouse_id 
                        ELSE class_public_id 
                        END) class_id
                    FROM tre_trx_schedules WHERE is_active='1' group by trx_train_event_id) schedule on schedule.class_id=tre_trx_classes.id AND tre_trx_classes.trx_train_event_id=schedule.event_id 
                ) classes 
                "),'tre_trx_train_events.id', 'classes.event_id');
    }

    public function schedules()
    {
        return $this->hasMany(TrxSchedule::class, 'trx_train_event_id', 'id')->where('is_active', '1');
    }

    public function classes()
    {
        return $this->hasMany(TrxClass::class, 'trx_train_event_id', 'id')->selectRaw('id,trx_train_event_id,type_class,last_order_date,min_participant,max_participant,max_order,min_order,kurs_dollar,price,is_discount,price_discount');
    }

    public function provider()
    {
        return $this->hasOne(Provider::class, 'id', 'provider_id');
    }

    public function category()
    {
        return $this->hasOne(\App\Models\Threads\Type::class, 'id', 'type_question_id');
    }

    public function trainers()
    {
        return $this->hasMany(TrxTrainer::class, 'trx_train_event_id', 'id')
                    ->join(DB::raw("(
                        SELECT tre_trainers.id, name, photo, details.position, company_name 
                        FROM tre_trainers 
                        LEFT JOIN (
                           SELECT trainer_id, company company_name,position 
                           FROM tre_trainer_details WHERE is_current='1'
                        ) details ON details.trainer_id=tre_trainers.id WHERE is_active='1'
                    ) trainers"), 'trainers.id', 'tre_trx_trainers.trainer_id')->limit(2);
    }
}
