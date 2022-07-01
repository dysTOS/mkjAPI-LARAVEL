<?php

namespace Database\Seeders;

use App\Helper\Helper\Helper;
use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Ausrueckung;

class AusrueckungenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            $table = 'ausrueckungen';
            $file = base_path("/seeders/$table".".csv"); // TODO: change to actual path
            $records = Helper::import_CSV($file);

            foreach ($records as $key => $record) {
                DB::table($table)->insert([
                    'name' => $record['name'],
                    'beschreibung' => $record['beschreibung'],
                    'oeffentlich' => $record['oeffentlich'],
                    'infoMusiker' => $record['infoMusiker'],
                    'ort' => $record['ort'],
                    'treffzeit' => $record['treffzeit'],
                    'kategorie' => $record['kategorie'],
                    'status' => $record['status'],
                    'vonDatum' => $record['vonDatum'],
                    'bisDatum' => $record['bisDatum'],
                    'vonZeit' => $record['vonZeit'],
                    'bisZeit' => $record['bisZeit'],
                    'created_at' => $record['created_at'],
                    'updated_at' => $record['updated_at'],
                ]);
            }
    }
}
