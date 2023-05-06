<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * ログイン
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only(["email", "password"]))) {
            // レスポンスを返す
            return response()->json(['message' => 'success'], 200);
        } else {
            // エラーレスポンスを返す
            return response()->json(['message' => 'failed'], 401);
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
}
