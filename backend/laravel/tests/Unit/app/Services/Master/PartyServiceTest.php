<?php

namespace Tests\Unit\app\Services\Master;

use App\Services\Master\PartyService;
use App\Models\User;
use App\Models\Party;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PartyServiceTest extends TestCase
{
    //todo:テスト修正
    /**
     * fetchPickUpParties
     *
     * @return void
     */
    public function test_fetch_pick_up_parties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00'],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-17 10:00:00'],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-15 10:00:00'],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-14 10:00:00'],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-12 10:00:00'],
        ));

        $service = new PartyService();

        $actual = $service->fetchPickUpParties();
        $this->assertSame($actual, [
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4],
        ]);
    }

    /**
     * fetchPickUpCreatedParties
     *
     * @return void
     */
    public function test_fetch_pick_up_created_parties()
    {
        Carbon::setTestNow('2023-05-20 10:00:00');
        User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        Party::factory(5)->create(new Sequence(
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1, 'created_at' => '2023-05-19 10:00:00', 'leader_id' => 1],
            ['id' => 2, 'theme' => 'theme_2', 'place' => 'place_2', 'due_max' => 2, 'created_at' => '2023-05-18 10:00:00', 'leader_id' => 2],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3, 'created_at' => '2023-05-17 10:00:00', 'leader_id' => 1],
            ['id' => 4, 'theme' => 'theme_4', 'place' => 'place_4', 'due_max' => 4, 'created_at' => '2023-05-16 10:00:00', 'leader_id' => 2],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5, 'created_at' => '2023-05-15 10:00:00', 'leader_id' => 1],
        ));

        $service = new PartyService();
        $actual = $service->fetchPickUpCreatedParties(1);
        $this->assertSame($actual, [
            ['id' => 1, 'theme' => 'theme_1', 'place' => 'place_1', 'due_max' => 1],
            ['id' => 3, 'theme' => 'theme_3', 'place' => 'place_3', 'due_max' => 3],
            ['id' => 5, 'theme' => 'theme_5', 'place' => 'place_5', 'due_max' => 5],
        ]);
    }
}
