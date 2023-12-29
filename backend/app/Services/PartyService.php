<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Party;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if (!is_null($data['image'])) {
            $this->registerImage($party, $data['image']);
        }

        $party->tags()->attach($data['tag_ids']);
    }

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function getdata(int $party_id): array
    {
        $party = Party::with(['leader', 'tags'])->find($party_id);
        $data = [];
        $data['id'] = $party->id;
        $data['theme'] = $party->theme;
        $data['place'] = $party->place;
        $data['due_max'] = $party->due_max;
        $data['user_name'] = $party->leader->name;
        $data['user_id'] = $party->leader->id;
        $data['introduction'] = $party->introduction;
        $data['due_date'] = $party->due_date;
        $data['image'] = config('filesystems.disks.s3.url').'/'.$party->image;
        foreach ($party->tags as $index => $tag) {
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
        $query = Party::where('id', $party_id)->where(function ($query) use ($user) {
            $query->whereHas('users', function ($q) use ($user) {
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

    //todo:テスト作成
    protected function registerImage(Party $party, string $image): void
    {
        $image_name = Storage::disk('s3')->putFile('/', $image);
        $party->update([
            'image' => $image_name
        ]);
    }

    /**
     *
     * @param array $params
     * @param int $auth_id
     *
     * @return array
     */
    public function searchParties(array $params, int $auth_id): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $query = Party::with('tags')->whereDate('created_at', '>=', $sevendays)
        ->where('leader_id', '!=', $auth_id)
        ->where(function ($query) use ($auth_id) {
            $query->whereHas('users', function ($q) use ($auth_id) {
                $q->whereNotIn('id', [$auth_id]);
            })
            ->orWhereDoesntHave('users');
        });

        if (isset($params['pref_id'])) {
            $query = $this->fetchRecordByPrefId($query, $params['pref_id']);
        }

        if (isset($params['tag_id'])) {
            $query = $this->fetchRecordByTagId($query, $params['tag_id']);
        }
        if (isset($params['keyword'])) {
            $query = $this->fetchRecordByKeyword($query, $params['keyword']);
        }

        $data = [];

        foreach ($query->get() as $key => $party) {
            $data[$key]['id']      = $party->id;
            $data[$key]['theme']   = $party->theme;
            $data[$key]['place']   = $party->place;
            $data[$key]['due_max'] = $party->due_max;
            $data[$key]['image']   = config('filesystems.disks.s3.url').'/'.$party->image;
            foreach($party->tags as $index => $tag) {
                $data[$key]['tags'][$index]['name'] = $tag->name;
            }
        }

        return $data;
    }

    /**
     * @param Builder $query
     * @param string $keyword
     *
     * @return Builder
     */
    protected function fetchRecordByKeyword(Builder $query, string $keyword): Builder
    {
        //文字列検索の条件洗い出し
        $query->where(function ($q) use ($keyword) {
            // →文字列がthemeに入る
            $q->where('theme', 'like', '%'.$keyword.'%');
            // →文字列がplaceに入る
            $q->orWhere('place', 'like', '%'.$keyword.'%');
            // →文字列がintroductionに入る
            $q->orWhere('introduction', 'like', '%'.$keyword.'%');
            // →3つのうちどれかに当てはまるレコードを全て取り出す
        });
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $tag_ids
     *
     * @return Builder
     */
    protected function fetchRecordByTagId(Builder $query, int $tag_id): Builder
    {

        $query->whereHas('tags', function ($q) use ($tag_id) {
            $q->where('tags.id', $tag_id);
        });
        return $query;
    }

    /**
     * @param Builder $query
     * @param int $pref_id
     *
     * @return Builder
     */
    protected function fetchRecordByPrefId(Builder $query, int $pref_id): Builder
    {
        $query->whereHas('pref', function ($q) use ($pref_id) {
            $q->where('id', $pref_id);
        });
        return $query;
    }
}
