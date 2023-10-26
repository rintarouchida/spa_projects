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
        //todo:もくもく会検索機能作成
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
    }

    /**
     * @param Builder $query
     * @param string $keyword
     *
     * @return Builder
     */
    protected function fetchRecordByKeyword(Builder $query, string $keyword): Builder
    {
        //todo:文字列検索の条件洗い出し
        // →文字列がthemeに入る
        $query->where('theme', 'like', '%'.$keyword.'%');
        // →文字列がplaceに入る
        $query->orWhere('place', 'like', '%'.$keyword.'%');
        // →文字列がintroductionに入る
        $query->orWhere('introduction', 'like', '%'.$keyword.'%');
        // →3つのうちどれかに当てはまるレコードを全て取り出す
        return $query;
    }

    protected function fetchRecordByTagId($query, $tag_ids)
    {
        //todo: タグID条件の洗い出し
        //    →tag_idsのどれか一つをもつレコードを全て取り出す
        //    →(例)tag_ids=[1, 2, 3]とすると、[3, 4, 5]に紐ずくレコードは3が合致するので抽出される
    }
}
