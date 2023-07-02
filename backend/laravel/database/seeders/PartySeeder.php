<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Party::create([
            'id' => 1,
            'theme' => 'Ruby勉強会@道玄坂',
            'place' => '渋谷道玄坂カフェ',
            'due_max' => 8,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 8,
            'leader_id' => 1,
        ]);
        Party::create([
            'id' => 2,
            'theme' => 'Laravel勉強会@下北沢',
            'place' => '下北沢第一ビル4F',
            'due_max' => 5,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 3,
            'leader_id' => 2,
        ]);
        Party::create([
            'id' => 3,
            'theme' => 'AWS勉強会@東京',
            'place' => '八重洲北口ビル4F',
            'due_max' => 12,
            'due_date' => '2023-05-08 00:00:00',
            'introduction' => 'こんにちは',
            'pref_id' => 5,
            'leader_id' => 3,
        ]);
    }
}
