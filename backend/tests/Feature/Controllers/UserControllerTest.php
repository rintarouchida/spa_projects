<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Pref;
use App\Services\UserService;
use Carbon\Carbon;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2023-11-15 00:00:00');
        Pref::factory(['id' => 1, 'name' => 'pref_1'])->create();
        Pref::factory(['id' => 2, 'name' => 'pref_2'])->create();
        User::factory([
            'id'           => 1,
            'name'         => 'ユーザー1',
            'email'        => 'test1@gmail.com',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction1',
            'pref_id'      => 1,
            'twitter_url'  => 'https://twitter.com',
        ])->create();

        User::factory([
            'id'           => 2,
            'name'         => 'ユーザー2',
            'email'        => 'test2@gmail.com',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction2',
            'pref_id'      => 2,
            'twitter_url'  => 'https://twitter.com',
        ])->create();

    }

    /**
     * getData
     *
     * @return void
     */
    public function test_getData()
    {
        $response = $this->get(route('user.get', 1));
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'id'           => 1,
                'name'         => 'ユーザー1',
                'email'        => 'test1@gmail.com',
                'pref_id'      => 1,
                'birthday'     => '2000-01-01',
                'introduction' => 'introduction1',
                'twitter_url'  => 'https://twitter.com',
                'old'          => 23,
                'pref_name'    => 'pref_1',
            ],
        );
    }

    /**
     * getAuthData
     *
     * @return void
     */
    public function test_getAuthData()
    {
        $this->actingAs(User::find(2));
        $response = $this->get(route('user.get_auth'));
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'id'           => 2,
                'name'         => 'ユーザー2',
                'email'        => 'test2@gmail.com',
                'pref_id'      => 2,
                'birthday'     => '2000-01-01',
                'introduction' => 'introduction2',
                'twitter_url'  => 'https://twitter.com',
                'old'          => 23,
                'pref_name'    => 'pref_2',
            ],
        );
    }

    /**
     * updateAuthData
     *
     * @return void
     */
    public function test_updateAuthData()
    {
        $this->actingAs(User::find(1));
        $data = [
            'name'         => 'ユーザー3',
            'email'        => 'test3@gmail.com',
            'birthday'     => '2001-02-02',
            'introduction' => 'introduction3',
            'pref_id'      => 2,
            'twitter_url'  => 'https://twitter_twitter.com',
        ];

        $response = $this->post(route('user.update_auth', 1), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id'           => 1,
            'name'         => 'ユーザー3',
            'email'        => 'test3@gmail.com',
            'birthday'     => '2001-02-02 00:00:00',
            'introduction' => 'introduction3',
            'pref_id'      => 2,
            'twitter_url'  => 'https://twitter_twitter.com',
        ]);
    }
}
