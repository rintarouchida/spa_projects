<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only(["email", "password"]))) {
            return response()->json(['message' => 'ログインしました'], 200);
        } else {
            return response()->json(['message' => 'パスワードかメールアドレスが間違っています。もう一度ログインし直してください。'], 401);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $user = Auth::User();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->all();
        $this->service->register($data);
        return response()->json(['message' => '登録が完了しました。'], 200);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
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
