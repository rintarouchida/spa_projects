<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Message\MessageGroupResource;
use App\Models\Message;
use App\Models\MessageGroup;
use Tests\TestCase;

class MessageGroupResourceTest extends TestCase
{
    /**
     * MessageGroupResource
     *
     * @test
     * @return void
     */
    public function MessageGroupResource()
    {
        $group = MessageGroup::factory()
            ->has(Message::factory()->count(5)->state([
            ]), 'messages')
            ->create();

        $groupResource = new MessageGroupResource($group);

        // Resourceを配列に変換
        $groupResourceArray = $groupResource->toArray(null);

        // 最新のメッセージを取得
        $latestMessage = $group->messages->sortByDesc('created_at')->last();

        /// 期待される値を設定
        $expectedArray = [
            'id' => $group->id,
            'party_theme' => $group->party->theme,
            'latest_message' => $latestMessage->content,
            'latest_message_time' => $latestMessage->created_at->format('Y-m-d H:i')
        ];

        // アサート: 期待される配列と Resourceから得られた配列が一致しているか
        $this->assertEquals($expectedArray, $groupResourceArray);
    }
}
