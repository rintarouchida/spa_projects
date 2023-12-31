<?php

namespace Database\Seeders;

use App\Models\MessageGroup;
use Illuminate\Database\Seeder;

class MessageGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MessageGroup::create([
            'id' => 1,
            'party_id' => 3,
        ]);
    }
}
