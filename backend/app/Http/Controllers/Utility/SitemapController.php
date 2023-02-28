<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Training\TrxEvent;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function article(){
        return response()->json(Article::select('slug_id')->pluck('slug_id'));
    }

    public function event(){
        return response()->json(TrxEvent::select('slug_id')
                        ->where('type','event')->pluck('slug_id'));
    }
}
