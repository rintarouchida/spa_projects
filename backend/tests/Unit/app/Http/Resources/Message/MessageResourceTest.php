<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class MessageResourceTest extends TestCase
{

    /**
     * MessageResource
     *
     * @test
     * @return void
     */
    public function MessageResource()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');

        $user = User::factory(['image' => 'test.jpg'])->create();
        //'is_users_message' => trueを期待
        $this->actingAs($user);

        $message = Message::factory()->make([
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
        ]);

        // MessageResource オブジェクトの生成
        $messageResource = new MessageResource($message);

        // 配列に変換
        $messageArray = $messageResource->toArray(null);

        // 期待される値を設定
        $expectedArray = [
            'id' => $message->id,
            'content' => $message->content,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'is_users_message' => true,
            'user_name' => $user->name,
            'user_image' => 'https://test/test.jpg',
        ];

        $this->assertEquals($expectedArray, $messageArray);
    }
}
