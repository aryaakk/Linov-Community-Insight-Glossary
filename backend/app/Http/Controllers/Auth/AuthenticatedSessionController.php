<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Helpers\RealIpAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        // dd($request);
        $request->token ? $this->loginWithToken($request) : $request->authenticate();
        $request->session()->regenerate();

        //$token   = auth()->user()->createToken('login');
        $user_id = auth()->user()->id;

        auth()->user()->sessions()->create([
            'user_id'  => $user_id,
            'token_id' => null,
            'ip_address' => RealIpAddress::getIp(),
            'is_allowed' => '1',
            'user_agent' => request()->header('user-agent'),
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'updated_at' => date('Y-m-d H:i:s'),
            'version' =>0
        ]);

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    private function loginWithToken(LoginRequest $request)
    {
        try {
            $request['token'] = decrypt($request->token);
        } catch (\Throwable $th) {
            throw $th;
        }

        if($request->token){
            $user = User::find($request->token);
            if (! $request->hasValidSignature() && !$user) {
                throw ValidationException::withMessages([
                    'status'  => 'FAILED',
                    'message' => __('auth.failed'),
                ]);
            }
    
            Auth::login($user);
        }
    }
}
