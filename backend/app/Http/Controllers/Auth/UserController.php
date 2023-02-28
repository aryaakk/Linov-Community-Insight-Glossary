<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return authenticated user.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        $user = $request->user();
        $user->hasProfile = $request->user()->hasFilledProfile();
        $user->hasVerified= $request->user()->hasVerifiedEmail();
        $user->avatar = $user->hasProfile ? $request->user()->getAvatar() : null;
        $user->notifications = $user->notifications()->with('sender:id,name')->whereNull('read_at')->orWhereNull('notifiable_id')->limit(5)->get();
        $user->unreadNotif = $user->notifications()->whereNull('read_at')->count();
        return $user;
    }
}
