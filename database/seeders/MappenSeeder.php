<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MappenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notenmappen')->insert([
            'name' => 'Rote Mappe',
            'hatVerzeichnis'=> true
        ]);
        DB::table('notenmappen')->insert([
            'name' => 'Grüne Konzertmappe'
        ]);
    }
}
