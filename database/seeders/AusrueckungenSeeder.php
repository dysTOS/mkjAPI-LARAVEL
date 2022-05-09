<?php

namespace Database\Seeders;

use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
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
        for($i = 0; $i < 10; $i++) {
            DB::table('ausrueckungen')->insert([
                'name' => Str::random(10),
                'beschreibung' => Str::random(10),
                'vonDatum' => DateTime::date(),
                'bisDatum' => DateTime::date(),
                'treffzeit' => DateTime::time(),
                'kategorie' => "Weckruf",
                'status' => "Fixiert"
            ]);
        }

    }
}
