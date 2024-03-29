<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Party;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartyService
{
    /**
     * @param int $auth_id
     *
     * @return Collection
     */
    public function fetchPickUpParties(int $auth_id): Collection
    {
        $next_day = Carbon::today()->addDay(1);
        //開催日が翌日以降のもくもく会のみ抽出
        $parties = Party::with(['tags', 'users', 'leader', 'pref'])->whereDate('event_date', '>=', $next_day)
        ->where('leader_id', '!=', $auth_id)
        ->where(function ($query) use ($auth_id) {
            $query->whereHas('users', function ($q) use ($auth_id) {
                $q->whereNotIn('id', [$auth_id]);
            })
            ->orWhereDoesntHave('users');
        })->get();

        return $parties;
    }

    /**
     * @param int $auth_id
     *
     * @return Collection
     */
    public function fetchPickUpCreatedParties(int $auth_id): Collection
    {
        $today = Carbon::today();
        //開催日が当日以降のもくもく会のみ抽出
        $parties = Party::with(['tags', 'users', 'leader', 'pref'])->whereDate('event_date', '>=', $today)
        ->where('leader_id', $auth_id)
        ->get();

        return $parties;
    }

    /**
     * @param int $auth_id
     *
     * @return Collection
     */
    public function fetchPickUpParticipatedParties(int $auth_id): Collection
    {
        $today = Carbon::today();
        //開催日が当日以降のもくもく会のみ抽出
        $parties = Party::with(['tags', 'users', 'leader', 'pref'])->whereDate('event_date', '>=', $today)
        ->whereHas('users', function ($q) use ($auth_id) {
            $q->whereIn('id', [$auth_id]);
        })->get();

        return $parties;
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
            'event_date' => $data['event_date'],
            'introduction' => $data['introduction'],
            'pref_id' => $data['pref_id'],
            'leader_id' => $user_id,
        ]);

        if (!is_null($data['image'])) {
            $this->registerImage($party, $data['image']);
        }

        if (!is_null($data['tag_ids'])) {
            $party->tags()->attach($data['tag_ids']);
        }

        $this->createMessageGroup($party->id, $user_id);
    }

    /**
     * @param int $party_id
     *
     * @return Party
     */
    public function getParty(int $party_id): Party
    {
        $party = Party::with(['leader', 'tags'])->find($party_id);
        return $party;
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
        $mesage_group = MessageGroup::where('party_id', $party_id)->first();
        $mesage_group->users()->attach($user->id);
        Message::create([
            'user_id' => $user->id,
            'message_group_id' => $mesage_group->id,
            'content' => $user->name.'です。お願いします。'
        ]);
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

        Message::create([
            'user_id' => $user_id,
            'message_group_id' => $message_group->id,
            'content' => 'もくもく会を作成しました。'
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
     * @return Collection
     */
    public function searchParties(array $params, int $auth_id): Collection
    {
        $next_day = Carbon::today()->addDay(1);
        //開催日が翌日以降のもくもく会のみ抽出
        $query = Party::with(['tags', 'users', 'leader', 'pref'])->whereDate('event_date', '>=', $next_day)
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

        return $query->get();
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

    /**
     * @param array $data
     *
     * @return void
     */
    public function update (array $data, Party $party): void
    {
        if (!is_null($data['tag_ids'])) {
            $party->tags()->sync($data['tag_ids']);
        }
        if (!is_null($data['image'])) {
            $this->registerImage($party, $data['image']);
            unset($data['image']);
        }
        unset($data['tag_ids']);
        unset($data['image']);
        $party->fill($data)->save();
    }

    public function cancel(Party $party, int $auth_id): void
    {
        //もくもく会とキャンセルユーザーの紐付けを削除
        $party->users()->detach($auth_id);

        $message_group = MessageGroup::where('party_id', $party->id)->first();
        //キャンセルしたもくもく会のメッセージを取得できなくする
        $message_group->users()->detach($auth_id);

        //主催者がキャンセルを自動アナウンス
        Message::create([
            'user_id' => $party->leader->id,
            'message_group_id' => $message_group->id,
            'content' => User::find($auth_id)->name.'さんが参加をキャンセルしました。',
        ]);
    }
}
