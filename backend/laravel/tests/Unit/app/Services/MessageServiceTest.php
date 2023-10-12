<?php

namespace Tests\Unit\app\Services;

use App\Models\MessageGroup;
use App\Models\User;
use App\Models\Party;
use App\Models\Message;
use App\Services\MessageService;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

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

    /**
     * getMessageLists
     *
     * @return void
     */
    public function test_get_message_lists()
    {
        $user = User::factory(['id' => 1])->create();
        MessageGroup::factory([
            'id' => 1,
            'party_id' => Party::factory(['theme' => 'party_1'])->create()->id,
        ])->create()->users()->attach($user->id);
        MessageGroup::factory([
            'id' => 2,
            'party_id' => Party::factory(['theme' => 'party_2'])->create()->id,
        ])->create()->users()->attach($user->id);

        Message::factory(4)->create(new Sequence(
            ['id' => 1, 'message_group_id' => 1, 'content' => 'メッセージ1', 'user_id' => $user->id],
            ['id' => 2, 'message_group_id' => 1, 'content' => 'メッセージ2', 'user_id' => User::factory(['id' => 2])->create()->id],
            ['id' => 3, 'message_group_id' => 2, 'content' => 'メッセージ3', 'user_id' => User::factory(['id' => 3])->create()->id],
            ['id' => 4, 'message_group_id' => 2, 'content' => 'メッセージ4', 'user_id' => User::factory(['id' => 4])->create()->id],
        ));
        $service = new MessageService();
        $actual = $service->getMessageLists(1);
        $this->assertSame($actual,[
            [
                'id' => 1,
                'party_theme' => 'party_1',
                'messages' => [
                    [
                        'id' => 1,
                        'content' => 'メッセージ1',
                        'user_id' => 1
                    ],
                    [
                        'id' => 2,
                        'content' => 'メッセージ2',
                        'user_id' => 2
                    ],
                ],
            ],
            [
                'id' => 2,
                'party_theme' => 'party_2',
                'messages' => [
                    [
                        'id' => 3,
                        'content' => 'メッセージ3',
                        'user_id' => 3
                    ],
                    [
                        'id' => 4,
                        'content' => 'メッセージ4',
                        'user_id' => 4
                    ],
                ],
            ],
        ]);
    }
}
