<?php

namespace App\Models\Article;

use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Slugable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, Uuids, Paging, Slugable, Searchable;

    protected $table = 'art_trx_articles';
    protected $appends = [
        'human_date',
        'banner_url',
    ];
    protected $casts = [
        'category' => 'array',
        'tags' => 'array',
    ];
    protected $guarded = [];

    public function getBannerUrlAttribute()
    {
        return $this->banner ? Storage::disk('oss')->url($this->banner) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
    }

    public function scopePublished($query)
    {
        $query->where('status', '=', 'published')->whereRaw('published_date <= now()');
    }

    public function scopeViewCount($query)
    {
        $query->leftJoin(DB::raw('(select count(id) num_view, article_id from art_detail_view_article group by article_id) view'), 'art_trx_articles.id', '=', 'view.article_id');
    }

    public function getHumanDateAttribute($value)
    {
        return \Carbon\Carbon::parse($this->published_date)->diffForHumans();
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'article_id', 'id')
                    ->selectRaw('id,article_id,comment,user_id')
                    ->where('status', 'approved')
                    ->with('author');
    }
}
