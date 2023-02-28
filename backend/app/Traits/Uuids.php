<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootUuids()
    {
        static::creating(function ($model) {
            $column_key = $model->getKeyName();
            $model->$column_key = $model->$column_key ? $model->$column_key : Str::uuid()->toString();
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

   /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}