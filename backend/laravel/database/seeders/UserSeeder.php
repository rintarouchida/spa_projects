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
    }
}
