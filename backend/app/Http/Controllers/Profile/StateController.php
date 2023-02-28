<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\City;
use App\Models\Profiles\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = State::select('id','code','name','is_active','country_name')
                        ->withCountry()
                        ->orderable($request->orders)
                        ->searchable($request->columns, $request->search);
        if($request->perpage)
            return response()->json($data->paging($request->perpage));

        return response()->json($data->get());
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
            'phone_code_id'=> 'required',
            'code' => 'required|max:36',
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(State::create($validated), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(State::select('id','code', 'name', 'is_active', 'phone_code_id')->find($id));
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
            'phone_code_id'=> 'required',
            'code' => 'required|max:36',
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);

        $data = State::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            State::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
