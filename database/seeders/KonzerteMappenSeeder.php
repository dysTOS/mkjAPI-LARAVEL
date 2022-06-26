<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KonzerteMappenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('konzerte')->insert([
            'name' => 'testkonzert',
            'datum' => '2022-05-06',
            'ort' => 'testort',
        ]);
        DB::table('notenmappen')->insert([
            'name' => 'Rote Mappe'
        ]);
        DB::table('notenmappen')->insert([
            'name' => 'Grüne Konzertmappe'
        ]);
    }
}
