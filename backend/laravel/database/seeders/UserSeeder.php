<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => '内田林太郎',
            'email' => 'r.uchida311@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 8,
        ]);
        User::create([
            'id' => 2,
            'name' => '山田',
            'email' => 'yamada@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 8,
        ]);
        User::create([
            'id' => 3,
            'name' => '鈴木',
            'email' => 'suzuki@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 8,
        ]);
    }
}
