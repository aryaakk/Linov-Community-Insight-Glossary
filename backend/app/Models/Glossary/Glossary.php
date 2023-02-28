<?php

namespace App\Models\Glossary;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Slugable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    use HasFactory, Uuids, Searchable;

    protected $table = "art_glossary";

    protected $guarded = [];
}
