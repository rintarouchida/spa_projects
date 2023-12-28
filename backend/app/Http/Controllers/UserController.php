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
}
