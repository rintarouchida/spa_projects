<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserService
{
    /**
     * @param int $user_id
     *
     * @return array
     */
    public function getdata(int $user_id): array
    {
        //todo:テスト修正
        $user = User::find($user_id);
        $data = [];
        $data['id'] = $user->id;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['pref_id'] = $user->pref_id;
        $data['birthday'] = Carbon::createFromFormat('Y-m-d H:i:s', $user->birthday)->format('Y-m-d');
        $data['introduction'] = $user->introduction;
        $data['twitter_url'] = $user->twitter_url;
        $data['old']  = Carbon::parse($user->birthday)->age;
        $data['pref_name'] = $user->pref->name;

        return $data;
    }

    //todo:ユーザー更新関数作る
    /**
     * @param array $data
     *
     * @return void
     */
    public function update(User $user, array $data): void
    {
        $user->fill($data)->save();
    }
}
