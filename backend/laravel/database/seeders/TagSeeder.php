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
            'name' => 'Ruby',
        ]);
        Tag::create([
            'id' => 2,
            'name' => 'Laravel',
        ]);
        Tag::create([
            'id' => 3,
            'name' => 'AWS',
        ]);
    }
}
