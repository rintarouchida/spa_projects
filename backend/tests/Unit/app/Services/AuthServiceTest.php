<?php

namespace Tests\Unit\app\Services;

use App\Models\Pref;
use App\Models\User;
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
        $this->assertDatabaseMissing('users', [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $mockAuthService = $this->getMockBuilder(AuthService::class)
            ->setMethods(['createS3Image'])
            ->getMock();
        $mockAuthService->expects($this->once())
            ->method('createS3Image')
            ->will($this->returnValue($this->mock_image));
        $mockAuthService->register($data);

        $this->assertDatabaseHas('users', [
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->mock_image,
        ]);
    }

    /**
     * update
     *
     * @return void
     */
    public function test_update()
    {
        Pref::factory(['id' => 1])->create();
        $user = User::factory([
            'id' => 1,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
        ])->create();

        $data = [
            'name' => 'テスト二郎',
            'email' => 'test2@email.com',
            'image' => 's3_2_test.jpg'
        ];

        $this->assertDatabaseMissing('users', $data);

        $mockAuthService = $this->getMockBuilder(AuthService::class)
            ->setMethods(['createS3Image'])
            ->getMock();
        $mockAuthService->expects($this->once())
            ->method('createS3Image')
            ->will($this->returnValue('s3_2_test.jpg'));
        $mockAuthService->update($user, $data);

        $this->assertDatabaseHas('users', $data);
    }
}
