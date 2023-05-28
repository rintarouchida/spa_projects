<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
    /**
     * ログイン
     *
     * @param  mixed $request
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only(["email", "password"]))) {
            // レスポンスを返す
            return response()->json(['message' => 'ログインしました'], 200);
        } else {
            // エラーレスポンスを返す
            return response()->json(['message' => 'パスワードかメールアドレスが間違っています。もう一度ログインし直してください。'], 401);
        }
    }

    /**
     * ログアウト
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        // ログアウトする
        Auth::logout();
        // レスポンスを返す
        return response()->json(['message' => 'Logged out'], 200);
    }

    public function get()
    {
        $user = Auth::User();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $this->service->register($data);
        return response()->json(['message' => '登録が完了しました。'], 200);
    }

    public function edit(Request $request): void
    {
        $data = $request->only(["name", "email"]);
        $user = Auth::User();
        $user->update([
            "name" => $data["name"],
            "email" => $data["email"],
        ]);
    }
}
