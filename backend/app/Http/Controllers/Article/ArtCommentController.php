<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article\Comment;
use Illuminate\Http\Request;

class ArtCommentController extends Controller
{
    public function index(Request $request){
        $comments = Comment::selectRaw('art_comments.id,comment,art_comments.status,approve_by,name,title,art_comments.created_at')
                    ->leftJoin('art_trx_articles', 'art_trx_articles.id', 'art_comments.article_id')
                    ->leftJoin('com_users', 'com_users.id', 'art_comments.user_id')
                    ->searchable($request->columns, $request->search)
                    ->paging();

        return response()->json($comments);
    }

    public function action(Request $request, $status){
        $comments = Comment::find($request->ids);

        foreach($comments as $comment){
            if($status=='delete'){
                $comment->delete();
            }else{
                $comment->status = $status;
                if($status=='approved'){
                    $comment->approve_by=auth()->id();
                }
                $comment->save();
            }
        }

        return response()->json($comments->pluck('id'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'comment'=>'required|max:255',
            'article_id'=>'required'
        ]);
        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending'; 

        return response()->json(Comment::create($validated),201);
    }
}
