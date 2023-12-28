<?php

namespace Tests\Unit\app\Services;

use App\Models\User;
use App\Models\Pref;
use App\Services\UserService;
use Tests\TestCase;
use Carbon\Carbon;
use Config;
use ReflectionMethod;

class UserServiceTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2023-11-15 00:00:00');
        Pref::factory(['id' => 1, 'name' => 'pref_1'])->create();
        Pref::factory(['id' => 2, 'name' => 'pref_2'])->create();
        $this->user = User::factory([
            'id'           => 1,
            'name'         => 'ユーザー1',
            'email'        => 'test1@gmail.com',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction1',
            'pref_id'      => 1,
            'twitter_url'  => 'https://twitter.com',
            'image'        => 'test.jpg',
        ])->create();
    }

    /**
     * getData
     *
     * @return void
     */
    public function test_get_data()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');
        $service = new UserService();

        $actual = $service->getData($this->user->id);
        $this->assertSame($actual, [
            'id'           => 1,
            'name'         => 'ユーザー1',
            'email'        => 'test1@gmail.com',
            'pref_id'      => 1,
            'birthday'     => '2000-01-01',
            'introduction' => 'introduction1',
            'twitter_url'  => 'https://twitter.com',
            'old'          => 23,
            'pref_name'    => 'pref_1',
            'image'        => 'https://test/test.jpg'
        ]);
    }

    /**
     * update
     *
     * @return void
     */
    public function test_update()
    {
        $data = [
            'name'         => 'ユーザー2',
            'email'        => 'test2@gmail.com',
            'pref_id'      => 2,
        ];

        $method = new ReflectionMethod(UserService::class, 'update');
        $method->setAccessible(true);
        $method->invoke(new UserService, $this->user, $data);

        $this->assertDatabaseHas('users', [
            'id'           => 1,
            'name'         => 'ユーザー2',
            'email'        => 'test2@gmail.com',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction1',
            'pref_id'      => 2,
            'twitter_url'  => 'https://twitter.com',
        ]);
    }
}
