<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){
        return response()->json(Company::get());
    }

    public function store(Request $request){
        $validated = $request->validate([
            'code' => 'required|unique:com_companies|max:36',
            'model' => 'required|max:1',
            'name' => 'required|max:100',
            'address' => 'required|max:100',
            'description' => 'nullable|max:255',
            'city_id' => 'nullable|exists:com_cities,id',
            'is_active' => 'required|boolean',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    public function show($id){
        return response()->json(Company::find($id));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'code' => 'required|unique:com_companies,code,'.$id.'|max:36',
            'model' => 'required|max:1',
            'name' => 'required|max:100',
            'address' => 'required|max:100',
            'description' => 'nullable|max:255',
            'city_id' => 'nullable|exists:com_cities,id',
            'is_active' => 'required|boolean',
        ]);

        $company = Company::find($id);
        $company->update($validated);

        return response()->json($company, 200);
    }

    public function destroy(Request $request, $id){
       try {
            Company::find($id)->delete();
       } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
       }

        return response()->noContent();
    }
}
