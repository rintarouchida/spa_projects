<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create([
            'id' => 1,
            'name' => 'PHP',
        ]);
        Tag::create([
            'id' => 2,
            'name' => 'Ruby',
        ]);
        Tag::create([
            'id' => 3,
            'name' => 'Python',
        ]);
        Tag::create([
            'id' => 4,
            'name' => 'Java',
        ]);
        Tag::create([
            'id' => 5,
            'name' => 'Go',
        ]);
        Tag::create([
            'id' => 6,
            'name' => 'JavaScript',
        ]);
        Tag::create([
            'id' => 7,
            'name' => 'Swift',
        ]);
        Tag::create([
            'id' => 8,
            'name' => 'Kotlin',
        ]);
        Tag::create([
            'id' => 9,
            'name' => 'Linux',
        ]);
        Tag::create([
            'id' => 10,
            'name' => 'MySQL',
        ]);
        Tag::create([
            'id' => 11,
            'name' => 'AWS',
        ]);
        Tag::create([
            'id' => 12,
            'name' => 'Docker',
        ]);
    }
}
