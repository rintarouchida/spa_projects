<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function SendEmail(Request $request)
    {
        Mail::send(new ResetPassword($request->email));
        return response()->json(['message' => 'メールを送信しました。'], 200);
    }

    public function ResetPassword()
    {
        //todo: トークンが正しいか確認, 正しければパスワード更新
    }
}
