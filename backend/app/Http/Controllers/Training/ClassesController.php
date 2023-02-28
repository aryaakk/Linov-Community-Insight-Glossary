<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrxClass;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TrxClass::query()
                    ->where('trx_train_event_id', @$request->event_id)
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
        $validated = $request->validate([
            'trx_train_event_id'=> 'required|exists:tre_trx_train_events,id',
            'type_class' => 'required|in:1,2',
            'seq' => 'required|integer',
            'last_order_date' => 'required|date',
            'description' => 'nullable|max:100',
            'min_participant'=> 'required|integer',
            'max_participant' => 'required|integer',
            'kurs_dollar' => 'required|in:0,1',
            'price' => 'required|numeric',
            'is_discount' => 'required|in:0,1',
            'kurs_dollar_discount' => 'required|in:0,1',
            'price_discount' => 'required|numeric',
            'is_scheduled' => 'required|in:0,1',
        ]);

        $data = TrxClass::create($validated);

        return response()->json($data, 201);
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
            'type_class' => 'required|in:1,2',
            'seq' => 'required|integer',
            'last_order_date' => 'required|date',
            'description' => 'nullable|max:100',
            'min_participant'=> 'required|integer',
            'max_participant' => 'required|integer',
            'kurs_dollar' => 'required|in:0,1',
            'price' => 'required|numeric',
            'is_discount' => 'required|in:0,1',
            'kurs_dollar_discount' => 'required|in:0,1',
            'price_discount' => 'required|numeric',
            'is_scheduled' => 'required|in:0,1',
        ]);

        $data = TrxClass::find($id);
        
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
            TrxClass::find($id)->delete();
        } catch (\Throwable $th) {
           return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->noContent();
    }
}
