<?php

namespace Tests\Unit\app\Services;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
use App\Models\MessageGroup;
use App\Services\PartyService;
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

        $this->user = User::factory(['id' => 1, 'name' => 'ユーザー1'])->create();
    }

    /**
     * A basic unit test example.
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

        Pref::factory(['id' => 1])->create();
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
        Pref::factory(['id' => 1])->create();
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
            'tags' => ['tag_1', 'tag_2', 'tag_3']
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
        Pref::factory(['id' => 1])->create();
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
        Pref::factory(['id' => 1])->create();
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