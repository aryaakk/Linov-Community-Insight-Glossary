<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory, Uuids;

    protected $table = 'hom_attach_threads';
    protected $guarded = [];
    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute()
    {
        return Storage::disk('oss')->temporaryUrl($this->file, now()->addHours(1));
    }
}
