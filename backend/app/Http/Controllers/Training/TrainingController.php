<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSchedule;
use App\Models\Training\TrxTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function getPremium(Request $request)
    {
        $data = TrxEvent::selectRaw('id,slug_id,title,type,banner_slide')
                        ->where('is_ads', '1')
                        ->orderBy('num_ads', 'asc')
                        ->limit(3)
                        ->get();

        return response()->json($data);
    }

    public function calendar(Request $request){
        $data = DB::table('tre_trx_train_events')->selectRaw('tre_trx_train_events.id,title,type,color,date')
                        ->leftJoin('hom_type_questions','type_question_id', 'hom_type_questions.id')
                        ->leftJoin('tre_trx_schedules','trx_train_event_id', 'tre_trx_train_events.id')
                        ->where('tre_trx_schedules.date', '>=', $request->start)
                        ->where('tre_trx_schedules.end_date', '<=', $request->end)
                        ->get()->groupBy('date');

        return response()->json($data);
    }

    public function index(Request $request, $type)
    {
        $data = TrxEvent::selectRaw('id,slug_id,title,type,provider_id,location,level,type_question_id,banner_card,published_date,status')
                          ->with('provider:id,name,logo', 'category:id,name,color,color_bg')
                          ->when($type!='all', function($query) use ($type) {
                            $query->where('type', $type);
                          })
                          ->when($request->has('filter'), function($query) use($request){
                            $query->whereIn('type_question_id', $request->filter);
                          })
                          ->when($request->provider_id, function($query) use($request){
                            $query->where('provider_id', $request->provider_id);
                          })
                          ->when($request->has('search'), function($query) use($request){
                            $query->where('title', 'LIKE', "%$request->search%");
                                //   ->orWhere('sustainable', 'LIKE', "%$request->search%")
                                //   ->orWhere('summary', 'LIKE', "%$request->search%");
                          })
                          ->when($request->has('class') && $request->class!='All', function($query) use($request){
                            $query->where('type_class', $request->class=='Public' ? '1' : '2');
                          })
                          ->withClass();

                          switch($request->sort){
                            case 'Popular':
                                $data->withNumTrx()->orderBy('num_trx', 'desc');
                                break;
                            case 'Highest Price':
                                $data->orderBy('classes.price', 'desc');
                                break;
                            case 'Lowest Price':
                                $data->orderBy('classes.price', 'asc');
                                break;
                            default:
                                $data->orderBy('classes.date', 'desc');
                                break;
                         }
        // return $data->toSql();
        return response()->json($data->paging($request->perpage??10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:training,event',
            // 'trx_date' => 'required|date',
            'provider_id' => 'required|exists:tre_providers,id',
            'title' => 'required|max:100',
            'sustainable'=> 'required|max:200',
            'summary' => 'required',
            'location'=> 'required|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'type_question_id' => 'required|exists:hom_type_questions,id',
            // 'banner' => 'required|image|max:2048',
            // 'banner_card' => 'required|image|max:2048',
            // 'banner_slide'=> 'nullable|image|max:2048',
            // 'is_ads' => 'nullable|in:0,1',
            'num_ads' => 'nullable|integer',
            // 'ads_start_date' => 'nullable|date',
            // 'ads_end_date' => 'nullable|date',
            'published_date' => 'required|date',
            'status' => 'required|in:published,banned,canceled',
        ]);

        $request->validate([
            'class.last_order_date'=>'required|after:yesterday',
            'class.price'=>'required|numeric|min:0'
        ]);
        
        $validated['trx_code'] = 'TR-'.($validated['type']=='training' ? 'TN' : 'EN');
        $validated['trx_date'] = now()->toDateString();
        $validated['is_ads']   = $request->is_ads ? '1' : '0';
        $validated['created_by'] = auth()->id();
        $validated['banner']   = $request->hasFile('banner') ? $request->file('banner')->store('public/training/banner', 'oss') : '';//upload banner
        $validated['banner_card'] = $request->hasFile('banner_card') ? $request->file('banner_card')->store('public/training/banner_card', 'oss') : ''; //upload banner slide
        $validated['banner_slide'] = $request->hasFile('banner_slide') ? $request->file('banner_slide')->store('public/training/banner_slide', 'oss') : ''; //upload banner slide
        
        $data = DB::transaction(function () use($request, $validated){
            $trxTrainer = [];
            $trxSchedule= [];
            $data       = TrxEvent::create($validated);
            $TrxClass   = TrxClass::create(array_merge($request->class, ['trx_train_event_id' => $data->id]));

            foreach ($request->trainer_id??[] as $value) {
                $trxTrainer[] = [
                    'id' =>Str::uuid()->toString(),
                    'trx_train_event_id' => $data->id,
                    'trainer_id' => $value,
                    'created_at' => now()->toDateTimeString()
                ];
            }

            foreach($request->schedules??[] as $key=>$schedule){
                $trxSchedule[$key] = array_merge($schedule, [
                    'id' => Str::uuid()->toString(),
                    'trx_train_event_id'=> $data->id,
                    $TrxClass->type_class=='1' ? 'class_public_id': 'class_inhouse_id'=>$TrxClass->id,
                    'created_at' => now()->toDateTimeString(),
                    'is_active' => '1'
                ]);
            }

            TrxTrainer::insert($trxTrainer);
            TrxSchedule::insert($trxSchedule);
            return $data;
        });

        return response()->json($data, 201);
    }

    public function show($type, $slug)
    {
        $data = TrxEvent::selectRaw('id,banner,location,summary,status,title,level,sustainable,provider_id,type_question_id,type,published_date')
                            ->with('provider:id,name,logo', 'category:id,name,color', 'trainers:trainers.id,trx_train_event_id,name,photo,position,company_name', 'classes')
                            ->where('slug_id', $slug)
                            ->orWhere('id', $slug)
                            ->withClass()
                            ->firstOrFail();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:training,event',
            // 'trx_date' => 'required|date',
            'provider_id' => 'required|exists:tre_providers,id',
            'title' => 'required|max:100',
            'sustainable'=> 'required|max:200',
            'summary' => 'required',
            'location'=> 'required|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'type_question_id' => 'required|exists:hom_type_questions,id',
            'banner' => 'nullable|image|max:2048',
            'banner_card' => 'nullable|image|max:2048',
            'banner_slide'=> 'nullable|image|max:2048',
            'is_ads' => 'nullable|in:0,1',
            'num_ads' => 'nullable|integer',
            // 'ads_start_date' => 'nullable|date',
            // 'ads_end_date' => 'nullable|date',
            'status' => 'required|in:published,banned,canceled',
        ]);
        $training = TrxEvent::find($id);

        $validated['trx_code'] = 'TR-'.($validated['type']=='training' ? 'TN' : 'EN');
        $validated['is_ads']   = $request->is_ads ? '1' : '0';
        $validated['banner']   = $request->hasFile('banner') ? $request->file('banner')->store('public/training/banner', 'oss') : $training->banner;//upload banner
        $validated['banner_card'] = $request->hasFile('banner_card') ? $request->file('banner_card')->store('public/training/banner_card', 'oss') : $training->banner_card; //upload banner slide
        $validated['banner_slide'] = $request->hasFile('banner_slide') ? $request->file('banner_slide')->store('public/training/banner_slide', 'oss') : $training->banner_slide; //upload banner slide

        DB::transaction(function () use($request, $validated, $training){
            $training->update($validated);
            $TrxClass   = TrxClass::find(@$request->class['id']);
            $TrxClass->update(array_merge($request->class, ['trx_train_event_id' => $training->id]));
            //update or create
            foreach($request->schedules??[] as $key=>$schedule){
                if(isset($schedule['id'])){
                    TrxSchedule::find($schedule['id'])->update($schedule);
                }else{
                    TrxSchedule::create(array_merge($schedule, [
                            'id' => Str::uuid()->toString(),
                            'trx_train_event_id'=> $training->id,
                            $TrxClass->type_class=='1' ? 'class_public_id': 'class_inhouse_id'=>$TrxClass->id,
                            'created_at' => now()->toDateTimeString(),
                            'is_active' => '1'
                        ]));
                }
            }
            //delete schedules
            TrxSchedule::where('trx_train_event_id', $training->id)
                        ->whereNotIn('id',collect($request->schedules)
                        ->pluck('id')->toArray())->delete();
            
            $trxTrainer = [];
            TrxTrainer::where('trx_train_event_id', $training->id)->delete();
            foreach ($request->trainer_id??[] as $value) {
                $trxTrainer[] = [
                    'id' =>Str::uuid()->toString(),
                    'trx_train_event_id' => $training->id,
                    'trainer_id' => $value,
                    'created_at' => now()->toDateTimeString()
                ];
            }
            TrxTrainer::insert($trxTrainer);
        });

        return response()->json($training);
    }

    public function destroy(Request $request)
    {
       try {
            TrxEvent::whereIn('id', $request->all())->delete();
            return response()->noContent();
       } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
       }

        return response()->noContent();
    }
    
}
