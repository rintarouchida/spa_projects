<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PrefSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PartySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(PartyTagSeeder::class);
    }
}
