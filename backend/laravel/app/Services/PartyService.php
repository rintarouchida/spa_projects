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

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function getdata(int $party_id): array
    {
        $party = Party::find($party_id);
        $data = [];
        $data['id'] = $party->id;
        $data['theme'] = $party->theme;
        $data['place'] = $party->place;
        $data['due_max'] = $party->due_max;
        $data['user_name'] = $party->user->name;
        $data['introduction'] = $party->introduction;
        $data['due_date'] = $party->due_date;
        foreach($party->tags as $index => $tag) {
            $data['tags'][$index] = $tag->name;
        }

        return $data;
    }
}
