<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profiles\City;
use App\Models\Profiles\PhoneCode;
use App\Models\Profiles\State;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getRegion(Request $request, $country=null, $state=null){
        if($state){
            return response()->json(City::where('state_id', $state)->select('id', 'name', 'state_id')->get());
        }

        if($country){
            return response()->json(State::where('phone_code_id', $country)->select('id', 'name', 'phone_code_id')->get());
        }

        return response()->json(PhoneCode::select('id', 'name')->get());
    }
}
