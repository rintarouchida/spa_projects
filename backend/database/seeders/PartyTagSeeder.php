<?php

namespace Database\Seeders;

use App\Models\PartyTag;
use Illuminate\Database\Seeder;

class PartyTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PartyTag::create([
            'id' => 1,
            'tag_id' => 1,
            'party_id' => 1,
        ]);
        PartyTag::create([
            'id' => 2,
            'tag_id' => 2,
            'party_id' => 1,
        ]);
        PartyTag::create([
            'id' => 3,
            'tag_id' => 2,
            'party_id' => 2,
        ]);
        PartyTag::create([
            'id' => 4,
            'tag_id' => 3,
            'party_id' => 2,
        ]);
        PartyTag::create([
            'id' => 5,
            'tag_id' => 3,
            'party_id' => 1,
        ]);
    }
}
