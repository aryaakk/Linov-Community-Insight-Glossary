<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, Request $request)
    {
        if($request->headers->get('Origin')==config('app.admin_url')){
            return response()->json(TrxSchedule::selectRaw('id,date,end_date,start_hour,end_hour')->where('trx_train_event_id', $id)->get());
        }

        $data = TrxClass::query()->selectRaw("schedules.id, type_class, schedules.*, max_participant,max_order")
                ->withSchedule()
                ->where('tre_trx_classes.trx_train_event_id', $id)
                ->whereRaw('last_order_date>=DATE(NOW())')
                ->whereRaw('date>=DATE(NOW())')
                ->get()->groupBy('type_class');

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trx_train_event_id'=> 'required|exists:tre_trx_train_events,id',
            'class_public_id' => 'required_if:class_inhouse_id,null|exists:tre_trx_classes,id',
            'class_inhouse_id'=> 'required_if:class_public_id,null|exists:tre_trx_classes,id',
            'date' => 'required|date',
            'is_same_hour' => 'required|in:0,1',
            'schedule_id' => 'nullable|exists:tre_trx_schedules,id',
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s',
            'is_active' => 'required|in:0,1',
        ]);

        $validated['created_by'] = @$request->user()->id;

        $data = TrxSchedule::create($validated);

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $data = TrxSchedule::selectRaw('tre_trx_schedules.id,type,title,level,trx_train_event_id,class_public_id,class_inhouse_id,date,end_date,DATEDIFF(end_date,date)+1 day, start_hour,end_hour')
                ->with('classPublic', 'classInhouse')
                ->leftJoin('tre_trx_train_events', 'tre_trx_train_events.id', '=', 'tre_trx_schedules.trx_train_event_id')
                ->find($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'trx_train_event_id'=> 'required|exists:tre_trx_train_events,id',
            'class_public_id' => 'required_if:class_inhouse_id,null|exists:tre_trx_classes,id',
            'class_inhouse_id'=> 'required_if:class_public_id,null|exists:tre_trx_classes,id',
            'date' => 'required|date',
            'is_same_hour' => 'required|in:0,1',
            'schedule_id' => 'nullable|exists:tre_trx_schedules,id',
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s',
            'is_active' => 'required|in:0,1',
        ]);

        $data = TrxSchedule::find($id);
        $data->update($validated);

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            TrxSchedule::find($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->noContent();
    }
}
