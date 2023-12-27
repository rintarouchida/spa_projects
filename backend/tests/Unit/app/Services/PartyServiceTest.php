<?php

namespace Tests\Unit\app\Services;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
use App\Models\MessageGroup;
use App\Services\PartyService;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Database\Eloquent\Factories\Sequence;
use ReflectionMethod;
use Tests\TestCase;

class PartyServiceTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Pref::factory(['id' => 1])->create();
        $this->user = User::factory(['id' => 1, 'name' => 'ユーザー1', 'pref_id' => 1])->create();
        Config::set('filesystems.disks.s3.url', 'https://test');
    }

    /**
     * fetchPickUpParties
     *
     * @return void
     */
    public function test_fetch_pick_up_parties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        $user = User::find(1);
        User::factory(['id' => 2])->create();
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00', 'leader_id' => 1, 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-17 10:00:00', 'leader_id' => 2, 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-15 10:00:00', 'leader_id' => 2, 'image' => 'test3.jpg'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-14 10:00:00', 'leader_id' => 2, 'image' => 'test4.jpg'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-12 10:00:00', 'leader_id' => 2, 'image' => 'test5.jpg'],
        ));

        $user->parties()->attach(3);

        $service = new PartyService();

        $actual = $service->fetchPickUpParties(1);
        //(登録から1週間以内)かつ(自身が作成したものでない)かつ(自身がまだ参加していない)もくもく会が抽出される
        $this->assertSame($actual, [
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'image' => 'https://test/test2.jpg'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'image' => 'https://test/test4.jpg']
        ]);
    }

    /**
     * fetchPickUpCreatedParties
     *
     * @return void
     */
    public function test_fetch_pick_up_created_parties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        User::factory(['id' => 2])->create();
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00', 'leader_id' => 1, 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-18 10:00:00', 'leader_id' => 2, 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-17 10:00:00', 'leader_id' => 1, 'image' => 'test3.jpg'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-16 10:00:00', 'leader_id' => 2, 'image' => 'test4.jpg'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-15 10:00:00', 'leader_id' => 1, 'image' => 'test5.jpg'],
        ));

        $service = new PartyService();
        $actual = $service->fetchPickUpCreatedParties(1);
        $this->assertSame($actual, [
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'image' => 'https://test/test1.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'image' => 'https://test/test3.jpg'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'image' => 'https://test/test5.jpg'],
        ]);
    }

    /**
     * fetchPickUpParticipatedParties
     *
     * @return void
     */
    public function test_fetch_pick_up_participated_parties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');

        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00', 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-18 10:00:00', 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-17 10:00:00', 'image' => 'test3.jpg'],
        ));
        User::find(1)->parties()->attach([1, 2, 3]);

        Party::factory(2)->create(new Sequence(
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-16 10:00:00'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-15 10:00:00'],
        ));
        User::factory(['id' => 2])->create()->parties()->attach([4, 5]);

        $service = new PartyService();
        $actual = $service->fetchPickUpParticipatedParties(1);
        //紐ずくユーザーがもくもく会に参加しているのでdue_maxは一人減る
        $this->assertSame($actual, [
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 0, 'image' => 'https://test/test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 1, 'image' => 'https://test/test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 2, 'image' => 'https://test/test3.jpg'],
        ]);
    }

    /**
     * register
     *
     * @return void
     */
    public function test_register()
    {
        $this->assertDatabaseMissing('parties', [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'leader_id' => 1,
        ]);

        $this->assertTrue(true);
        $data = [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'leader_id' => 1,
            'tag_ids' => null,
            'image' => null
        ];

        $service = new PartyService();
        $service->register($data, $this->user->id);

        $this->assertDatabaseHas('parties', [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'leader_id' => 1,
        ]);

    }

    /**
     * getData
     *
     * @return void
     */
    public function test_getData()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));
        Party::factory([
            'id' => 1,
            'leader_id' => $this->user->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            'image' => 'test.jpg'
        ])->create()->tags()->attach([1, 2, 3]);

        $service = new PartyService();
        $actual = $service->getdata(1);

        $this->assertSame($actual, [
            'id' => 1,
            'theme' => 'テストパーティー1',
            'place' => '東京都港区',
            'due_max' => 10,
            'user_name' => 'ユーザー1',
            'user_id'   => 1,
            'introduction' => '詳細1',
            'due_date' => '2023-05-08',
            'image' => 'https://test/test.jpg',
            'tags' => ['tag_1', 'tag_2', 'tag_3'],
        ]);
    }

    /**
     * join
     *
     * @return void
     */
    public function test_join()
    {
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

        $this->assertFalse(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $this->user->id)->exists());

        $this->actingAs($this->user);
        $service = new PartyService();
        $service->join($party->id);
        $this->assertTrue(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $this->user->id)->exists());
    }

    /**
     * checkIfJoined
     *
     * @return void
     */
    public function test_checkIfJoined()
    {
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

        $this->actingAs($this->user);
        $service = new PartyService();

        $this->assertFalse($service->checkIfJoined($party->id));

        $this->user->parties()->attach($party->id);
        $this->assertTrue($service->checkIfJoined($party->id));
    }

    /**
     * createMessageGroup
     *
     * @return void
     */
    public function test_create_message_group()
    {
        $leader = User::factory()->create();
        $party = Party::factory([
            'id'        => 1,
            'leader_id' => $leader->id,
        ])->create();

        $this->assertDatabaseMissing('message_groups', [
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseMissing('user_message_group', [
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseMissing('messages', [
            'user_id' => $leader->id,
            'content' => 'ユーザー1さんが参加しました、よろしくお願いします!!',
        ]);

        $method = new ReflectionMethod(PartyService::class, 'createMessageGroup');
        $method->setAccessible(true);
        $method->invoke(new PartyService, $this->user->id, $party->id);

        $this->assertDatabaseHas('message_groups', [
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseHas('user_message_group', [
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseHas('messages', [
            'user_id' => $leader->id,
            'content' => 'ユーザー1さんが参加しました、よろしくお願いします!!',
        ]);
    }
}
