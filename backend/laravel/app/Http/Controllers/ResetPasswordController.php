<?php

namespace App\Http\Controllers;

use App\Services\ResetPasswordService;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    protected $service;

    public function __construct(ResetPasswordService $service)
    {
        $this->service = $service;
    }

    public function SendEmail()
    {
        //todo: 該当ユーザーにメール送信
    }

    public function ResetPassword()
    {
        //todo: トークンが正しいか確認, 正しければパスワード更新
    }
}
