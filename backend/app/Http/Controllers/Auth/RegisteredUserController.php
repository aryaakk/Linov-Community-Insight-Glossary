<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profiles\PhoneCode;
use App\Models\Profiles\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:com_users'],
            'password' => ['required', Rules\Password::defaults(), 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ]);

        $user = User::create([
            'id' => Str::uuid()->toString(),
            'role_id' => Str::uuid()->toString(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'login_by' => 'konven',
            'fb_token' => '',
            'is_verify' => '0',
            'is_active' => '0',
            'token_linov' => Str::random(255),
        ]);

        //event(new Registered($user));

        return response()->noContent();
    }

    /**
     * Handle an incoming profile complate request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function profile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'industry_id'=>['required'],
            'position_id'=>['required'],
            'other_position'=>['nullable']
        ]);

        PhoneCode::updateOrCreate(
        [
            'code'=>@$request->country->iso2,
            'name'=>@$request->country->name,
            'phone_code'=>@$request->country->dialCode,
        ],
        [
            'code'=>@$request->country->iso2,
            'name'=>@$request->country->name,
            'phone_code'=>@$request->country->dialCode,
            'is_active'=>'1',
            'created_by'=>auth()->user()->id
        ]);

        Profile::create(array_merge($validated, [
            'user_id'=>auth()->user()->id,
            'created_by'=>auth()->user()->id,
        ]));

        return response()->json([
            'status'=>'SUCCESS',
            'message'=>'Profile berhasil di simpan'
        ]);
    }
}
