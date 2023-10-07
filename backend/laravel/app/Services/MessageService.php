<?php

namespace App\Services;

use App\Models\Message;

class MessageService
{
    public function sendMessage(int $user_id, array $params): void
    {
        Message::create([
            'user_id' => $user_id,
            'message_group_id' => $params['message_group_id'],
            'content' => $params['content'],
            'is_read' => false,
        ]);
    }
}
