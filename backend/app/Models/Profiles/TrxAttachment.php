<?php

namespace App\Models\Profiles;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TrxAttachment extends Model
{
    use HasFactory, Uuids;

    protected $table = 'con_trx_attach_consultants';
    protected $guarded = [];
    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute()
    {
        return Storage::disk('oss')->temporaryUrl($this->file_attach, now()->addHours(1));
    }
}
