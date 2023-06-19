<?php

namespace Tests\Feature\Controllers\Master;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    /**
    * index
    *
    * @return void
    */
    public function test_index()
    {
        Tag::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ));

        $response = $this->get(route('tags'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'name' => 'tag_1'],
            ['id' => 2, 'name' => 'tag_2'],
            ['id' => 3, 'name' => 'tag_3'],
        ]);
    }
}
