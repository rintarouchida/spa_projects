<?php

namespace Tests\Feature\Controllers\Master;

use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartyControllerTest extends TestCase
{
    //todo:テスト修正
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

    /**
    * indexCreated
    *
    * @return void
    */
    public function test_index_created()
    {
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);
        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1', 'leader_id' => 1],
            ['id' => 2, 'theme' => 'party_2', 'leader_id' => 2],
            ['id' => 3, 'theme' => 'party_3', 'leader_id' => 1],
        ));

        $response = $this->get(route('created_parties', $user->id));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'theme' => 'party_1'],
            ['id' => 3, 'theme' => 'party_3'],
        ]);
    }
}
