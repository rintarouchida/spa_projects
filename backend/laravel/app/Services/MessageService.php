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

        foreach ($message_groups as $key => $message_group) {
            $data[$key]['id']          = $message_group->id;
            $data[$key]['party_theme'] = $message_group->party->theme;
            foreach($message_group->messages as $index => $message) {
                $data[$key]['messages'][$index]['id']      = $message->id;
                $data[$key]['messages'][$index]['content'] = $message->content;
                $data[$key]['messages'][$index]['user_id'] = $message->user_id;
            }
        }
        return $data;
    }
}
