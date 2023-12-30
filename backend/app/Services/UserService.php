<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserService
{
    /**
     * @param int $user_id
     * @return User
     */
    public function getUser(int $user_id): User
    {
        $user = User::with('pref')->find($user_id);
        return $user;
    }
}
