<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request, $folder){
        $filename= $request->file('file')->storePublicly('public/'.$folder, 'oss');

        return response()->json(['link'=>Storage::disk('oss')->url($filename), 'file_name'=>$filename]);
    }
}
