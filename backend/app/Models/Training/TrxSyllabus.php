<?php

namespace App\Models\Training;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxSyllabus extends Model
{
    use HasFactory, Uuids;

    protected $table = 'tre_trx_syllabus';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(TrxSyllabusDetail::class, 'syllabus_id')->orderBy('seq');
    }
}
