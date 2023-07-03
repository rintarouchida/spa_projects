<?php

namespace Tests\Feature\Controllers;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
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

    /**
    * getData
    *
    * @return void
    */
    public function test_getData()
    {
        Pref::factory(['id' => 1])->create();
        User::factory(['id' => 1, 'name' => 'ユーザー1'])->create();
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));
        Party::factory([
            'id' => 1,
            'leader_id' => 1,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            ])->create()->tags()->attach([1, 2, 3]);
        $response = $this->get(route('party.get', 1));
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'id' => 1,
                'due_date' => '2023-05-08',
                'due_max' => 10,
                'introduction' => '詳細1',
                'place' => '東京都港区',
                'theme' => 'テストパーティー1',
                'user_name' => 'ユーザー1',
                'tags' => ['tag_1', 'tag_2', 'tag_3']
            ],
        );
    }
}
