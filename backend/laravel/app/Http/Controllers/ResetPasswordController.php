<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Password;

class ResetPasswordController extends Controller
{
    //todo: テスト作成
    //todo: バリデーション作成
    //todo: 細かい処理をServiceクラスにうつす
    //todo: Exeptionエラーの設置
    public function sendEmail(Request $request)
    {
        Password::sendResetLink(['email' => $request->email], function (User $user, string $token) use ($request) {
            Mail::send(new ResetPassword($request->email, $token, $user->name));
        });
        return response()->json(['message' => 'メールを送信しました。'], 200);
    }

    public function resetPassword(Request $request)
    {
        $result = Password::reset([
            'email' => $request->email,
            'token' => $request->token,
            'password' => $request->password,
        ],
        function(User $user) use ($request){
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        });
        return response()->json(['message' => $result], 200);
    }
}
