<?php

namespace App\Http\Controllers\Threads;

use App\Helpers\NotifHelper;
use App\Http\Controllers\Controller;
use App\Models\ComNotification;
use App\Models\Threads\Attachment;
use App\Models\Threads\Comment;
use App\Models\Threads\Love;
use App\Models\User;
use App\Notifications\CommentThread;
use App\Notifications\LikeComment;
use App\Notifications\LikeThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function index($thread_id){
        $data = Comment::query()->selectRaw('id,thread_id,hom_comment_threads.comment_thread_id,comment,user_id,created_at,0 as show_sub, 0 as show_reply')->where('thread_id', $thread_id)
                ->with('author', 'subcomment.author', 'images', 'files')
                ->withCountData()
                ->withLoveStatus()
                ->whereNull('hom_comment_threads.comment_thread_id')
                ->orderBy('created_at', 'ASC')
                ->get();

        return response()->json($data);
    }

    public function save(Request $request, $id){
        $validated = $request->validate([
            'comment' => 'required|string|max:200',
            'photos.*' => 'nullable|image|max:10480',
            'upfiles.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:1048',
        ]);

        $files =[];
        $comment_id=null;
        //save all files
        foreach ([1=>'photos', 2=>'upfiles'] as $key => $type) {
            if($request->hasFile($type)){
                foreach($request->$type as $idx=>$file){
                    $filename= $file->store("public/comment/$request->mode-$type", "oss");
                    $files[] = [
                        'id' => Str::uuid()->toString(),
                        'type' => $key,
                        'file' => $filename,
                        'file_type' => $file->extension(),
                        'size' => round($file->getSize()/1024, 2),
                        'created_by'=>auth()->id(),
                        'created_at'=>date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        try {
            $comment_id = DB::transaction(function () use($request, $id, $files) {
                $comment = new Comment();
                $comment->comment = $request->comment;
                $comment->user_id = auth()->id();        
                if($request->mode=='thread'){
                    $comment->thread_id = $id;
                    $userNotif = User::haveThread($id)->first();
                }else{
                    $comment->thread_id=$request->thread_id;
                    $comment->comment_thread_id = $id;
                    $content['message'] = "<b>".auth()->user()->name."</b> mengomentari comment kamu '".Str::limit($request->comment, 20);    
                    $userNotif = User::haveComment($id)->first();  
                }
                $comment->save();

                $content = [
                    'detail_id'=> $id,
                    'path'     => "/threads/".@$userNotif->thread_id,
                    'message'  =>"<b>".auth()->user()->name."</b> mengomentari thread kamu '".Str::limit($request->comment, 30)
                ];

                (new NotifHelper)->to($userNotif)
                                 ->notify(new CommentThread($content));

                foreach ($files as $key => $value) {
                    $files[$key]['comment_thread_id'] = $comment->id;
                }

                Attachment::insert($files);

                return $comment->id;
            });
        } catch (\Throwable $th) {
            Storage::delete(collect($files)->pluck('file')->toArray());
            return response()->json(['error'=>$th->getMessage()], 500);
        }

        $comment = Comment::selectRaw('id,thread_id,hom_comment_threads.comment_thread_id,comment,user_id,created_at,0 as show_sub, 0 as show_reply')->with('author:id,name,photo','images', 'files')->withCountData()->withLoveStatus()->find($comment_id);
        if($request->mode=='thread'){
            $comment->load('subcomment');
        }

        return response()->json($comment, 201);
    }

    public function love(Request $request, $id){
        $loveComment = Love::where('comment_thread_id', $id)->where('user_id', auth()->id())->first();
      
        if($loveComment){
            $loveComment->delete();
        }else{
            $love = new Love();
            $love->user_id = auth()->id();
            $love->comment_thread_id = $id;
            $love->save();

            $userNotif = User::haveComment($id)->first();

            (new NotifHelper)->to($userNotif)->notify(new LikeComment([
                'detail_id'=> $id,
                'path'     => "/threads/".@$userNotif->thread_id,
                'message'  =>"<b>".auth()->user()->name."</b> menyukai komentar kamu"
            ]));
        }

        return response()->json([
            'total_love' => Love::where('comment_thread_id', $id)->count(),
            'love_status'=> $loveComment ? false : true
        ]);
    }

    public function delete($id){
        $comment = Comment::with('images', 'files')->find($id);
        try {
            // Storage::delete($comment->images()->pluck('file')->toArray());
            // Storage::delete($comment->files()->pluck('file')->toArray());
            // $comment->images()->delete();
            // $comment->files()->delete();
            $comment->delete();

        } catch (\Throwable $th) {
            throw $th;
        }
        
        return response()->noContent();
    }
}
