<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleMitgliedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_mitglied', function (Blueprint $table) {
            $table->uuid('mitglied_id')->index();
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');

            $table->uuid('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            /*$table->primary(['mitglied_id', 'role_id']);*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_mitglied');
    }
}
