<?php

namespace App\Services\Search;

use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PartyService
{
    /**
    * @param array $params
    *
    * @return array
    */
    public function searchParties(array $params): array
    {
        $sevendays=Carbon::today()->subDay(7);
        $query = Party::with('tags')->whereDate('created_at', '>=', $sevendays);

        if(!is_null($params['pref_id'])) {
            $query = $this->fetchRecordByPrefId($query, $params['pref_id']);
        }
        if(!is_null($params['tag_id'])) {
            $query = $this->fetchRecordByPrefId($query, $params['tag_id']);
        }
        if(!is_null($params['keyword'])) {
            $query = $this->fetchRecordByTagId($query, $params['keyword']);
        }
        foreach ($query as $key => $party) {
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

    protected function fetchRecordByTagId(Builder $query, array $tag_ids): Builder
    {
        //タグID条件の洗い出し
        //    →tag_idsのどれか一つをもつレコードを全て取り出す
        //    →(例)tag_ids=[1, 2, 3]とすると、[3, 4, 5]に紐ずくレコードは3が合致するので抽出される
        $query->('tags', function ($q) use ($tag_ids) {
            $q->whereIn('id', $tag_ids);
        });
        return $query
    }
}
