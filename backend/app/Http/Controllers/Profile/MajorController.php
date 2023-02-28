<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $data = Major::select('id','code', 'name', 'is_active');
        
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
            'is_active'=>'required'
        ]);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(Major::create($validated), 201);
    }

    
    public function show($id)
    {
        return response()->json(Major::select('id','code', 'name', 'is_active')->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:36|unique:com_majors,code,'.$id,
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);

        $data = Major::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    public function destroy(Request $request)
    {
        try {
            Major::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
