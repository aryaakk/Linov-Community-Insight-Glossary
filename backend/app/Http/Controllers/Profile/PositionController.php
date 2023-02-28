<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Handle an incoming request of industries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Position::select('id','code', 'name', 'is_active');
        
        if($request->perpage){

            $data = $data->orderable($request->orders)
                         ->searchable($request->columns, $request->search)
                         ->paging($request->perpage);
            return response()->json($data);
        }

        return response()->json($data->where('is_active', '1')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:8|unique:com_positions,code',
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(Position::create($validated), 201);
    }

    
    public function show($id)
    {
        return response()->json(Position::select('id','code', 'name', 'is_active')->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:8|unique:com_positions,code,'.$id,
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);

        $data = Position::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    public function destroy(Request $request)
    {
        try {
            Position::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
