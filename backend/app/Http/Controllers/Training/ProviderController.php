<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all){
            $data = Provider::selectRaw('id,name')->get();
            return response()->json($data);
        }

        $data = Provider::selectRaw('tre_providers.id,name,logo,state_name,tre_providers.is_active')
                        ->leftJoin(DB::raw("(select com_states.id, CONCAT(com_states.name, ',',com_phone_codes.name) state_name from com_states left join com_phone_codes on com_phone_codes.id=phone_code_id) state"), 'state.id', 'state_id')
                        ->when($request->has('search'), function($query) use($request){
                            $query->where('name', 'LIKE', "%$request->search%");
                        });
        
        switch($request->sort){
            case 'Z to A':
                $data->orderBy('name', 'desc');
                break;
            case 'Newest':
                $data->orderBy('created_at', 'desc');
                break;
            default:
                $data->orderBy('name', 'asc');
                break;
         }

        return response()->json($data->paging($request->perpage??10));
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
            'code' => 'required|max:36|unique:tre_providers,code',
            // 'company_id'=>'required|max:36|exists:com_companies,id',
            'name' => 'required|max:100',
            'state_id'=>'required|max:36|exists:com_states,id',
            'tagline' => 'nullable|max:100',
            'summary' => 'required',
            'logo' => 'required|image|max:5120',
            'logo_2'=> 'nullable|image|max:5120',
            'num' => 'nullable',
            'is_active' => 'required',
        ]);

        $validated['logo'] = $request->hasFile('logo') ? $request->file('logo')->store('public/provider', 'oss') : '';//upload logo
        $validated['logo_2'] = $request->hasFile('logo_2') ? $request->file('logo_2')->store('public/provider', 'oss') : '';//upload logo_2
        $validated['is_active'] = $validated['is_active'] ? '1' : '0';

        try {
            $provider = DB::transaction(function () use($validated){
                $provider = new Provider();
                $provider->fill($validated);
                $provider->save();

                return $provider;
            });

            return response()->json($provider, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Provider::selectRaw('id,name,code,tagline,summary,logo,logo_2,state_id,is_active')
                ->with('state')->findOrFail($id);

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
            'code' => 'required|max:36|unique:tre_providers,code,'.$id,
            // 'company_id'=>'required|max:36|exists:com_companies,id',
            'name' => 'required|max:100',
            'state_id'=>'required|max:36|exists:com_states,id',
            'tagline' => 'nullable|max:100',
            'summary' => 'required',
            'logo' => 'nullable|image|max:5120',
            'logo_2'=> 'nullable|image|max:5120',
            'num' => 'nullable',
            'is_active' => 'required',
        ]);

        $provider = Provider::query()->find($id);

        $validated['logo'] = $request->hasFile('logo') ? $request->file('logo')->store('public/provider', 'oss') : $provider->logo;//upload logo
        $validated['logo_2'] = $request->hasFile('logo_2') ? $request->file('logo_2')->store('public/provider', 'oss') : $provider->logo_2;//upload logo_2
        $validated['is_active'] = $validated['is_active'] ? '1' : '0';
        
        try {
            $provider->fill($validated);
            $provider->save();

            return response()->json($provider, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            Provider::whereIn('id', $request->all())->delete();
            return response()->noContent();
            // Storage::delete($provider->logo);
            // Storage::delete($provider->logo_2);
        } catch (\Throwable $th) {
           return response()->json(['error' => $th->getMessage()], 500);
        }
        return response()->noContent();
    }
}
