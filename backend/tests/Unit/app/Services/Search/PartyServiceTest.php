<?php

namespace Tests\Unit\app\Services\Search;

namespace App\Services\Search;

use App\Models\Party;
use App\Models\Pref;
use App\Models\Tag;
use App\Services\Search\PartyService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use ReflectionMethod;
use Tests\TestCase;

class PartyServiceTest extends TestCase
{

    /**
     * fetchRecordByPrefId
     *
     * @return void
     */
    public function test_fetch_record_by_pref_id(): void
    {
        Pref::factory(['id' => 1])->create();
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
     * @return void
     */
    public function test_fetch_record_by_tags_id(): void
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
     * @return void
     */
    public function test_fetch_record_by_keyword(): void
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
     * @return void
     */
    public function test_search_parties(): void
    {
        Carbon::setTestNow('2023-10-29 10:00:00');

        Pref::factory(['id' => 1])->create();
        Pref::factory(['id' => 2])->create();

        Tag::factory(4)->create(new Sequence(
            ['id' => 1, 'name' => 'タグ1'],
            ['id' => 2, 'name' => 'タグ2'],
            ['id' => 3, 'name' => 'タグ3'],
            ['id' => 4, 'name' => 'タグ4']
        ));

        Party::factory(['id' => 1, 'theme' => 'theme_1', 'pref_id' => 1, 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-10-29 10:00:00'])->create()->tags()->attach([1,2]);

        $data = [
            'pref_id'  => 1,
            'tag_ids'   => [2, 3],
            'keyword' => 'place_1'
        ];

        $service = new PartyService();

        $actual = $service->searchParties($data);
        $this->assertSame([
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1,
            'tags' => [
                ['name' => 'タグ1'],
                ['name' => 'タグ2']
            ]],
        ], $actual);
    }
}
