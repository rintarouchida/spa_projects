<?php

namespace Tests\Feature\Controllers;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
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
    * @return void
    */
    public function test_index()
    {
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);

        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1', 'leader_id' => 1],
            ['id' => 2, 'theme' => 'party_2', 'leader_id' => 2],
            ['id' => 3, 'theme' => 'party_3', 'leader_id' => 2],
            ['id' => 4, 'theme' => 'party_4', 'leader_id' => 2],
            ['id' => 5, 'theme' => 'party_5', 'leader_id' => 2],
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
    * @return void
    */
    public function test_index_created()
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
    * @return void
    */
    public function test_index_participated()
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
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 0],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 1],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 2],
        ]);
    }

    /**
    * register
    *
    * @return void
    */
    public function test_register()
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
            'due_date' => '2023-12-28 10:00:00',
            'introduction' => '詳細1'
        ]);

        $data = [
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-12-28 10:00:00',
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
            'due_date' => '2023-12-28 00:00:00',
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
            'image' => 'test.jpg',
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
                'user_id'   => 1,
                'tags' => ['tag_1', 'tag_2', 'tag_3'],
                'image' => '/test.jpg'
            ],
        );
    }

    /**
    * checkIfJoined
    *
    * @return void
    */
    public function test_checkIfJoined()
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
            'due_date' => '2023-05-08 00:00:00',
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
    * @return void
    */
    public function test_join()
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
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            ])->create();

        $this->assertFalse(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $user->id)->exists());
        $response = $this->post(route('party.join'), ['party_id' => $party->id]);
        $response->assertStatus(200);
        $this->assertTrue(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $user->id)->exists());
    }

    /**
    * search
    *
    * @return void
    */
    public function test_search()
    {
        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();
        Config::set('filesystems.disks.s3.url', 'https://test');

        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        Carbon::setTestNow('2023-10-29 10:00:00');

        Tag::factory(4)->create(new Sequence(
            ['id' => 1, 'name' => 'タグ1'],
            ['id' => 2, 'name' => 'タグ2'],
            ['id' => 3, 'name' => 'タグ3'],
            ['id' => 4, 'name' => 'タグ4']
        ));

        Party::factory(['id' => 1, 'theme' => 'theme_1', 'pref_id' => 1, 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-10-29 10:00:00', 'leader_id' => 2, 'image' => 'test.jpg'])->create()->tags()->attach([1,2]);

        $data = [
            'pref_id'  => 1,
            'tag_ids'   => [2, 3],
            'keyword' => 'place_1'
        ];

        $this->actingAs($user);
        $response = $this->get(route('party.search', $data));
        $response->assertStatus(200);
        $response->assertExactJson([
            [
                'id' => 1,
                'theme' => 'theme_1',
                'place' => 'place_1',
                'image' => 'https://test/test.jpg',
                'due_max' => 1,
                'tags' => [
                    ['name' => 'タグ1'],
                    ['name' => 'タグ2']
                ]
            ],
        ]);
    }
}
