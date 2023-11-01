<?php

namespace Tests\Feature\Controllers;

use App\Models\Party;
use App\Models\Pref;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    /**
    * index
    *
    * @return void
    */
    public function test_index()
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

        $response = $this->get(route('search'), $data);
        $response->assertStatus(200);
        $response->assertExactJson([
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1,
            'tags' => [
                ['name' => 'タグ1'],
                ['name' => 'タグ2']
            ]],
        ]);
    }
}
