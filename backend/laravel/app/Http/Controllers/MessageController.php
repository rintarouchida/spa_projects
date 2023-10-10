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
}
