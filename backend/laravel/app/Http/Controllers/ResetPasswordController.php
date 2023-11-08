<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Password;
use App\Models\User;

class ResetPasswordController extends Controller
{
    //todo: テスト作成
    public function sendEmail(Request $request)
    {
        Password::sendResetLink(['email' => $request->email], function (User $user, string $token) use ($request) {
            Mail::send(new ResetPassword($request->email, $token, $user->name));
        });
        return response()->json(['message' => 'メールを送信しました。'], 200);
    }

    public function resetPassword()
    {
        //todo: トークンが正しいか確認, 正しければパスワード更新
    }
}
