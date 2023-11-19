<?php

namespace Tests\Feature\Controllers\Master;

use App\Models\Party;
use App\Models\User;
use Carbon\Carbon;
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
        $user = User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        $this->actingAs($user);

        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'party_1', 'leader_id' => 1],
            ['id' => 2, 'theme' => 'party_2', 'leader_id' => 2],
            ['id' => 3, 'theme' => 'party_3', 'leader_id' => 2],
            ['id' => 4, 'theme' => 'party_4', 'leader_id' => 2],
            ['id' => 5, 'theme' => 'party_5', 'leader_id' => 2],
        ));

        $user->parties()->attach([2, 3]);

        $response = $this->get(route('parties'));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 4, 'theme' => 'party_4'],
            ['id' => 5, 'theme' => 'party_5'],
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

    /**
    * indexParticipated
    *
    * @return void
    */
    public function test_index_participated()
    {
        $user = User::factory(['id' => 1])->create();
        $this->actingAs($user);

        Carbon::setTestNow('2023-05-20 10:00:00');

        Party::factory(3)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-18 10:00:00'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-17 10:00:00'],
        ));
        $user->parties()->attach([1, 2, 3]);

        Party::factory(2)->create(new Sequence(
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-16 10:00:00'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-15 10:00:00'],
        ));
        User::factory(['id' => 2])->create()->parties()->attach([4, 5]);

        $response = $this->get(route('participated_parties', $user->id));

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 0],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 1],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 2],
        ]);
    }
}
