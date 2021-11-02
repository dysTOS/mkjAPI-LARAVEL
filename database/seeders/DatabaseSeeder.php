<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\date;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         // \App\Models\ausrueckung::factory(10)->create();

         DB::table('ausrueckungs')->insert([
            'name' => Str::random(10),
            'beschreibung' => Str::random(10),
            'von' => Str::random(10),
        ]);
    }
}
