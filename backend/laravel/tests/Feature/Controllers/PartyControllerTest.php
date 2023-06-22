<?php

namespace Tests\Feature\Controllers;

use App\Models\Pref;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartyControllerTest extends TestCase
{
    /**
    * index
    *
    * @return void
    */
    public function test_register()
    {
        $user     = User::factory(['id' => 1])->create();
        $this->actingAs($user);
        Pref::factory(['id' => 1])->create();
        $this->assertDatabaseMissing('parties', [
            'theme' => 'テストパーティー1',
            'place' => '東京都港区',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1'
        ]);

        $data = [
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            'tag_ids' => null,
        ];
        $response = $this->post(route('party.register'), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('parties', [
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1'
        ]);
    }
}
