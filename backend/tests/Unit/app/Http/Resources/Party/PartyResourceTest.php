<?php

namespace Tests\Unit\Http\Resources\Party;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PartyResourceTest extends TestCase
{
    public function testPartyResource()
    {
        Config::set('filesystems.disks.s3.url', 'https://example-s3.com');

        $leader = User::factory()->create();
        User::factory(['id' => 11])->create();
        User::factory(['id' => 12])->create();
        $tags = Tag::factory()->count(3)->create();
        $pref = Pref::factory()->create();
        $party = Party::factory()->create([
            'leader_id'   => $leader->id,
            'image'       => 'test.jpg',
            'pref_id'     => $pref->id,
            'due_max'     => 10,
        ]);
        $party->tags()->attach($tags);
        $party->users()->attach([11, 12]);

        // PartyResource オブジェクトの生成
        $partyResource = new PartyResource($party);

        // 配列に変換
        $partyArray = $partyResource->toArray(null);

        $party->load('leader', 'tags');

        // 期待される値を設定
        $expectedArray = [
            'id' => $party->id,
            'theme' => $party->theme,
            'place' => $party->place,
            'due_max' => 10,
            'now_participated_num' => 2,
            'user_name' => $leader->name,
            'user_id' => $leader->id,
            'introduction' => $party->introduction,
            'due_date' => $party->due_date,
            'image' => 'https://example-s3.com/test.jpg',
            'tags' => $tags->pluck('name'),
            'tag_ids' => $tags->pluck('id'),
            'pref_id' => $pref->id,
            'pref_name' => $pref->name,
            'cancelable' => true,
            'cancelable_hours' => 72
        ];

        $this->assertEquals($expectedArray, $partyArray);
    }
}
