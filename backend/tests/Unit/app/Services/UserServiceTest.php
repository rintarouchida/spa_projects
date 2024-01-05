<?php

namespace Tests\Unit\app\Services;

use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{

    /**
     * getUser
     *
     * @test
     * @return void
     */
    public function getUser()
    {
        $user = User::factory(['id' => 1])->create();
        $service = new UserService();

        $actual = $service->getUser($user->id);
        $this->assertSame($actual->id, 1);
    }
}
