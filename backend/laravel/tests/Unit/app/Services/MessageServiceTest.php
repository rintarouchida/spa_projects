<?php

namespace Tests\Unit\app\Services;

use Carbon\Carbon;
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

    /**
     * getMessagesByGroupId
     *
     * @return void
     */
    public function test_get_messages_by_group_id()
    {
        $user = User::factory(['id' => 1, 'name' => 'ユーザー1'])->create();
        $message_group = MessageGroup::factory([
            'id' => 1,
            'party_id' => Party::factory(['theme' => 'party_1'])->create()->id,
        ])->create();
        Message::factory(4)->create(new Sequence(
            ['id' => 1, 'message_group_id' => $message_group->id, 'content' => 'メッセージ1', 'user_id' => $user->id, 'created_at' => '2023-10-13 10:00:00'],
            ['id' => 2, 'message_group_id' => $message_group->id, 'content' => 'メッセージ2', 'user_id' => User::factory(['id' => 2, 'name' => 'ユーザー2'])->create()->id, 'created_at' => '2023-10-14 10:00:00'],
            ['id' => 3, 'message_group_id' => $message_group->id, 'content' => 'メッセージ3', 'user_id' => User::factory(['id' => 3, 'name' => 'ユーザー3'])->create()->id, 'created_at' => '2023-10-15 10:00:00'],
            ['id' => 4, 'message_group_id' => $message_group->id, 'content' => 'メッセージ4', 'user_id' => User::factory(['id' => 4, 'name' => 'ユーザー4'])->create()->id, 'created_at' => '2023-10-16 10:00:00'],
        ));
        $service = new MessageService();
        $actual = $service->getMessagesByGroupId($message_group->id, $user->id);
        $this->assertSame($actual,[
            [
                'id'               => 1,
                'content'          => 'メッセージ1',
                'created_at'       => '2023-10-13 10:00:00',
                'is_users_message' => true,
                'user_name'        => 'ユーザー1',
            ],
            [
                'id'               => 2,
                'content'          => 'メッセージ2',
                'created_at'       => '2023-10-14 10:00:00',
                'is_users_message' => false,
                'user_name'        => 'ユーザー2',
            ],
            [
                'id'               => 3,
                'content'          => 'メッセージ3',
                'created_at'       => '2023-10-15 10:00:00',
                'is_users_message' => false,
                'user_name'        => 'ユーザー3',
            ],
            [
                'id'               => 4,
                'content'          => 'メッセージ4',
                'created_at'       => '2023-10-16 10:00:00',
                'is_users_message' => false,
                'user_name'        => 'ユーザー4',
            ],
        ]);
    }
}
