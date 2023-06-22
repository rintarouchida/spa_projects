<?php

namespace Tests\Unit\app\Services;

use App\Models\Pref;
use App\Models\User;
use App\Services\PartyService;
use Tests\TestCase;

class PartyServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_register()
    {
        $this->assertDatabaseMissing('parties', [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'user_id' => 1,
        ]);

        $user = User::factory(['id' => 1])->create();
        Pref::factory(['id' => 1])->create();
        $this->assertTrue(true);
        $data = [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'user_id' => 1,
            'tag_ids' => null,
        ];

        $service = new PartyService();
        $service->register($data, $user->id);

        $this->assertDatabaseHas('parties', [
            'theme' => 'theme_1',
            'place' => 'place_1',
            'due_max' => 1,
            'due_date' => '2023-05-12',
            'introduction' => 'introduction_1',
            'pref_id' => 1,
            'user_id' => 1,
        ]);

    }
}
