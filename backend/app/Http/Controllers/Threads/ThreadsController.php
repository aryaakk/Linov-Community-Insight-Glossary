<?php

namespace App\Http\Controllers\Threads;

use App\Helpers\NotifHelper;
use App\Http\Controllers\Controller;
use App\Models\Threads\Attachment;
use App\Models\Threads\Bookmark;
use App\Models\Threads\Dismiss;
use App\Models\Threads\Love;
use App\Models\Threads\PollDetail;
use App\Models\Threads\PollDuration;
use App\Models\Threads\Polling;
use App\Models\Threads\PollVoter;
use App\Models\Threads\Report;
use App\Models\Threads\Thread;
use App\Models\Threads\Type;
use App\Models\Threads\TypeQuestion;
use App\Models\Threads\View;
use App\Models\User;
use App\Notifications\LikeThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ThreadsController extends Controller
{
    /**
     * Handle an incoming request of industries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDiscuses(Request $request)
    {
        $data = Thread::query()
            ->selectRaw("hom_threads.id, 
                type.type_question_id, 
                type.main_type,
                question.name,
                question.color,
                question.color_bg,
                hom_threads.slug_id,
                hom_threads.title")
            ->leftjoin('hom_thread_type_questions as type', 'hom_threads.id', '=', 'type.thread_id')
            ->leftjoin('hom_type_questions as question', 'type.type_question_id', '=', 'question.id')
            ->withCountData()
            ->with('image')
            ->orderBy('total_comment', 'DESC')
            ->orderBy('total_comment', 'DESC')
            ->orderBy('total_view', 'DESC')
            ->where('type.main_type', 0)
            ->limit(3)
            ->get();

        return response()->json($data);
    }

    public function index(Request $request, $category = null)
    {
        $data = Thread::query()
            ->selectRaw("hom_threads.id, 
                hom_threads.slug_id,
                hom_threads.title,
                hom_threads.description,
                hom_threads.user_id,
                hom_threads.created_at")
            ->with('types:thread_id,code,name,color,color_bg', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files', 'author')
            ->withCountData()
            ->withLoveStatus()
            ->withBookmarkStatus()
            ->withDismisStatus()
            ->limitDescription(235)
            ->notReported()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('hom_threads.title', 'like', '%' . $request->search . '%')
                    ->orWhere('hom_threads.description', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'lastest':
                        $query->orderBy('hom_threads.updated_at', 'desc');
                        break;
                    case 'created':
                        $query->orderBy('hom_threads.created_at', 'desc');
                        break;
                    case 'atoz':
                        $query->orderBy('hom_threads.title', 'asc');
                        break;
                    case 'ztoa':
                        $query->orderBy('hom_threads.title', 'desc');
                        break;
                    case 'mostlike':
                        $query->orderBy('love.total_love', 'desc');
                        break;
                    case 'mostview':
                        $query->orderBy('view.total_view', 'desc');
                        break;
                }
            })
            ->when($request->has('filter') && count($request->filter) > 0, function ($query) use ($request) {
                $query->whereHas('types', function ($query) use ($request) {
                    $query->withQuestion()->whereIn('code', $request->filter);
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('types', function ($query) use ($category) {
                    $query->withQuestion()->where('code', $category);
                });
            })
            ->when(!$category, function ($query) {
                $query->whereHas('types', function ($query) {
                    $query->where('main_type', 0);
                });
            })
            ->orderBy('hom_threads.created_at', 'DESC');
        //dd($data->toSql());
        // echo $data->toSql();
        // return;
        return response()->json($data->paging(10));
    }

    public function show($slug)
    {
        $data = Thread::query()->selectRaw("hom_threads.id, 
                    hom_threads.slug_id,
                    hom_threads.title,
                    hom_threads.description,
                    hom_threads.user_id,
                    hom_threads.created_at")
            ->leftjoin(
                DB::raw("(select thread_id, main_type, code from hom_thread_type_questions join hom_type_questions on hom_type_questions.id=hom_thread_type_questions.type_question_id) type"),
                'hom_threads.id',
                '=',
                'type.thread_id'
            )
            ->with('types:thread_id,name,code,color,questions.type_question_id', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files', 'author:id,name,photo')
            ->withCountData()
            ->withLoveStatus()
            ->withBookmarkStatus()
            ->withDismisStatus()
            ->where('slug_id', $slug)->orWhere('id', $slug)->firstOrFail();

        View::firstOrCreate([
            'user_id' => auth()->id(),
            'thread_id' =>  @$data->id,
            'created_by' => auth()->id(),
        ]);

        return response()->json($data);
    }

    public function related(Request $request, $id)
    {
        $codes = TypeQuestion::withQuestion()->where('thread_id', $id)->pluck('code')->toArray();

        $data   = Thread::query()
            ->selectRaw("hom_threads.id, 
                type.type_question_id, 
                hom_threads.slug_id,
                type.main_type,
                question.name,
                question.color,
                hom_threads.title")
            ->leftjoin('hom_thread_type_questions as type', 'hom_threads.id', '=', 'type.thread_id')
            ->leftjoin('hom_type_questions as question', 'type.type_question_id', '=', 'question.id')
            ->withCountData()
            ->with('image')
            ->where('type.main_type', 0)
            ->where('hom_threads.id', '<>', $id)
            ->whereHas('types', function ($query) use ($codes) {
                $query->whereIn('code', $codes);
            })
            ->orderBy('total_comment', 'DESC')
            ->orderBy('total_view', 'DESC')
            ->orderBy('total_comment', 'DESC')
            ->limit(3)
            ->get();

        return response()->json($data);
    }

    public function reported(Request $request)
    {
        $data = Thread::query()
            ->selectRaw("hom_threads.id, 
                        hom_threads.title,
                        hom_threads.slug_id,
                        com_users.name,
                        hom_threads.description,
                        hom_threads.user_id,
                        total_report,
                        hom_threads.created_at,
                        hom_threads.deleted_at")
            ->withTrashed()
            ->withReportStatus()
            ->leftJoin('com_users', 'com_users.id', 'hom_threads.user_id')
            ->where('report_status', 1)
            ->orderable($request->orders)
            ->searchable($request->columns, $request->search)
            ->paging(10);

        return response()->json($data);
    }

    public function chart($id)
    {
        $labels = [
            '1' => "It's really annoy me",
            '2' => "It's annoy me",
            '3' => "It's lil bit annoy me",
            '4' => "Somehow it's lil bit annoy me"
        ];
        $data = Report::selectRaw('level, COUNT(id) num_report')
            ->groupBy('thread_id')
            ->where('thread_id', $id)
            ->get()->groupBy('level');
        $report = [];

        foreach ($labels as $key => $label) {
            $report['labels'][] = $label;
            $report['datas'][] = @$data[$key][0]->num_report ?? 0;
        }

        return response()->json($report);
    }

    public function reporters(Request $request, $id)
    {
        $data = Report::selectRaw('hom_report_threads.id,name,level,reason,hom_report_threads.created_at')
            ->leftJoin('com_users', 'com_users.id', 'hom_report_threads.user_reporter_id')
            ->orderable($request->orders)
            ->searchable($request->columns, $request->search)
            ->where('thread_id', $id)
            ->paging($request->perpage ?? 10);

        return response()->json($data);
    }

    public function visibility($id)
    {
        $thread = Thread::withTrashed()->find($id);

        $thread->update([
            'deleted_at' => !$thread->deleted_at ? now()->toDateTimeString() : null
        ]);

        return response()->json($thread);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'question_id' => 'required|array',
            'photos.*' => 'nullable|image|max:10480',
            'upfiles.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:1048',
        ]);

        $files = [];
        //save all files
        foreach ([1 => 'photos', 2 => 'upfiles'] as $key => $type) {
            if ($request->hasFile($type)) {
                foreach ($request->$type as $idx => $file) {
                    $filename = $file->store("public/threads/$type", "oss");
                    $files[] = [
                        'id' => Str::uuid()->toString(),
                        'type' => $key,
                        'file' => $filename,
                        'file_type' => $file->extension(),
                        'size' => round($file->getSize() / 1024, 2),
                        'created_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        try {
            $thread = DB::transaction(function () use ($request, $files) {
                $thread = new Thread();
                $thread->title = $request->title;
                $thread->description = $request->description;
                $thread->user_id = auth()->id();
                $thread->created_by = auth()->id();
                $thread->save();

                foreach ($request->question_id as $idx => $question_id) {
                    TypeQuestion::create([
                        'thread_id' => $thread->id,
                        'type_question_id' => $question_id,
                        'main_type' => $idx == 0 ? '0' : '1',
                        'created_by' => auth()->id(),
                    ]);
                }

                foreach ($files as $key => $value) {
                    $files[$key]['thread_id'] = $thread->id;
                }
                //insert attachment if exist
                Attachment::insert($files);

                //create polling if exist
                $polling = json_decode($request->polling);
                if ($polling) {
                    $poll = Polling::create([
                        'thread_id' => $thread->id,
                        'description' => $polling->description,
                        'duration_poll_id' => $polling->duration,
                        'created_by' => auth()->id(),
                    ]);

                    foreach ($polling->options as $option) {
                        $poll->details()->create([
                            'thread_poll_id' => $poll->id,
                            'poll_name' => $option,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                return $thread;
            });

            return response()->json($thread, 201);
        } catch (\Throwable $th) {
            Storage::disk('oss')->delete(collect($files)->pluck('file')->toArray());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'question_id' => 'required|array',
            'photos.*' => 'nullable|image|max:10480',
            'upfiles.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:1048',
        ]);

        $files = [];
        //save all files
        foreach ([1 => 'photos', 2 => 'upfiles'] as $key => $type) {
            if ($request->hasFile($type)) {
                foreach ($request->$type as $idx => $file) {
                    $filename = $file->store("public/threads/$type", "oss");
                    $files[] = [
                        'id' => Str::uuid()->toString(),
                        'type' => $key,
                        'file' => $filename,
                        'file_type' => $file->extension(),
                        'size' => round($file->getSize() / 1024, 2),
                        'created_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        try {
            $thread = DB::transaction(function () use ($request, $files, $id) {
                TypeQuestion::where('thread_id', $id)->delete();
                Attachment::where('thread_id', $id)->delete();

                $thread = Thread::find($id);
                $thread->title = $request->title;
                $thread->description = $request->description;
                $thread->save();

                foreach ($request->question_id as $idx => $question_id) {
                    TypeQuestion::create([
                        'thread_id' => $thread->id,
                        'type_question_id' => $question_id,
                        'main_type' => $idx == 0 ? '0' : '1',
                        'created_by' => auth()->id(),
                    ]);
                }

                foreach ($files as $key => $value) {
                    $files[$key]['thread_id'] = $thread->id;
                }
                //insert attachment if exist
                Attachment::insert($files);

                return $thread;
            });

            return response()->json($thread, 201);
        } catch (\Throwable $th) {
            Storage::disk('oss')->delete(collect($files)->pluck('file')->toArray());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function report(Request $request, $id)
    {
        $report = Report::create([
            'thread_id' => $id,
            'level'     => $request->level,
            'reason'    => $request->reason,
            'user_reporter_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        return response()->json($report);
    }

    public function getTypes()
    {
        $data = Type::select('id', 'code', 'name', 'color')->where('is_active', '1')->get();

        return response()->json($data);
    }

    public function love(Request $request, $id)
    {
        $loveThread = Love::where('thread_id', $id)->where('user_id', auth()->id())->first();

        if ($loveThread) {
            $loveThread->delete();
        } else {
            $love = new Love();
            $love->user_id = auth()->id();
            $love->thread_id = $id;
            $love->save();

            $userNotif = User::haveThread($id)->first();

            (new NotifHelper)->to($userNotif)->notify(new LikeThread([
                'detail_id' => $id,
                'path'     => "/threads/" . @$userNotif->thread_id,
                'message'  => "<b>" . auth()->user()->name . "</b> menyukai thread kamu"
            ]));
        }

        return response()->json([
            'small_loves' => Love::selectRaw('hom_love_threads.user_id,name,photo')->withProfile()->where('thread_id', $id)->limit(3)->get(),
            'total_love' => Love::where('thread_id', $id)->count(),
            'love_status' => $loveThread ? false : true
        ]);
    }

    public function bookmark(Request $request, $id)
    {
        $bookmarkThread = Bookmark::where('thread_id', $id)->where('user_id', auth()->id())->first();

        if ($bookmarkThread) {
            $bookmarkThread->delete();
        } else {
            $bookmark = new Bookmark();
            $bookmark->user_id = auth()->id();
            $bookmark->thread_id = $id;
            $bookmark->save();
        }

        return response()->json([
            'bookmark_status' => $bookmarkThread ? false : true
        ]);
    }

    public function dismiss(Request $request, $id)
    {
        $dismissThread = Dismiss::where('thread_id', $id)->where('user_dismisser_id', auth()->id())->first();

        if ($dismissThread) {
            $dismissThread->delete();
        } else {
            $bookmark = new Dismiss();
            $bookmark->user_dismisser_id = auth()->id();
            $bookmark->thread_id = $id;
            $bookmark->save();
        }

        return response()->json([
            'dismiss_status' => $dismissThread ? false : true
        ]);
    }

    public function vote(Request $request, $id)
    {

        DB::transaction(function () use ($request) {
            $pollDetail = PollDetail::find($request->detail_id);
            $pollVoter = PollVoter::leftJoin('hom_thread_detail_polls', 'hom_thread_detail_polls.id', 'hom_thread_detail_voters.detail_poll_id')
                ->where('thread_poll_id', $request->poll_id)
                ->where('user_id', auth()->id())->first();

            if (!$pollVoter) {
                $poll = new PollVoter();
                $poll->user_id = auth()->id();
                $poll->detail_poll_id = $request->detail_id;
                $poll->save();

                $pollDetail->counter = $pollDetail->counter + 1;
                $pollDetail->save();
            }
        });
        $polling = Polling::selectRaw('id,thread_id,description')->with('details')
            ->withSum('details as total_vote', 'counter')
            ->withDuration()
            ->withPollStatus()
            ->where('thread_id', $id)->first();

        return response()->json($polling);
    }

    public function pollduration(Request $request)
    {
        $data = PollDuration::select('id', 'name', 'convert_day')->where('is_active', '1')->get()->keyBy('id');
        return response()->json($data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $thread = Thread::find($id);
            // $thread->images()->delete();
            // $thread->files()->delete();
            $thread->delete();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

        return response()->noContent();
    }
}
