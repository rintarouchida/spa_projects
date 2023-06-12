<?php

namespace App\Services;

use App\Models\User;
use App\Models\Party;

class PartyService
{
    /**
     * @return array
     */
    public function register(array $data, int $user_id): void
    {
        $party = Party::create([
            'theme' => $data['theme'],
            'place' => $data['place'],
            'due_max' => $data['due_max'],
            'due_date' => $data['due_date'],
            'introduction' => $data['introduction'],
            'pref_id' => $data['pref_id'],
            'user_id' => $user_id,
        ]);

        $party->tags()->attach($data['tag_ids']);
    }
}
