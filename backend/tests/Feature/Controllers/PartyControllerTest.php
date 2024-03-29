<?php

namespace Tests\Feature\Controllers;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
use App\Models\MessageGroup;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartyControllerTest extends TestCase
{
    /**
    * index
    *
    * test
    * @return void
    */
    public function index()
    {
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);

        Carbon::setTestNow('2024-01-01 00:00:00');

        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1', 'leader_id' => 1, 'event_date' => '2024-01-04 00:00:00'],
            ['id' => 2, 'theme' => 'party_2', 'leader_id' => 2, 'event_date' => '2024-01-04 00:00:00'],
            ['id' => 3, 'theme' => 'party_3', 'leader_id' => 2, 'event_date' => '2024-01-04 00:00:00'],
            ['id' => 4, 'theme' => 'party_4', 'leader_id' => 2, 'event_date' => '2024-01-04 00:00:00'],
            ['id' => 5, 'theme' => 'party_5', 'leader_id' => 2, 'event_date' => '2024-01-04 00:00:00'],
        ));

        $user->parties()->attach([2, 3]);

        $response = $this->get(route('party.index'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 4, 'theme' => 'party_4'],
            ['id' => 5, 'theme' => 'party_5'],
        ]);
    }

    /**
    * indexCreated
    *
    * @test
    * @return void
    */
    public function indexCreated()
    {
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);
        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1', 'leader_id' => 1],
            ['id' => 2, 'theme' => 'party_2', 'leader_id' => 2],
            ['id' => 3, 'theme' => 'party_3', 'leader_id' => 1],
        ));

        $response = $this->get(route('party.index_created'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'theme' => 'party_1'],
            ['id' => 3, 'theme' => 'party_3'],
        ]);
    }

    /**
    * indexParticipated
    *
    * @test
    * @return void
    */
    public function indexParticipated()
    {
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);

        Carbon::setTestNow('2023-05-20 10:00:00');

        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-18 10:00:00'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-17 10:00:00'],
        ));
        $user->parties()->attach([1, 2, 3]);

        Party::factory(2)->create(new Sequence(
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-16 10:00:00'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-15 10:00:00'],
        ));
        User::factory(['id' => 2])->create()->parties()->attach([4, 5]);

        $response = $this->get(route('party.index_participated'));
        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'now_participated_num' => 1],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'now_participated_num' => 1],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'now_participated_num' => 1],
        ]);
    }

    /**
    * register
    *
    * @test
    * @return void
    */
    public function register()
    {
        Carbon::setTestNow('2023-12-27 10:00:00');
        Pref::factory(['id' => 1])->create();
        $user     = User::factory(['id' => 1])->create();
        $this->actingAs($user);
        $this->assertDatabaseMissing('parties', [
            'theme' => 'テストパーティー1',
            'place' => '東京都港区',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-28 10:00:00',
            'introduction' => '詳細1'
        ]);

        $data = [
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2024-01-10 10:00:00',
            'introduction' => '詳細1',
            'tag_ids' => null,
            'image'   => null
        ];
        $response = $this->put(route('party.register'), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('parties', [
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2024-01-10',
            'introduction' => '詳細1'
        ]);
    }

    /**
    * getData
    *
    * @test
    * @return void
    */
    public function getData()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');

        Pref::factory(['id' => 1, 'name' => 'pref_1'])->create();
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
            'event_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            'image' => 'test.jpg',
            ])->create()->tags()->attach([1, 2, 3]);
        $response = $this->get(route('party.get', 1));
        $response->assertStatus(200);
        $response->assertJson(
            [
                'id' => 1,
                'event_date' => '2023-05-08',
                'now_participated_num' => 0,
                'due_max' => 10,
                'introduction' => '詳細1',
                'place' => '東京都港区',
                'theme' => 'テストパーティー1',
                'user_name' => 'ユーザー1',
                'user_id'   => 1,
                'tags' => ['tag_1', 'tag_2', 'tag_3'],
                'tag_ids' => [1, 2, 3],
                'pref_name' => 'pref_1',
                'pref_id' => 1,
                'image' => 'https://test/test.jpg'
            ],
        );
    }

    /**
    * checkIfJoined
    *
    * @test
    * @return void
    */
    public function checkIfJoined()
    {
        $user     = User::factory(['id' => 1])->create();
        $this->actingAs($user);

        Pref::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();

        $party = Party::factory([
            'id' => 1,
            'leader_id' => 2,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            ])->create();

        $response = $this->get(route('party.check_if_joined', 1));
        $response->assertStatus(200);
        $response->assertExactJson(
            ['result' => false],
        );
        $user->parties()->attach(1);
        $response = $this->get(route('party.check_if_joined', 1));
        $response->assertStatus(200);
        $response->assertExactJson(
            ['result' => true],
        );
    }

    /**
    * join
    *
    * @test
    * @return void
    */
    public function join()
    {
        $user     = User::factory(['id' => 1])->create();
        $this->actingAs($user);

        Pref::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();

        $party = Party::factory([
            'id' => 1,
            'leader_id' => 2,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            ])->create();

            MessageGroup::factory(['party_id' => $party->id])->create();

        $this->assertFalse(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $user->id)->exists());
        $response = $this->post(route('party.join'), ['party_id' => $party->id]);
        $response->assertStatus(200);
        $this->assertTrue(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $user->id)->exists());
    }

    /**
    * search
    *
    * @test
    * @return void
    */
    public function search()
    {
        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        Config::set('filesystems.disks.s3.url', 'https://test');
        Carbon::setTestNow('2023-10-29 10:00:00');

        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();

        Tag::factory(4)->create(new Sequence(
            ['id' => 1, 'name' => 'タグ1'],
            ['id' => 2, 'name' => 'タグ2'],
            ['id' => 3, 'name' => 'タグ3'],
            ['id' => 4, 'name' => 'タグ4']
        ));

        Party::factory(['id' => 1, 'theme' => 'theme_1', 'pref_id' => 1, 'place' => 'place_1', 'due_max' => 1, 'event_date' => '2023-10-30 10:00:00', 'leader_id' => 2, 'image' => 'test.jpg'])->create()->tags()->attach([1,2]);

        $data = [
            'pref_id'  => 1,
            'tag_ids'   => [2, 3],
            'keyword' => 'place_1'
        ];

        $this->actingAs($user);
        $response = $this->get(route('party.search', $data));
        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => 1,
                'theme' => 'theme_1',
                'place' => 'place_1',
                'image' => 'https://test/test.jpg',
                'due_max' => 1,
                'tags' => [
                    'タグ1',
                    'タグ2'
                ],
            ],
        ]);
    }

    /**
    * edit
    *
    * @test
    * @return void
    */
    public function edit()
    {
        Config::set('filesystems.disks.s3.url', 'https://test');

        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));

        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        $user = User::factory(['id' => 1, 'name' => 'ユーザー1'])->create();
        $this->actingAs($user);
        Carbon::setTestNow('2023-12-29 08:00:00');
        Party::factory([
            'id' => 1,
            'leader_id' => $user->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-30 10:00:00',
            'introduction' => '詳細1',
            'created_at' => '2023-12-29 10:00:00',
            'image' => 'test.jpg',
        ])->create()->tags()->attach([1, 2]);

        $response = $this->get(route('party.edit', 1));

        $response->assertStatus(200);

        $response->assertJson(
            [
                'id' => 1,
                'event_date' => '2023-12-30',
                'due_max' => 10,
                'introduction' => '詳細1',
                'place' => '東京都港区',
                'theme' => 'テストパーティー1',
                'user_name' => 'ユーザー1',
                'user_id'   => 1,
                'tags' => ['tag_1', 'tag_2'],
                'image' => 'https://test/test.jpg'
            ],
        );
    }

    /**
    * update
    *
    * @test
    * @return void
    */
    public function update()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));

        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);
        Carbon::setTestNow('2023-12-29 08:00:00');
        $party = Party::factory([
            'id' => 1,
            'leader_id' => $user->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-30 10:00:00',
            'introduction' => '詳細1',
            'created_at' => '2023-12-29 10:00:00',
        ])->create();

        $data = [
            'place' => '東京都千代田区',
            'theme' => 'テストパーティー2',
            'pref_id' => 2,
            'due_max' => 20,
            'now_participated_num' => 10,
            'event_date' => '2024-01-30 00:00:00',
            'introduction' => '詳細2',
            'tag_ids' => [1, 2],
            'image' => null,
        ];

        $response = $this->put(route('party.update', 1), $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('parties', [
            'id'    => 1,
            'theme' => 'テストパーティー2',
            'place' => '東京都千代田区',
            'due_max' => 20,
            'event_date' => '2024-01-30 00:00:00',
            'introduction' => '詳細2',
            'pref_id' => 2,
        ]);
    }

    /**
    * update
    *
    * @test
    * @return void
    */
    public function updateIfInvalid()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));

        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);
        Carbon::setTestNow('2023-12-29 08:00:00');
        $party = Party::factory([
            'id' => 1,
            'leader_id' => 2,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-30 10:00:00',
            'introduction' => '詳細1',
            'created_at' => '2023-12-29 10:00:00',
        ])->create();

        $data = [
            'place' => '東京都千代田区',
            'theme' => 'テストパーティー2',
            'pref_id' => 2,
            'due_max' => 20,
            'now_participated_num' => 10,
            'event_date' => '2024-12-29 08:00:00',
            'introduction' => '詳細2',
            'tag_ids' => [1, 2],
            'image' => null,
        ];

        $response = $this->put(route('party.update', 1), $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'ログインユーザー以外が作成したもくもく会の内容は更新できません。']);
    }

    /**
    * update
    *
    * @return void
    */
    public function testUpdateIfAfterOnDday()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));

        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);
        Carbon::setTestNow('2023-12-28 08:00:00');
        $party = Party::factory([
            'id' => 1,
            'leader_id' => $user->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2024-01-30 10:00:00',
            'introduction' => '詳細1',
            'created_at' => '2023-12-26 10:00:00',
        ])->create();

        $data = [
            'place' => '東京都千代田区',
            'theme' => 'テストパーティー2',
            'pref_id' => 2,
            'due_max' => 20,
            'now_participated_num' => 10,
            'event_date' => '2024-01-31 10:00:00',
            'introduction' => '詳細2',
            'tag_ids' => [1, 2],
            'image' => null,
        ];

        $response = $this->put(route('party.update', 1), $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => '作成から24時間経過したもくもく会の内容は変更できません。']);
    }

    /**
    * cancel
    *
    * @test
    * @return void
    */
    public function cancel()
    {
        Pref::factory(['id' => 1])->create();
        $leader = User::factory(['id' => 1])->create();
        $cancel_user = User::factory(['id' => 2])->create();
        $party = Party::factory([
            'id' => 1,
            'leader_id' => $leader->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-28 00:00:00',
            'introduction' => '詳細1',
            'created_at' => '2023-12-20 08:00:00',
        ])->create();
        Carbon::setTestNow('2023-12-21 08:00:00');

        $cancel_user->parties()->attach($party->id);
        $message_group = MessageGroup::factory(['id' => 1, 'party_id' => $party->id])->create();
        $message_group->users()->attach($cancel_user->id);

        $this->assertDatabaseMissing('messages', [
            'user_id' => $leader->id,
            'message_group_id' => $message_group->id,
            'content' => $cancel_user->name.'さんが参加をキャンセルしました。',
        ]);
        $this->assertDatabaseHas('party_user', [
            'user_id' => $cancel_user->id,
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseHas('user_message_group', [
            'user_id' => $cancel_user->id,
            'message_group_id' => $message_group->id,
        ]);

        $this->actingAs($cancel_user);
        $response = $this->delete(route('party.cancel', $party->id));
        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'user_id' => $leader->id,
            'message_group_id' => $message_group->id,
            'content' => $cancel_user->name.'さんが参加をキャンセルしました。',
        ]);
        $this->assertDatabaseMissing('party_user', [
            'user_id' => $cancel_user->id,
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseMissing('user_message_group', [
            'user_id' => $cancel_user->id,
            'message_group_id' => $message_group->id,
        ]);
    }
}
