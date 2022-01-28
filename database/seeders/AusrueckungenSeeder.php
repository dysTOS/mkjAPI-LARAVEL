<?php

namespace Database\Seeders;

use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AusrueckungenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++) {
            DB::table('ausrueckungen')->insert([
                'name' => Str::random(10),
                'beschreibung' => Str::random(10),
                'von' => DateTime::dateTime(),
                'bis' => DateTime::dateTime(),
                'kategorie' => "Weckruf",
                'status' => "Fixiert"
            ]);
        }
        for($i = 0; $i < 200; $i++) {
            DB::table('ausrueckungen')->insert([
                'name' => Str::random(10),
                'beschreibung' => Str::random(10),
                'von' => DateTime::dateTime(),
                'bis' => DateTime::dateTime(),
                'kategorie' => "Kurkonzert",
                'status' => "Geplant"
            ]);
        }
        for($i = 0; $i < 100; $i++) {
            DB::table('ausrueckungen')->insert([
                'name' => Str::random(10),
                'beschreibung' => Str::random(10),
                'von' => DateTime::dateTime(),
                'bis' => DateTime::dateTime(),
                'kategorie' => "Ständchen",
                'status' => "Ersatztermin"
            ]);
        }

    }
}
