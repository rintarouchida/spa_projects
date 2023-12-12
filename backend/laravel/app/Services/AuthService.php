<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function register(array $data): void
    {
        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "pref_id" => $data["pref_id"],
            "introduction" => $data["introduction"],
            "twitter_url" => $data["twitter_url"],
            "birthday" => $data["birthday"],
        ]);

        if (!is_null($data['image'])) {
            $this->registerImage($user, $data['image']);
        }
    }

    //テスト作成
    /**
     * @param array $data
     *
     * @return void
     */
    public function update(User $user, array $data): void
    {
        if (!is_null($data['image'])) {
            $this->registerImage($user, $data['image']);
        }

        unset($data['image']);
        $user->fill($data)->save();
    }

    //todo:テスト作成
    protected function registerImage(User $user, string $image): void
    {
        $image_name = Storage::disk('s3')->putFile('/', $image);
        $user->update([
            'image' => $image_name
        ]);
    }
}
