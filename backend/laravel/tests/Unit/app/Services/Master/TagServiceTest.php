<?php

namespace Tests\Unit\app\Services\Master;

use App\Services\Master\TagService;
use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class TagServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_fetch_pick_up_tags()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));
        $service = new TagService();

        $actual = $service->fetchPickUpTags();

        $this->assertSame($actual, [
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ]);
    }
}
