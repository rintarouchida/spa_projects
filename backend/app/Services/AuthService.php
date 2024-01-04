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
            $user->update([
                'image' => $this->createS3Image($user, $data['image'])
            ]);
        }
    }

    //テスト作成
    /**
     * @param array $data
     *
     * @return void|string
     */
    public function update(User $user, array $data)
    {
        if (!is_null($data['image'])) {
            $user->update([
                'image' => $this->createS3Image($user, $data['image'])
            ]);
        }
        unset($data['image']);

        $user->fill($data)->save();
    }

    /**
     * @param User $user
     * @param string $image
     *
     * @return string
     */
    protected function createS3Image(User $user, string $image): string
    {
        $image_name = Storage::disk('s3')->putFile('/', $image);

        return $image_name;
    }
}
