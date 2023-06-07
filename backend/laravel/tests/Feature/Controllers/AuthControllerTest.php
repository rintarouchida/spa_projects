<?php

namespace Tests\Feature\Controllers;

use App\Models\Pref;
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
        ];

        $response = $this->post(route('register'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => $this->name,
        ]);
    }
}
