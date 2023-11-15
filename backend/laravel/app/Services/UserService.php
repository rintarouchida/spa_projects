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
        $user = User::find($user_id);
        $data = [];
        $data['name'] = $user->name;
        $data['old']  = Carbon::parse($user->birthday)->age;
        $data['pref_name'] = $user->pref->name;
        $data['introduction'] = $user->introduction;

        return $data;
    }
}
