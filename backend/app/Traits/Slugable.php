<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Slugable{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootSlugable()
    {
        static::saving(function ($model) {
            $oriTitle= $model->getRawOriginal('title');
            $oriSlug = $model->getRawOriginal('slug_id');
            if($oriTitle!=$model->title){
                $model->slug_id = Str::slug($model->title).'-'.Str::random(5);
            }
            if($oriSlug!=$model->slug_id){
                $model->slug_id = $model->slug_id.'-'.Str::random(5);
            }
        });
    }
}