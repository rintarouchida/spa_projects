<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function get()
    {
        $user = Auth::User();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function register(RegisterRequest $request): void
    {
        $data = $request->all();
        User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "pref_id" => $data["pref_id"],
            "introduction" => $data["introduction"],
            "twitter_url" => $data["twitter_url"],
            "birthday" => $data["birthday"],
        ]);
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
