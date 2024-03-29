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
     * @test
     * @return void
     */
    public function fetchPickUpParties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        $user = User::find(1);
        User::factory(['id' => 2])->create();
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'event_date' => '2023-05-24 10:00:00', 'leader_id' => 1, 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'event_date' => '2023-05-23 10:00:00', 'leader_id' => 2, 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'event_date' => '2023-05-22 10:00:00', 'leader_id' => 2, 'image' => 'test3.jpg'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'event_date' => '2023-05-21 10:00:00', 'leader_id' => 2, 'image' => 'test4.jpg'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'event_date' => '2023-05-20 10:00:00', 'leader_id' => 2, 'image' => 'test5.jpg'],
        ));

        $user->parties()->attach(3);

        $service = new PartyService();

        $actual = $service->fetchPickUpParties(1);
        //(開催日が翌日以降)かつ(自身が作成したものでない)かつ(自身がまだ参加していない)もくもく会が抽出される
        $this->assertSame($actual->pluck('id')->toArray(), [2, 4]);
    }

    /**
     * fetchPickUpCreatedParties
     *
     * @test
     * @return void
     */
    public function fetchPickUpCreatedParties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        User::factory(['id' => 2])->create();
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'event_date' => '2023-05-24 10:00:00', 'leader_id' => 1, 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'event_date' => '2023-05-23 10:00:00', 'leader_id' => 2, 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'event_date' => '2023-05-22 10:00:00', 'leader_id' => 1, 'image' => 'test3.jpg'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'event_date' => '2023-05-21 10:00:00', 'leader_id' => 2, 'image' => 'test4.jpg'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'event_date' => '2023-05-20 10:00:00', 'leader_id' => 1, 'image' => 'test5.jpg'],
        ));

        $service = new PartyService();
        $actual = $service->fetchPickUpCreatedParties(1);
        $this->assertSame($actual->pluck('id')->toArray(), [1, 3, 5]);
    }

    /**
     * fetchPickUpParticipatedParties
     *
     * @test
     * @return void
     */
    public function fetchPickUpParticipatedParties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');

        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'event_date' => '2023-05-24 10:00:00', 'image' => 'test1.jpg'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'event_date' => '2023-05-24 10:00:00', 'image' => 'test2.jpg'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'event_date' => '2023-05-24 10:00:00', 'image' => 'test3.jpg'],
        ));
        User::find(1)->parties()->attach([1, 2, 3]);

        Party::factory(2)->create(new Sequence(
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'event_date' => '2023-05-24 10:00:00'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'event_date' => '2023-05-24 10:00:00'],
        ));
        User::factory(['id' => 2])->create()->parties()->attach([4, 5]);

        $service = new PartyService();
        $actual = $service->fetchPickUpParticipatedParties(1);
        //紐ずくユーザーがもくもく会に参加しているのでdue_maxは一人減る
        $this->assertSame($actual->pluck('id')->toArray(), [1, 2, 3]);
    }

    /**
     * register
     *
     * @test
     * @return void
     */
    public function register()
    {
        $this->assertDatabaseMissing('parties', [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'event_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'leader_id' => 1,
        ]);

        $this->assertTrue(true);
        $data = [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'event_date' => '2023-05-12',
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
            'event_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'leader_id' => 1,
        ]);

    }

    /**
     * getParty
     *
     * ptesr
     * @return void
     */
    public function getParty()
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
            'event_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
            'image' => 'test.jpg'
        ])->create()->tags()->attach([1, 2, 3]);

        $service = new PartyService();
        $actual = $service->getParty(1);

        $this->assertSame($actual->id, 1);
    }

    /**
     * join
     *
     * @test
     * @return void
     */
    public function join()
    {
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

        $this->assertFalse(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $this->user->id)->exists());

        $this->actingAs($this->user);
        $service = new PartyService();
        $service->join($party->id);
        $this->assertTrue(DB::table('party_user')->where('party_id', $party->id)->where('user_id', $this->user->id)->exists());
    }

    /**
     * cancel
     *
     * @test
     * @return void
     */
    public function cancel()
    {
        $cancel_user = User::factory(['id' => 2])->create();
        $party = Party::factory([
            'id' => 1,
            'leader_id' => $this->user->id,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-05-08 00:00:00',
            'introduction' => '詳細1',
        ])->create();
        $cancel_user->parties()->attach($party->id);
        $message_group = MessageGroup::factory(['id' => 1, 'party_id' => $party->id])->create();
        $message_group->users()->attach($cancel_user->id);

        $this->assertDatabaseMissing('messages', [
            'user_id' => $this->user->id,
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

        $service = new PartyService();
        $service->cancel($party, $cancel_user->id);

        $this->assertDatabaseMissing('party_user', [
            'user_id' => $cancel_user->id,
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseMissing('user_message_group', [
            'user_id' => $cancel_user->id,
            'message_group_id' => $message_group->id,
        ]);

        $this->assertDatabaseHas('messages', [
            'user_id' => $this->user->id,
            'message_group_id' => $message_group->id,
            'content' => $cancel_user->name.'さんが参加をキャンセルしました。',
        ]);
    }

    /**
     * checkIfJoined
     *
     * @test
     * @return void
     */
    public function checkIfJoined()
    {
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

        $this->actingAs($this->user);
        $service = new PartyService();

        $this->assertFalse($service->checkIfJoined($party->id));

        $this->user->parties()->attach($party->id);
        $this->assertTrue($service->checkIfJoined($party->id));
    }

    /**
     * createMessageGroup
     *
     * @test
     * @return void
     */
    public function createMessageGroup()
    {
        $leader = User::factory(['id' => 2])->create();
        $party = Party::factory([
            'id'        => 1,
            'leader_id' => $leader->id,
        ])->create();

        $this->assertDatabaseMissing('message_groups', [
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseMissing('messages', [
            'user_id' => $leader->id,
            'content' => 'もくもく会を作成しました。',
        ]);

        $method = new ReflectionMethod(PartyService::class, 'createMessageGroup');
        $method->setAccessible(true);
        $method->invoke(new PartyService, $party->id, $leader->id);

        $this->assertDatabaseHas('message_groups', [
            'party_id' => $party->id,
        ]);
        $this->assertDatabaseHas('messages', [
            'user_id' => $leader->id,
            'content' => 'もくもく会を作成しました。',
        ]);
    }

    /**
     * fetchRecordByPrefId
     *
     * @test
     * @return void
     */
    public function fetchRecordByPrefIdd(): void
    {
        Pref::factory(['id' => 2])->create();

        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'pref_id' => 1],
            ['id' => 2, 'pref_id' => 2],
            ['id' => 3, 'pref_id' => 1],
        ));

        $method = new ReflectionMethod(PartyService::class, 'fetchRecordByPrefId');
        $method->setAccessible(true);
        $actual = $method->invoke(new PartyService, Party::query(), 1);
        $this->assertSame([1, 3], $actual->get()->pluck('id')->toArray());
    }

    /**
     * fetchRecordByTagId
     *
     * @test
     * @return void
     */
    public function fetchRecordByTagId(): void
    {
        Tag::factory(4)->create(new Sequence(
            ['id' => 1], ['id' => 2], ['id' => 3], ['id' => 4]
        ));

        Party::factory(['id' => 1])->create()->tags()->attach([1]);
        Party::factory(['id' => 2])->create()->tags()->attach([2, 3]);
        Party::factory(['id' => 3])->create()->tags()->attach([3, 4]);

        $method = new ReflectionMethod(PartyService::class, 'fetchRecordByTagId');
        $method->setAccessible(true);
        $actual = $method->invoke(new PartyService, Party::query(), 3);
        $this->assertSame([2, 3], $actual->get()->pluck('id')->toArray());
    }

    /**
     * fetchRecordByKeyword
     *
     * @test
     * @return void
     */
    public function fetchRecordByKeyword(): void
    {
        Party::factory(['id' => 1, 'theme'        => '山田'])->create();
        Party::factory(['id' => 2, 'place'        => '山田'])->create();
        Party::factory(['id' => 3, 'introduction' => '山田'])->create();
        Party::factory(['id' => 4, 'theme'        => '鈴木'])->create();
        Party::factory(['id' => 5, 'place'        => '鈴木'])->create();
        Party::factory(['id' => 6, 'introduction' => '鈴木'])->create();

        $method = new ReflectionMethod(PartyService::class, 'fetchRecordByKeyword');
        $method->setAccessible(true);
        $actual = $method->invoke(new PartyService, Party::query(), '山田');
        $this->assertSame([1, 2, 3], $actual->get()->pluck('id')->toArray());
    }

    /**
     * searchParties
     *
     * @test
     * @return void
     */
    public function searchParties(): void
    {
        Carbon::setTestNow('2023-10-29 10:00:00');

        Pref::factory(['id' => 2])->create();
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

        $service = new PartyService();

        $actual = $service->searchParties($data, $this->user->id);
        $this->assertSame([1], $actual->pluck('id')->toArray());
    }

    /**
     * update
     *
     * @test
     * @return void
     */
    public function update(): void
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'タグ1'],
            ['id' => 2, 'name' => 'タグ2'],
            ['id' => 3, 'name' => 'タグ3'],
        ));

        Pref::factory(['id' => 2])->create();
        $party = Party::factory([
            'id' => 1,
            'place' => '東京都港区',
            'theme' => 'テストパーティー1',
            'pref_id' => 1,
            'due_max' => 10,
            'event_date' => '2023-12-29 00:00:00',
            'introduction' => '詳細1',
        ])->create();

        $data = [
            'place' => '東京都千代田区',
            'theme' => 'テストパーティー2',
            'pref_id' => 2,
            'due_max' => 20,
            'event_date' => '2023-12-30 00:00:00',
            'introduction' => '詳細2',
            'tag_ids' => [1, 2],
            'image' => null,
        ];

        $service = new PartyService();
        $service->update($data, $party);

        $this->assertDatabaseHas('parties', [
            'id'    => 1,
            'theme' => 'テストパーティー2',
            'place' => '東京都千代田区',
            'due_max' => 20,
            'event_date' => '2023-12-30 00:00:00',
            'introduction' => '詳細2',
            'pref_id' => 2,
        ]);
    }
}
