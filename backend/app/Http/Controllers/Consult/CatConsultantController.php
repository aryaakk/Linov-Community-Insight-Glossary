<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Consults\Category;
use Illuminate\Http\Request;

class CatConsultantController extends Controller
{
    /**
     * Handle an incoming request of industries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Category::select('id','code', 'name', 'is_active');

        if($request->perpage){
            return response()->json($data->orderable($request->orders)->searchable($request->columns, $request->search)->paging($request->perpage));
        }
        
        return response()->json($data->get());
    }

    public function category(){
        $data = Category::selectRaw("id,code,name")->where("is_active", "1")->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:8|unique:com_category_consultants,code',
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(Category::create($validated), 201);
    }

    
    public function show($id)
    {
        return response()->json(Category::select('id','code', 'name', 'is_active')->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:8|unique:com_category_consultants,code,'.$id,
            'name' => 'required|max:100',
            'is_active'=>'required'
        ]);

        $data = Category::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    public function destroy(Request $request)
    {
        try {
            Category::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
