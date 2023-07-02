<?php

namespace App\Services;

use App\Models\User;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;

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
        $data['user_name'] = $party->leader->name;
        $data['introduction'] = $party->introduction;
        $data['due_date'] = $party->due_date;
        foreach($party->tags as $index => $tag) {
            $data['tags'][$index] = $tag->name;
        }

        return $data;
    }

    /**
     * @param integer $party_id
     *
     * @return void
     */
    public function join(int $party_id): void
    {
        $user = Auth::User();
        $user->parties()->attach($party_id);
    }

    /**
     * @param int $party_id
     *
     * @return bool
     */
    public function checkIfJoined(int $party_id): bool
    {
        $user = Auth::User();
        $query = $user->whereHas('parties', function($query) use ($party_id){
            $query->where('id', $party_id);
        });
        return $query->exists();
    }
}
