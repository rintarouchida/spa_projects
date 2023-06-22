<?php

namespace Tests\Unit\app\Services;

use App\Models\Pref;
use App\Services\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    protected string $name = 'テスト太郎';
    protected string $email = 'test@email.com';
    protected string $password = 'password';
    protected string $birthday = '2023-05-08 00:00:00';
    protected string $introduction = 'こんにちは';
    protected string $twitter_url = 'https://twitter.com';

    /**
     * register
     *
     * @return void
     */
    public function test_register()
    {
        Pref::factory(['id' => 1])->create();
        $service = new AuthService();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'birthday' => $this->birthday,
            'pref_id' => 1,
            'introduction' => $this->introduction,
            'twitter_url' => $this->twitter_url,
        ];
        $this->assertDatabaseMissing('users', [
            'name' => $this->name,
        ]);
        $service->register($data);

        $this->assertDatabaseHas('users', [
            'name' => $this->name,
        ]);
    }
}
