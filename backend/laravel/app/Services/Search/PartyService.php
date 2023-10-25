<?php

namespace App\Services\Search;

use Carbon\Carbon;

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

    protected function fetchRecordByKeyword($query, $keyword)
    {
        //todo:文字列検索の条件洗い出し
        // →文字列がthemeに入る
        // →文字列がplaceに入る
        // →文字列がintroductionに入る
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
