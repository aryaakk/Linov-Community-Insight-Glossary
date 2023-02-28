<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\Utility\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Bank::selectRaw('id,code,icon,bank_name,is_active');

        if($request->perpage){
            return response()->json($data->orderable($request->orders)->searchable($request->columns, $request->search)->paging($request->perpage));
        }
        
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
            'code' => 'required|max:100|unique:utl_banks,code',
            'icon' => 'required|image|max:200',
            'bank_name' => 'required|max:100',
            'is_active' => 'required'
        ]);

        $validated['icon'] = $request->file('icon')->store('public/bank');
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $bank = Bank::create($validated);

        return response()->json($bank, 201);
    }

    public function show($id)
    {
        return response()->json(Bank::select('id','code', 'bank_name','icon', 'is_active')->find($id));
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
            'code' => 'required|max:100|unique:utl_banks,code,'.$id,
            'icon' => 'nullable|image|max:200',
            'bank_name' => 'required|max:100',
            'is_active' => 'required'
        ]);

        $bank = Bank::find($id);
        $validated['icon'] = $request->hasFile('icon') ? $request->file('icon')->store('public/bank') : $bank->icon;
        $validated['is_active'] = $validated['is_active'] ? '1' : '0'; 
        $bank->update($validated);

        return response()->json($bank, 200);
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
            Bank::whereIn('id', $request->all())->delete();
            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
