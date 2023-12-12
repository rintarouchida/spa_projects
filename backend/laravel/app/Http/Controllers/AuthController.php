<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
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

    //todo:テスト作成
    /**
     * @return array
     */
    public function getAuthData(): array
    {
        $user_id = Auth::id();
        $service = new UserService();
        $data = $service->getData($user_id);
        return $data;
    }

    //todo:テスト作成
    public function updateAuthData(UpdateRequest $request, int $auth_id)
    {
        $data = $request->only(["name", "email", "birthday", "pref_id", "introduction", "twitter_url"]);
        $data["image"] = $request->file("image");
        $user = User::find($auth_id);
        $this->service->update($user, $data);
        return response()->json(['message' => '編集が完了しました'], 200);
    }

    //todo:確認して問題なければ削除
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
