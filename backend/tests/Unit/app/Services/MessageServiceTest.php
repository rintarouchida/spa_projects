<?php

namespace Tests\Unit\app\Services;

use Carbon\Carbon;
use App\Models\MessageGroup;
use App\Models\User;
use App\Models\Party;
use App\Models\Message;
use App\Services\MessageService;
use Config;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class MessageServiceTest extends TestCase
{
    /**
     * sendMessage
     *
     * @test
     * @return void
     */
    public function sendMessage()
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
     * @test
     * @return void
     */
    public function getMessageLists()
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
            ['id' => 1, 'message_group_id' => 1, 'content' => 'メッセージ1', 'created_at' => '2023-10-14 10:00:00'],
            ['id' => 2, 'message_group_id' => 1, 'content' => 'メッセージ2', 'created_at' => '2023-10-13 10:00:00'],
            ['id' => 3, 'message_group_id' => 2, 'content' => 'メッセージ3', 'created_at' => '2023-10-11 10:00:00'],
            ['id' => 4, 'message_group_id' => 2, 'content' => 'メッセージ4', 'created_at' => '2023-10-12 10:00:00'],
        ));
        $service = new MessageService();
        $actual = $service->getMessageLists(1);
        $this->assertSame($actual->pluck('id')->toArray(), [1, 2]);
    }

    /**
     * getMessagesByMessageGroup
     *
     * @test
     * @return void
     */
    public function getMessagesByMessageGroup()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');
        $user = User::factory(['id' => 1, 'name' => 'ユーザー1', 'image' => 'test1.jpg'])->create();
        $message_group = MessageGroup::factory([
            'id' => 1,
            'party_id' => Party::factory(['theme' => 'party_1'])->create()->id,
        ])->create();
        MessageGroup::factory(['id' => 2])->create();
        Message::factory(4)->create(new Sequence(
            ['id' => 1, 'message_group_id' => $message_group->id, 'content' => 'メッセージ1', 'user_id' => $user->id, 'created_at' => '2023-10-13 10:00:00'],
            ['id' => 2, 'message_group_id' => 2, 'content' => 'メッセージ2', 'user_id' => User::factory(['id' => 2, 'name' => 'ユーザー2', 'image' => 'test2.jpg'])->create()->id, 'created_at' => '2023-10-14 10:00:00'],
            ['id' => 3, 'message_group_id' => $message_group->id, 'content' => 'メッセージ3', 'user_id' => User::factory(['id' => 3, 'name' => 'ユーザー3', 'image' => 'test3.jpg'])->create()->id, 'created_at' => '2023-10-15 10:00:00'],
            ['id' => 4, 'message_group_id' => 2, 'content' => 'メッセージ4', 'user_id' => User::factory(['id' => 4, 'name' => 'ユーザー4', 'image' => 'test4.jpg'])->create()->id, 'created_at' => '2023-10-16 10:00:00'],
        ));
        $service = new MessageService();
        $actual = $service->getMessagesByMessageGroup($message_group);
        $this->assertSame($actual->pluck('id')->toArray(), [1, 3]);
    }

    /**
     * getMessageListsForLeader
     *
     * @test
     * @return void
     */
    public function getMessageListsForLeader()
    {
        $user = User::factory(['id' => 1])->create();
        Party::factory(['id' => 1, 'theme' => 'party_1', 'leader_id' => $user->id])->create();
        Party::factory(['id' => 2, 'theme' => 'party_2', 'leader_id' => $user->id])->create();
        Party::factory(['id' => 3, 'theme' => 'party_2', 'leader_id' =>  User::factory(['id' => 2])->create()->id])->create();

        MessageGroup::factory(3)->create(new Sequence(
            ['id' => 1, 'party_id' => 1],
            ['id' => 2, 'party_id' => 2],
            ['id' => 3, 'party_id' => 3],
        ));

        Message::factory(6)->create(new Sequence(
            ['id' => 1, 'message_group_id' => 1, 'content' => 'メッセージ1', 'user_id' => User::factory(['id' => 4, 'name' => 'ユーザー4'])->create()->id, 'created_at' => '2023-10-13 10:00:00'],
            ['id' => 2, 'message_group_id' => 1, 'content' => 'メッセージ2', 'user_id' => User::factory(['id' => 5, 'name' => 'ユーザー5'])->create()->id, 'created_at' => '2023-10-14 10:00:00'],
            ['id' => 3, 'message_group_id' => 2, 'content' => 'メッセージ3', 'user_id' => User::factory(['id' => 6, 'name' => 'ユーザー6'])->create()->id, 'created_at' => '2023-10-15 10:00:00'],
            ['id' => 4, 'message_group_id' => 2, 'content' => 'メッセージ4', 'user_id' => User::factory(['id' => 7, 'name' => 'ユーザー7'])->create()->id, 'created_at' => '2023-10-16 10:00:00'],
            ['id' => 5, 'message_group_id' => 3, 'content' => 'メッセージ5', 'user_id' => User::factory(['id' => 8, 'name' => 'ユーザー8'])->create()->id, 'created_at' => '2023-10-12 10:00:00'],
            ['id' => 6, 'message_group_id' => 3, 'content' => 'メッセージ6', 'user_id' => User::factory(['id' => 9, 'name' => 'ユーザー9'])->create()->id, 'created_at' => '2023-10-11 10:00:00'],
        ));

        $service = new MessageService();
        $actual = $service->getMessageListsForLeader(1);
        $this->assertSame($actual->pluck('id')->toArray(), [1, 2]);
    }
}
