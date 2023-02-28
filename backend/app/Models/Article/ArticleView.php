<?php

namespace App\Models\Article;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    use HasFactory, Uuids;

    protected $table = 'art_detail_view_article';
    protected $guarded = [];
}
