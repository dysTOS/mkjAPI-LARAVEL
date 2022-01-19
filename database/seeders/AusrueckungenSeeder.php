<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AusrueckungenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ausrueckungs')->insert([
                    'name' => Str::random(10),
                    'beschreibung' => Str::random(10),
                    'von' => Str::random(10),
                ]);
    }
}
