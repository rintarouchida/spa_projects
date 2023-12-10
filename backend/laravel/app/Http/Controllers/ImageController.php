<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function s3(Request $request)
    {
        $file = $request->file('image');
        Storage::disk('s3')->putFile('/', $file);
        return response()->json([
            'message' => $request->file('image')->getClientOriginalName(),
            'message2' => $request->text,
        ], 200);
    }
}

// https://tech.aainc.co.jp/archives/10714
// https://saunabouya.com/2022/06/06/%E3%80%90vue-js3%E7%B3%BB%E3%80%91formdata%E3%81%A7%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB%E3%82%82%E3%82%AA%E3%83%96%E3%82%B8%E3%82%A7%E3%82%AF%E3%83%88%E3%82%82%E4%B8%80%E7%B7%92%E3%81%ABaxios%E3%81%AE/
