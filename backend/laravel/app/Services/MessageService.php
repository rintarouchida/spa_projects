<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;

class MessageService
{
    /**
     * @param int $user_id
     * @param array $params
     * @return void
     */
    public function sendMessage(int $user_id, array $params): void
    {
        Message::create([
            'user_id' => $user_id,
            'message_group_id' => $params['message_group_id'],
            'content' => $params['content'],
        ]);
    }

    //todo:メッセージ一覧の関数を作成したのでテストを作成する
    /**
     * @param int $user_id
     * @return array
     */
    public function getMessageLists(int $user_id): array
    {
        $message_groups = MessageGroup::whereHas('users', function ($query) {
            $query->where('id', $user_id);
        })->get();

        foreach ($message_groups as $key => $message_group) {
            $data[$key]['id']          = $message_group->id;
            $data[$key]['party_theme'] = $message_group->party->theme;
            $data[$key]['messages']    = $this->getMessages();
            $data[$key]['due_max']     = $party->due_max;
        }
        return $data;
    }

    protected function getMessages(MessageGroup $message_group): array
    {
        foreach ($message_group->messages as $key => $message) {
            $data[$key]['id']      = $message->id;
            $data[$key]['content'] = $message->content;
            $data[$key]['user_id'] = $message->user_id;
        }
    }
}
