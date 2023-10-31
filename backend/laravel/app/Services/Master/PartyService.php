<?php

namespace App\Services\Master;

use App\Models\User;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PartyService
{
    /**
     * @return array
     */
    public function fetchPickUpParties(): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $parties = Party::with('tags')->whereDate('created_at', '>=', $sevendays)->get();

        $data = [];

        foreach ($parties as $key => $party) {
            $data[$key]['id'] = $party->id;
            $data[$key]['theme'] = $party->theme;
            $data[$key]['place'] = $party->place;
            $data[$key]['due_max'] = $party->due_max;
            foreach($party->tags as $index => $tag) {
                $data[$key]['tags'][$index]['name'] = $tag->name;
            }
        }
        return $data;
    }
}
