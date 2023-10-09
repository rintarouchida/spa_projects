<?php

namespace Tests\Unit\app\Services;

use App\Models\MessageGroup;
use App\Models\User;
use App\Services\MessageService;
use Tests\TestCase;

class MessageServiceTest extends TestCase
{
    /**
     * sendMessage
     *
     * @return void
     */
    public function test_send_message()
    {
        $user = User::factory(['id' => 1])->create();
        $data = [
            'message_group_id' => MessageGroup::factory(['id' => 1])->create()->id,
            'content' => 'メッセージ',
        ];

        $this->assertDatabaseMissing('messages', [
            'user_id' => 1,
            'message_group_id' => 1,
            'content' => 'メッセージ',
        ]);

        $service = new MessageService();
        $service->sendMessage($user->id, $data);
        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'message_group_id' => 1,
            'content' => 'メッセージ',
        ]);
    }
}
