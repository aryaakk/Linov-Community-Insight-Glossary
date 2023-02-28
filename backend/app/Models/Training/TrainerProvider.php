<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerProvider extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_train_providers';
    protected $guarded = [];
}
