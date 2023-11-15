<?php

namespace Tests\Unit\app\Services;

use App\Models\User;
use App\Models\Pref;
use App\Services\UserService;
use Tests\TestCase;
use Carbon\Carbon;

class UserServiceTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2023-11-15 00:00:00');
        Pref::factory(['id' => 1, 'name' => 'pref_1'])->create();
        $this->user = User::factory([
            'id'           => 1,
            'name'         => 'ユーザー1',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction1',
            'pref_id'      => 1,
        ])->create();
    }

    /**
     * getData
     *
     * @return void
     */
    public function test_get_data()
    {
        $service = new UserService();

        $actual = $service->getData($this->user->id);
        $this->assertSame($actual, [
            'name'         => 'ユーザー1',
            'old'          => 23,
            'pref_name'    => 'pref_1',
            'introduction' => 'introduction1',
        ]);
    }
}
