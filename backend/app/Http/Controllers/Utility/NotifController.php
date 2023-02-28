<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\ComNotification;
use Illuminate\Http\Request;

class NotifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = auth()->user()->notifications()->orWhereNull('notifiable_id')->selectRaw('id,message_title,message,icon,path,read_at,created_by,created_at')->with('sender:com_users.id,name,photo')->paging(10);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $total = auth()->user()->notifications()
                              ->whereIn('id', $request->all())
                              ->whereNull('read_at')
                              ->update(['read_at'=>now()->toDateTimeString()]);

        return response()->json(['total'=>$total]);
    }
}
