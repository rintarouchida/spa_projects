<?php

namespace App\Services\Master;

use App\Models\User;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PartyService
{
    /**
     * @param int $auth_id
     *
     * @return array
     */
    public function fetchPickUpParties(int $auth_id): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $parties = Party::with(['tags', 'users'])->whereDate('created_at', '>=', $sevendays)
        ->where('leader_id', '!=', $auth_id)
        ->where(function ($query) use ($auth_id) {
            $query->whereHas('users', function ($q) use ($auth_id) {
                $q->whereNotIn('id', [$auth_id]);
            })
            ->orWhereDoesntHave('users');
        })->get();

        $data = [];

        foreach ($parties as $key => $party) {
            $data[$key]['id']      = $party->id;
            $data[$key]['theme']   = $party->theme;
            $data[$key]['place']   = $party->place;
            $data[$key]['due_max'] = $party->due_max - count($party->users);
            $data[$key]['image']   = config('filesystems.disks.s3.url').'/'.$party->image;
            foreach ($party->tags as $index => $tag) {
                $data[$key]['tags'][$index]['name'] = $tag->name;
            }
        }
        return $data;
    }

    /**
     * @param int $auth_id
     *
     * @return array
     */
    public function fetchPickUpCreatedParties(int $auth_id): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $parties = Party::with(['tags', 'users'])->whereDate('created_at', '>=', $sevendays)
        ->where('leader_id', $auth_id)
        ->get();

        $data = [];

        foreach ($parties as $key => $party) {
            $data[$key]['id']      = $party->id;
            $data[$key]['theme']   = $party->theme;
            $data[$key]['place']   = $party->place;
            $data[$key]['due_max'] = $party->due_max - count($party->users);
            $data[$key]['image']   = config('filesystems.disks.s3.url').'/'.$party->image;
            foreach ($party->tags as $index => $tag) {
                $data[$key]['tags'][$index]['name'] = $tag->name;
            }
        }
        return $data;
    }

    /**
     * @param int $auth_id
     *
     * @return array
     */
    public function fetchPickUpParticipatedParties(int $auth_id): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $parties = Party::with(['tags', 'users'])->whereDate('created_at', '>=', $sevendays)
        ->whereHas('users', function ($q) use ($auth_id) {
            $q->whereIn('id', [$auth_id]);
        })->get();

        $data = [];

        foreach ($parties as $key => $party) {
            $data[$key]['id']      = $party->id;
            $data[$key]['theme']   = $party->theme;
            $data[$key]['place']   = $party->place;
            $data[$key]['due_max'] = $party->due_max - count($party->users);
            $data[$key]['image']   = config('filesystems.disks.s3.url').'/'.$party->image;
            foreach ($party->tags as $index => $tag) {
                $data[$key]['tags'][$index]['name'] = $tag->name;
            }
        }
        return $data;
    }
}
//todo:image分のテスト追記
