<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('role');
            $table->string('name');
            $table->timestamps();
        });

        //initially create all Roles
        DB::table('roles')->insert([
            'role' => 'mitglied',
            'name' => 'Mitglied'
        ]);
        DB::table('roles')->insert([
            'role' => 'admin',
            'name' => 'Admin',
        ]);
        DB::table('roles')->insert([
            'role' => 'ausschuss',
            'name' => 'Ausschuss'
        ]);
        DB::table('roles')->insert([
            'role' => 'festausschuss',
            'name' => 'Festausschuss'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
