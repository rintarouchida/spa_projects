<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
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
     * ログアウト
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'ログアウトが完了しました。'], 200);
    }

    /**
     * ユーザー登録
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
     * @return JsonResource
     */
    public function getAuthData(): JsonResource
    {
        return UserResource::make(Auth::user());
    }

    /**
     * ユーザー情報更新
     * @param UpdateRequest $request
     * @param int $auth_id
     *
     * @return JsonResponse
     */
    public function updateAuthData(UpdateRequest $request, int $auth_id): JsonResponse
    {
        $data = $request->only(["name", "email", "birthday", "pref_id", "introduction", "twitter_url"]);
        $data["image"] = $request->file("image");
        $user = User::find($auth_id);
        $this->service->update($user, $data);
        return response()->json(['message' => '編集が完了しました'], 200);
    }
}
