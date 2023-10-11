<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Http\Requests\Message\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function sendMessage(SendMessageRequest $request): void
    {
        $user_id = Auth::id();
        $param = $request->only(['message_group_id', 'content']);
        $this->service->sendMessage($user_id, $param);
    }

    /**
     * @return array
     */
    public function index(): array
    {
        //todo:メッセージ一覧の関数を作成したのでテストを作成する
        $user_id = Auth::id();
        $message_lists = $this->service->getMessageLists($user_id);
        return $message_lists;
    }
}
