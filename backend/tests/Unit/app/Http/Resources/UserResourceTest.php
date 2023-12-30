<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\Pref;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Tests\TestCase;

class UserResourceTest extends TestCase
{

    public function testUserResourceReturnsExpectedFormat()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');

        Pref::factory(['id' => 1])->create();
        $user = new User([
            'id' => 1,
            'name' => '鈴木一郎',
            'email' => 'test@example.com',
            'pref_id' => 1,
            'birthday' => Carbon::now()->subYears(20)->format('Y-m-d H:i:s'), // 20年前の日付を生成
            'introduction' => 'こんにちは、鈴木です。',
            'twitter_url' => 'https://twitter.com/suzuki',
            'image' => 'profile.jpg',
        ]);

        $userResource = new UserResource($user);

        // 配列に変換
        $userArray = $userResource->toArray(null);

        // UserResourceによって変換された配列が期待した形式であることを確認
        $age = Carbon::parse($user->birthday)->age;
        $expectedArray = [
            'id'           => 1,
            'name'         => '鈴木一郎',
            'email'        => 'test@example.com',
            'pref_id'      => 1,
            'birthday'     => Carbon::parse($user->birthday)->format('Y-m-d'),
            'introduction' => 'こんにちは、鈴木です。',
            'twitter_url'  => 'https://twitter.com/suzuki',
            'old'          => $age,
            'pref_name'    => $user->pref->name,
            'image'        => 'https://test/profile.jpg'
        ];

        $this->assertEquals($expectedArray, $userArray);
    }
}
