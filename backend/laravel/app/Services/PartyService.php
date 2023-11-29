<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Party;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PartyService
{
    /**
     * @param array $data
     * @param integer $user_id
     *
     * @return void
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
            'leader_id' => $user_id,
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
        $data['user_id'] = $party->leader->id;
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
        $this->createMessageGroup($party_id, $user->id);
    }

    /**
     * @param int $party_id
     *
     * @return bool
     */
    public function checkIfJoined(int $party_id): bool
    {
        $user = Auth::User();
        //todo:ロジックの変更をユニットテストに反映
        $query = Party::where('id', $party_id)->where(function ($query) use ($user) {
            $query->whereHas('users', function($q) use ($user){
                $q->where('id', $user->id);
            });
            $query->orWhere('leader_id', $user->id);
        });
        return $query->exists();
    }

    /**
     * @param int $party_id
     *
     * @return void
     */
    protected function createMessageGroup(int $party_id, int $user_id): void
    {
        $message_group = MessageGroup::create([
            'party_id' => $party_id,
        ]);
        $message_group->users()->attach($user_id);
        $party = Party::find($party_id);
        $user = User::find($user_id);
        Message::create([
            'user_id' => $party->leader->id,
            'message_group_id' => $message_group->id,
            'content' => $user->name.'さんが参加しました、よろしくお願いします!!',
        ]);
    }
}
