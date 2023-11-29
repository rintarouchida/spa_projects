<?php

namespace App\Services\Search;

use Carbon\Carbon;
use App\Models\Party;
use Illuminate\Database\Eloquent\Builder;

class PartyService
{
    //todo:テスト修正
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
        if (isset($params['tag_ids'])) {
            $query = $this->fetchRecordByTagId($query, $params['tag_ids']);
        }
        if (isset($params['keyword'])) {
            $query = $this->fetchRecordByKeyword($query, $params['keyword']);
        }

        $data = [];

        foreach ($query->get() as $key => $party) {
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

    /**
     * @param Builder $query
     * @param string $keyword
     *
     * @return Builder
     */
    protected function fetchRecordByKeyword(Builder $query, string $keyword): Builder
    {
        //文字列検索の条件洗い出し
        // →文字列がthemeに入る
        $query->where('theme', 'like', '%'.$keyword.'%');
        // →文字列がplaceに入る
        $query->orWhere('place', 'like', '%'.$keyword.'%');
        // →文字列がintroductionに入る
        $query->orWhere('introduction', 'like', '%'.$keyword.'%');
        // →3つのうちどれかに当てはまるレコードを全て取り出す
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $tag_ids
     *
     * @return Builder
     */
    protected function fetchRecordByTagId(Builder $query, array $tag_ids): Builder
    {
        //タグID条件の洗い出し
        //    →tag_idsのどれか一つをもつレコードを全て取り出す
        //    →(例)tag_ids=[1, 2, 3]とすると、[3, 4, 5]に紐ずくレコードは3が合致するので抽出される
        $query->whereHas('tags', function ($q) use ($tag_ids) {
            $q->whereIn('tags.id', $tag_ids);
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
