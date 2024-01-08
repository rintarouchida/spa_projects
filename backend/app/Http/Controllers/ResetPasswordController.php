<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Password\SendEmailRequest;
use App\Http\Requests\Auth\Password\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Password;

class ResetPasswordController extends Controller
{
    //todo: テスト作成
    /**
     * @param SendEmailRequest $request
     *
     * @return JsonResponse
     */
    public function sendEmail(SendEmailRequest $request): JsonResponse
    {
        $result = Password::sendResetLink(['email' => $request->email],
        function (User $user, string $token) use ($request) {
            Mail::send(new ResetPassword($request->email, $token, $user->name));
        });

        if ($result === PasswordBroker::INVALID_USER) {
            return response()->json(['message' => '無効なユーザーです。'], 400);
        }

        else if ($result === PasswordBroker::RESET_THROTTLED) {
            return response()->json(['message' => 'トークンの有効期限が過ぎています。'], 400);
        }

        return response()->json(['message' => 'メールを送信しました。'], 200);
    }

    /**
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
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

        if ($result === PasswordBroker::INVALID_USER) {
            return response()->json(['message' => '無効なユーザーです。'], 400);
        }

        else if ($result === PasswordBroker::INVALID_TOKEN) {
            return response()->json(['message' => '無効なトークンです。'], 400);
        }

        return response()->json(['message' => 'パスワードの変更が完了しました。'], 200);
    }
}
