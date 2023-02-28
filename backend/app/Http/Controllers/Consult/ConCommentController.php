<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Consults\Comment;
use App\Models\Consults\Attachment;
use App\Notifications\CommentConsThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Helpers\NotifHelper;

class ConCommentController extends Controller
{
    public function index(Request $request, $thread_id){
        $data = Comment::query()->selectRaw('id,consul_thread_id,con_comment_threads.con_comment_thread_id,comment,user_id,created_at,0 as show_sub, 0 as show_reply')->where('consul_thread_id', $thread_id)
                ->with('author', 'images', 'files')
                ->withCountData()
                ->whereNull('con_comment_threads.con_comment_thread_id')
                ->when(!auth()->check(), function($query){
                    $query->limitComment(100)->limit(1);
                })
                ->when(auth()->check(), function($query){
                    $query->with('subcomment.author');
                })
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
                    $filename= $file->store("public/consulting/$request->mode-$type", "oss");
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
                    $comment->consul_thread_id = $id;
                    $userNotif = User::haveThread($id, 'consult')->first();
                }else{
                    $comment->consul_thread_id=$request->thread_id;
                    $comment->con_comment_thread_id = $id;
                    $userNotif = User::haveComment($id)->first();  
                }
        
                $comment->save();

                $content = [
                    'detail_id'=> $id,
                    'path'     => "/consultation/".@$userNotif->thread_id,
                    'message'  =>"<b>".auth()->user()->name."</b> mengomentari thread konsultasi kamu '".Str::limit($request->comment, 30)
                ];

                (new NotifHelper)->to($userNotif)
                                 ->notify(new CommentConsThread($content));

                foreach ($files as $key => $value) {
                    $files[$key]['consul_thread_id'] = $comment->id;
                }

                Attachment::insert($files);

                return $comment->id;
            });
        } catch (\Throwable $th) {
            Storage::delete(collect($files)->pluck('file')->toArray());
            return response()->json(['error'=>$th->getMessage()], 500);
        }

        $comment = Comment::selectRaw('id,consul_thread_id,con_comment_threads.con_comment_thread_id,comment,user_id,created_at,0 as show_sub, 0 as show_reply')->with('author:id,name,photo','images', 'files')->withCountData()->find($comment_id);
        if($request->mode=='thread'){
            $comment->load('subcomment');
        }

        return response()->json($comment,201);
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
