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
        $user = User::with('pref')->find($user_id);
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
        $data['image'] = config('filesystems.disks.s3.url').'/'.$user->image;

        return $data;
    }
}
