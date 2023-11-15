<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    //todo:テスト作成
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
