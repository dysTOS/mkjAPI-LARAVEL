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
        DB::table('mitglieder')->insert([
            'vorname' => 'Roland',
            'zuname' => 'Sams',
            'email' => 'rolandsams@gmail.com',
        ]);
        DB::table('mitglieder')->insert([
            'vorname' => 'test',
            'zuname' => 'user',
            'email' => 'test@user.com',
        ]);
    }
}
