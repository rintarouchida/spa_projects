<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param int $user_id
     *
     * @return array
     */
    public function getData(int $user_id): array
    {
        $data = $this->service->getData($user_id);
        return $data;
    }

    /**
     * @return array
     */
    public function getAuthData(): array
    {
        $user_id = Auth::id();
        $data = $this->service->getData($user_id);
        return $data;
    }

    /**
     * @param UpdateRequest $request
     * @param int $auth_id
     *
     * @return JsonResponse
     */
    public function updateAuthData(UpdateRequest $request, int $auth_id): JsonResponse
    {
        $data = $request->only(["name", "email", "birthday", "pref_id", "introduction", "twitter_url"]);
        $user = User::find($auth_id);
        $this->service->update($user, $data);
        return response()->json(['message' => '編集が完了しました'], 200);
    }
}
