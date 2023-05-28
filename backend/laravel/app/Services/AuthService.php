<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): void
    {
        User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "pref_id" => $data["pref_id"],
            "introduction" => $data["introduction"],
            "twitter_url" => $data["twitter_url"],
            "birthday" => $data["birthday"],
        ]);
    }
}
