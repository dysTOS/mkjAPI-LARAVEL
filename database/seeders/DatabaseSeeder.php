<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        $this->call([
            //AusrueckungenSeeder::class,
            //MitgliederSeeder::class,
            //RolePermissionSeeder::class,
            MappenSeeder::class
        ]);
    }
}
