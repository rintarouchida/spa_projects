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
        $user_id = Auth::id();
        $message_lists = $this->service->getMessageLists($user_id);
        return $message_lists;
    }

    //todo: getMessageのテスト作成
    /**
     * @param int $message_group_id
     * @return array
     */
    public function getMessage(int $message_group_id): array
    {
        $user_id = Auth::id();
        $messages = $this->service->getMessagesByGroupId($message_group_id, $user_id);
        return $messages;
    }
}
