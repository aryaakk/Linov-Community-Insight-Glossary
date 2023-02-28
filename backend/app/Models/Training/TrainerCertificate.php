<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerCertificate extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trainer_certificate';
    protected $guarded = [];
}
