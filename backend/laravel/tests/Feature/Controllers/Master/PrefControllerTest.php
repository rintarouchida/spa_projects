<?php

namespace Tests\Feature\Controllers\Master;

use App\Models\Pref;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PrefControllerTest extends TestCase
{
    /**
    * index
    *
    * @return void
    */
    public function test_index()
    {
        Pref::factory(3)->create(new Sequence(
            ['id' => 1, 'name' => 'pref_1'],
            ['id' => 2, 'name' => 'pref_2'],
            ['id' => 3, 'name' => 'pref_3'],
        ));

        $response = $this->get(route('prefs'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'name' => 'pref_1'],
            ['id' => 2, 'name' => 'pref_2'],
            ['id' => 3, 'name' => 'pref_3'],
        ]);
    }
}
