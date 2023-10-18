<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Party;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class MessageControllerTest extends TestCase
{
    /**
     * sendMessage
     *
     * @return void
     */
    public function test_send_message()
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
     * @return void
     */
    public function test_index()
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
     * @return void
     */
    public function test_get_message()
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
