<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\MessageGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
