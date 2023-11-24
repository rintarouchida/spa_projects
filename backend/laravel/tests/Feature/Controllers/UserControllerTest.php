<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Pref;
use App\Services\UserService;
use Carbon\Carbon;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getData()
    {
        Carbon::setTestNow('2023-11-15 00:00:00');
        Pref::factory(['id' => 1, 'name' => 'pref_1'])->create();
        User::factory([
            'id'           => 1,
            'name'         => 'ユーザー1',
            'birthday'     => '2000-01-01 00:00:00',
            'introduction' => 'introduction1',
            'pref_id'      => 1,
        ])->create();

        $response = $this->get(route('user.get', 1));
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'name'         => 'ユーザー1',
                'old'          => 23,
                'pref_name'    => 'pref_1',
                'introduction' => 'introduction1',
            ],
        );
    }
}
