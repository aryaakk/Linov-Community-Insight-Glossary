<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $data = Degree::select('id','code', 'name', 'degree', 'is_active');
        
        if($request->perpage){
            return response()->json($data->orderable($request->orders)->searchable($request->columns, $request->search)->paging($request->perpage));
        }

        return response()->json($data->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:36|unique:com_majors,code',
            'name' => 'required|max:100',
            'degree'=>'required|max:100',
            'is_active'=>'required'
        ]);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(Degree::create($validated), 201);
    }

    
    public function show($id)
    {
        return response()->json(Degree::select('id','code', 'name', 'degree','is_active')->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:36|unique:com_majors,code,'.$id,
            'name' => 'required|max:100',
            'degree'=>'required|max:100',
            'is_active'=>'required'
        ]);

        $data = Degree::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    public function destroy(Request $request)
    {
        try {
            Degree::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
