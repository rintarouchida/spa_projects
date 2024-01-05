<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Party;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class MessageControllerTest extends TestCase
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
        $this->actingAs($user);
        $data = [
            'message_group_id' => MessageGroup::factory(['id' => 1])->create()->id,
            'content' => 'メッセージ',
        ];

        $this->assertDatabaseMissing('messages', [
            'user_id' => 1,
            'message_group_id' => 1,
            'content' => 'メッセージ',
        ]);

        $response = $this->post(route('message.send_message'), $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'message_group_id' => 1,
            'content' => 'メッセージ',
        ]);
    }

    /**
     * index
     *
     * @test
     * @return void
     */
    public function index()
    {
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);
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

        $response = $this->get(route('message.index'));
        $response->assertStatus(200);
        $response->assertJson([
            [
                'id'                  => 1,
                'party_theme'         => 'party_1',
                'latest_message'      => 'メッセージ1',
                'latest_message_time' => '2023-10-14 10:00',
            ],
            [
                'id'                  => 2,
                'party_theme'         => 'party_2',
                'latest_message'      => 'メッセージ4',
                'latest_message_time' => '2023-10-12 10:00',
            ],
        ]);
    }

    /**
     * getMessage
     *
     * @test
     * @return void
     */
    public function getMessage()
    {
        $user = User::factory(['id' => 1, 'name' => 'ユーザー1'])->create();
        $this->actingAs($user);
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

        $response = $this->get(route('message.get', $message_group->id));
        $response->assertStatus(200);
        $response->assertJson([
            'theme' => 'party_1',
            'messages' => [
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
            ],
        ]);
    }

    /**
     * indexForLeader
     *
     * @test
     * @return void
     */
    public function indexForLeader()
    {
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);
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

        $response = $this->get(route('message.index_for_leader'));
        $response->assertStatus(200);
        $response->assertJson([
            [
                'id'                  => 1,
                'party_theme'         => 'party_1',
                'latest_message'      => 'メッセージ2',
                'latest_message_time' => '2023-10-14 10:00',
            ],
            [
                'id'                  => 2,
                'party_theme'         => 'party_2',
                'latest_message'      => 'メッセージ4',
                'latest_message_time' => '2023-10-16 10:00',
            ],
        ]);
    }
}
