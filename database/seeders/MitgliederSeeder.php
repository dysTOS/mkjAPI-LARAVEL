<?php

namespace Database\Seeders;

use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MitgliederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*DB::table('mitglieder')->insert([
            'vorname' => 'Viktoria',
            'zuname' => 'Sams',
            'email' => 'viktoriasams@gmail.com',
        ]);
        DB::table('mitglieder')->insert([
            'vorname' => 'Alois',
            'zuname' => 'Sams',
            'email' => 'aloissams@gmail.com',
        ]);
        DB::table('mitglieder')->insert([
            'vorname' => 'Manueala',
            'zuname' => 'Sams',
            'email' => 'manuelasams@gmail.com',
        ]);*/

        /*for($i = 0; $i < 50; $i++) {
            DB::table('mitglieder')->insert([
                'vorname' => Str::random(10),
                'zuname' => Str::random(10),
                'email' => Str::random(5).'@'.Str::random(3).'.'.Str::random(2),
                'titelVor' => Str::random(2),
                'titelNach' => Str::random(2),
                'geschlecht' => Str::random(2),
                'geburtsdatum' => DateTime::date(),
                'strasse' => Str::random(10),
                'hausnummer' => Str::random(2),
                'ort' => Str::random(10),
                'plz' => Str::random(4),
                'beruf' => Str::random(10),
                'telefonHaupt' => str::random(10),
                'telefonMobil' => Str::random(10),
                'aktiv' => 1,
                'eintrittDatum' => DateTime::date(),
                'austrittDatum' => DateTime::date(),
            ]);
        }*/
    }
}
