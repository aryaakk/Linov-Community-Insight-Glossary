<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrxSyllabus;
use App\Models\Training\TrxSyllabusDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = TrxSyllabus::query()->selectRaw('id,title,sub_title,seq')
                ->with('details:id,syllabus_id,icon,head,description,seq')
                ->where('trx_train_event_id', $id)
                ->orderBy('seq')
                ->get();

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
        if($request->details){
            $validated = $request->validate([
                'details.syllabus_id'=> 'required',
                'details.icon' => 'required|max:3',
                'details.head' => 'required|max:200',
                'details.description' => 'required',
                'details.seq'=> 'required'
            ]);

            $details = TrxSyllabusDetail::create($validated['details']);
            return response()->json($details, 201);
        }else{
            $validated = $request->validate([
                'trx_train_event_id'=> 'required|exists:tre_trx_train_events,id',
                'title' => 'required|max:30',
                'sub_title' => 'nullable|max:50',
                'seq' => 'required|integer',
            ]);

            $syllabus = TrxSyllabus::create($validated);
            return response()->json($syllabus, 201);
        }
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
        if($request->details){
            $validated = $request->validate([
                'details.syllabus_id'=> 'required',
                'details.icon' => 'required|max:3',
                'details.head' => 'required|max:200',
                'details.description' => 'required',
                'details.seq'=> 'required'
            ]);

            $details = TrxSyllabusDetail::find($id);
            $details->update($validated['details']);
            return response()->json($details, 200);
        }else{
            $validated = $request->validate([
                'title' => 'required|max:30',
                'sub_title' => 'nullable|max:50',
                'seq' => 'required|integer',
            ]);

            $syllabus = TrxSyllabus::find($id);
            $syllabus->update($validated);
            return response()->json($syllabus, 200);
        }
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
            TrxSyllabus::destroy($id);
            TrxSyllabusDetail::destroy($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->noContent();
    }
}
