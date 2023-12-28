<?php

namespace Tests\Feature\Controllers;

use App\Models\Pref;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected string $name = 'テスト太郎';
    protected string $email = 'test@email.com';
    protected string $password = 'password';
    protected string $birthday = '2023-05-08 00:00:00';
    protected string $introduction = 'こんにちは';
    protected string $twitter_url = 'https://twitter.com';
    protected string $image = 'test.jpg';
    protected string $mock_image = 's3_test.jpg';

    /**
     * register
     *
     * @return void
     */
    public function test_register()
    {
        Pref::factory(['id' => 1])->create();

        $this->assertDatabaseMissing('users', [
            'name' => $this->name,
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'birthday' => $this->birthday,
            'pref_id' => 1,
            'introduction' => $this->introduction,
            'twitter_url' => $this->twitter_url,
            'image' => $this->image,
        ];

        $mockAuthService = $this->getMockBuilder(AuthService::class)
            ->setMethods(['createS3Image'])
            ->getMock();
        $mockAuthService->expects($this->any())
            ->method('createS3Image')
            ->will($this->returnValue($this->mock_image));

        //AuthServiceに対してmockデータを返すインスタンス結合
        app()->instance(AuthService::class, $mockAuthService);

        $response = $this->put(route('register'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => $this->name,
            'image' => $this->mock_image
        ]);
    }

    /**
     * login
     *
     * @return void
     */
    public function test_login()
    {
        Pref::factory(['id' => 1])->create();
        User::create(
            [
                'id' => 1,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'birthday' => $this->birthday,
                'pref_id' => 1,
                'introduction' => $this->introduction,
                'twitter_url' => $this->twitter_url,
            ]
        );
        $this->assertFalse(Auth::check());
        $response = $this->post(route('login'), [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $response->assertStatus(200);
        $this->assertTrue(Auth::check());
    }

    /**
     * login
     *
     * @return void
     */
    public function test_login_if_notauthenticated()
    {
        Pref::factory(['id' => 1])->create();
        User::create(
            [
                'id' => 1,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'birthday' => $this->birthday,
                'pref_id' => 1,
                'introduction' => $this->introduction,
                'twitter_url' => $this->twitter_url,
            ]
        );
        $this->assertFalse(Auth::check());
        $response = $this->post(route('login'), [
            'email' => 'test1@email.com',
            'password' => 'password2',
        ]);
        $response->assertStatus(401);
        $this->assertFalse(Auth::check());
    }
}
