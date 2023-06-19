<?php

namespace Tests\Feature\Controllers\Master;

use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartyControllerTest extends TestCase
{
    /**
    * index
    *
    * @return void
    */
    public function test_index()
    {
        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1'],
            ['id' => 2, 'theme' => 'party_2'],
            ['id' => 3, 'theme' => 'party_3'],
        ));

        $response = $this->get(route('parties'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'theme' => 'party_1'],
            ['id' => 2, 'theme' => 'party_2'],
            ['id' => 3, 'theme' => 'party_3'],
        ]);
    }
}
