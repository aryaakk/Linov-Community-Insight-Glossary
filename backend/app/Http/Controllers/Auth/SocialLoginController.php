<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\User;

class SocialLoginController extends Controller
{
    public function redirect($service)
    {
        auth()->guard('web')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return Socialite::driver($service)->redirect();
    }

    public function callback($service)
    {
        try {
            $serviceUser = Socialite::driver($service)->user();
        } catch (\Exception $e) {
            return redirect(config('app.frontend_url') . '/auth/social-callback?error=Unable to login using ' . $service . '. Please try again' . '&origin=login');
        }

        $user = User::where('email', $serviceUser->getEmail())->first();
        if (!$user) {
            $id     = Str::uuid()->toString();
            $others = $service=='google' 
            ? ['google_token'=> $serviceUser->token, 'login_by'=>'google', 'google_id'=>$serviceUser->getId()]
            : ['login_by'=>'fb', 'fb_token'=> $serviceUser->token, 'fb_id'=>$serviceUser->getId()];

            $user = User::create(array_merge([
                'name' => $serviceUser->getName(),
                'email' => $serviceUser->getEmail(),
                'password' => bcrypt(Str::random(10)),
                'role_id' => $id,
                'verified_kode' => random_int(100000, 999999),
                'verified_date' => now()->toDateTimeString(),
                'is_verify' => '1',
                'is_active' => '1',
                'created_by' => $id,
                'updated_by' => $id,
                'token_linov' => $serviceUser->token,
            ],$others));
        }

        $token = base64_encode(URL::temporarySignedRoute('login', now()->addMinutes(10), ['token' => encrypt($user->id)]));

        return redirect(config('app.frontend_url') . "/auth/social-callback?token=$token");
    }
}
