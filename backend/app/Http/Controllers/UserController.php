<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
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
     * @return JsonResource
     */
    public function getData(int $user_id): JsonResource
    {
        $user = $this->service->getUser($user_id);
        return UserResource::make($user);
    }
}
