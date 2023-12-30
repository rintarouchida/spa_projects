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
     * @return void
     */
    public function test_getUser()
    {
        $user = User::factory(['id' => 1])->create();
        $service = new UserService();

        $actual = $service->getUser($user->id);
        $this->assertSame($actual->id, 1);
    }
}
