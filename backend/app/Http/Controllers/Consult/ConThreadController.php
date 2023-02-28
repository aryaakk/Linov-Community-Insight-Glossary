<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Consults\Attachment;
use App\Models\Consults\Love;
use App\Models\Consults\Thread;
use App\Models\Threads\TypeQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\NotifHelper;
use App\Notifications\LikeConstThread;
use App\Notifications\NewConsultation;

class ConThreadController extends Controller
{
    public function index(Request $request, $category=null){
        $data = Thread::query()
                ->selectRaw("con_consul_threads.id, 
                slug_id,
                title,
                description,
                is_private,
                user_id,
                category_id,
                con_consul_threads.created_at")
                ->with('types:id,name,code,color,color_bg', 'smallloves:consul_thread_id,con_love_threads.user_id,name,photo', 'images', 'files','author')
                ->withCountData()
                ->withLoveStatus()
                ->limitDescription(235)
                ->when($request->has('search'), function($query) use($request){
                    $query->where('con_consul_threads.title', 'like', '%'.$request->search.'%')
                          ->orWhere('con_consul_threads.description', 'like', '%'.$request->search.'%');
                })
                ->when($request->has('sort'), function($query) use($request){
                    switch ($request->sort) {
                        case 'lastest':
                            $query->orderBy('con_consul_threads.updated_at', 'desc');
                            break;
                        case 'created':
                            $query->orderBy('con_consul_threads.created_at', 'desc');
                            break;
                        case 'atoz':
                            $query->orderBy('con_consul_threads.title', 'asc');
                            break;
                        case 'ztoa':
                            $query->orderBy('con_consul_threads.title', 'desc');
                            break;
                        case 'mostlike':
                            $query->orderBy('love.total_love', 'desc');
                            break;
                    }
                })
                ->when($request->has('filter') && count($request->filter)>0, function($query) use($request){
                    $query->whereHas('types', function($query) use($request){
                        $query->whereIn('code', $request->filter);
                    });
                })
                ->orderBy('con_consul_threads.created_at', 'DESC')
                ->paging(10);

        return response()->json($data);
    }

    public function show($slug){
        $data = Thread::query()->selectRaw("con_consul_threads.id,slug_id,title,description,user_id,category_id,con_consul_threads.created_at")
                    ->leftjoin(
                    DB::raw("(select thread_id, main_type, code from hom_thread_type_questions join hom_type_questions on hom_type_questions.id=hom_thread_type_questions.type_question_id) type")
                    ,'con_consul_threads.id', '=', 'type.thread_id')
                    ->with('types:id,name,code,color,color_bg', 'smallloves:consul_thread_id,con_love_threads.user_id,name,photo', 'images', 'files','author:id,name,photo')
                    ->withCountData()
                    ->withLoveStatus()
                    ->when(!auth()->check(), function($query){
                        $query->limitDescription(480);
                    })
                    ->where('slug_id', $slug)->orWhere('id', $slug)->firstOrFail();
                    
        return response()->json($data);
    }

    public function save(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'category_id' => 'required|exists:hom_type_questions,id',
            'user_consul_id' => 'required',
            'photos.*' => 'nullable|image|max:2048',
            'upfiles.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        $files =[];
        //save all files
        foreach ([1=>'photos', 2=>'upfiles'] as $key => $type) {
            if($request->hasFile($type)){
                foreach($request->$type as $idx=>$file){
                    $filename= $file->store("public/consults/$type", "oss");
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
            $thread = DB::transaction(function () use($request, $files) {
                $thread = new Thread();
                $thread->title = $request->title;
                $thread->description = $request->description;
                $thread->user_id = auth()->id();
                $thread->created_by = auth()->id();
                $thread->category_id = $request->category_id;
                $thread->user_consul_id = $request->user_consul_id;
                $thread->is_private = $request->is_private ?? 0;
                $thread->save();
                
                foreach ($files as $key => $value) {
                    $files[$key]['consul_thread_id'] = $thread->id;
                }
                (new NotifHelper)->to(User::find($request->user_consul_id))->notify(new NewConsultation([
                    'detail_id'=> $thread->id,
                    'path'     => "/consultation/".$thread->id,
                    'message'  =>"<b>".auth()->user()->name."</b> membuat konsultasi dan memerlukan jawaban anda."
                ]));
                //insert attachment if exist
                Attachment::insert($files);
                return $thread;
            });

            return response()->json($thread, 201);
        } catch (\Throwable $th) {
            Storage::delete(collect($files)->pluck('file')->toArray());
            return response()->json(['error'=>$th->getMessage()], 500);
        }
    }

    public function love(Request $request, $id){
        $loveThread = Love::where('consul_thread_id', $id)->where('user_id', auth()->id())->first();
        
        if($loveThread){
            $loveThread->delete();
        }else{
            $love = new Love();
            $love->user_id = auth()->id();
            $love->consul_thread_id = $id;
            $love->save();

            $userNotif = User::haveThread($id, 'consult')->first();

            (new NotifHelper)->to($userNotif)->notify(new LikeConstThread([
                'detail_id'=> $id,
                'path'     => "/consultation/".@$userNotif->thread_id,
                'message'  =>"<b>".auth()->user()->name."</b> menyukai thread konsultasi kamu"
            ]));
        }

        return response()->json([
            'small_loves'=> Love::selectRaw('con_love_threads.user_id,name,photo')->withProfile()->where('consul_thread_id', $id)->limit(3)->get(),
            'total_love' => Love::where('consul_thread_id', $id)->count(),
            'love_status'=> $loveThread ? false : true
        ]);
    }

    public function delete(Request $request, $id){
        try {
            $thread = Thread::find($id);
            // $thread->images()->delete();
            // $thread->files()->delete();
            $thread->delete();
        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->getMessage()], 500);
        }

        return response()->noContent();
    }

}
