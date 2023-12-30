<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\Message\MessageGroupCollection;
use App\Http\Resources\Message\MessageCollection;
use App\Models\MessageGroup;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    /**
     * @param SendMessageRequest $request
     *
     * @return void
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        $user_id = Auth::id();
        $param = $request->only(['message_group_id', 'content']);
        $this->service->sendMessage($user_id, $param);
        return response()->json(['message' => 'メッセージを送信しました。'], 200);
    }

    /**
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $user_id = Auth::id();
        $message_lists = $this->service->getMessageLists($user_id);
        return MessageGroupCollection::make($message_lists);
    }

    /**
     * @return ResourceCollection
     */
    public function indexForLeader(): ResourceCollection
    {
        $user_id = Auth::id();
        $message_lists = $this->service->getMessageListsForLeader($user_id);
        return MessageGroupCollection::make($message_lists);
    }

    /**
     * @param int $message_group_id
     * @return array
     */
    public function getMessage(int $message_group_id): array
    {
        $message_group = MessageGroup::find($message_group_id);
        $messages = $this->service->getMessagesByMessageGroup($message_group);

        $collection = (array)MessageCollection::make($messages);
        $resource['messages'] = $collection['collection'];
        $resource['theme'] = $message_group->party->theme;

        return $resource;
    }
}
