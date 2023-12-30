<?php

namespace Tests\Unit\Http\Resources\Party;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
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
        $tags = Tag::factory()->count(3)->create();
        $party = Party::factory()->create([
            'leader_id' => $leader->id,
            'image'     => 'test.jpg',
        ]);
        $party->tags()->attach($tags);

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
            'due_max' => $party->due_max - $party->users->count(),
            'user_name' => $leader->name,
            'user_id' => $leader->id,
            'introduction' => $party->introduction,
            'due_date' => $party->due_date,
            'image' => 'https://example-s3.com/test.jpg',
            'tags' => $tags->pluck('name'),
        ];

        $this->assertEquals($expectedArray, $partyArray);
    }
}
