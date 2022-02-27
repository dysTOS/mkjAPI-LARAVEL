<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('role');

//            $table->integer('mitglieder_id')->nullable();
//            $table->foreign('mitglieder_id')
//                ->references('mitglieder_id')
//                ->on('role_mitglied')
//                ->onDelete('cascade');

            $table->timestamps();
        });

        //initially create all Roles
        DB::table('roles')->insert([
            'role' => 'mitglied'
        ]);
        DB::table('roles')->insert([
            'role' => 'admin'
        ]);
        DB::table('roles')->insert([
            'role' => 'ausschuss'
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
