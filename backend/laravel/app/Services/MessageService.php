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

    /**
     * @param int $user_id
     * @return array
     */
    public function getMessageLists(int $user_id): array
    {
        $message_groups = MessageGroup::whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })->get();

        $data = [];

        //todo: 取得データ変更によるテスト修正
        foreach ($message_groups as $key => $message_group) {
            $data[$key]['id']          = $message_group->id;
            $data[$key]['party_theme'] = $message_group->party->theme;
            $data[$key]['latest_message'] = $message_group->messages->first()->content;
            $data[$key]['latest_message_time'] = $message_group->messages->first()->created_at->format('Y-m-d H:i');
        }
        return $data;
    }
}
