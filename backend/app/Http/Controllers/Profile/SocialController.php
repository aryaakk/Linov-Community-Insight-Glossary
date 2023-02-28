<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function socials(Request $request){
        $data = Social::selectRaw('id,name,icon,0 as show_input')->with('data:id,social_media_id,url')->where('is_active', '1')->get();

        return response()->json($data);
    }

     /**
     * Handle an incoming request of industries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Social::select('id', 'name', 'icon', 'url', 'is_active');

        if($request->perpage){
            return response()->json($data->orderable($request->orders)->searchable($request->columns, $request->search)->paging($request->perpage));
        }
        
        return response()->json($data->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'icon' => 'required|max:100',
            'url' => 'required|max:100',
            'is_active'=>'required'
        ]);

        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 

        return response()->json(Social::create($validated), 201);
    }

    
    public function show($id)
    {
        return response()->json(Social::select('id', 'name', 'icon', 'url', 'is_active')->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'icon' => 'required|max:100',
            'url' => 'required|max:100',
            'is_active'=>'required'
        ]);
        
        $data = Social::find($id);
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $data->update($validated);
        return response()->json($data, 200);
    }

    public function destroy(Request $request)
    {
        try {
            Social::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
