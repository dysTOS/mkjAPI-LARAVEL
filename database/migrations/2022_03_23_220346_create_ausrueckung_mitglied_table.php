<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusrueckungMitgliedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausrueckung_mitglied', function (Blueprint $table) {
            $table->uuid('ausrueckung_id')->index();
            $table->foreign('ausrueckung_id')->references('id')->on('ausrueckungen')->onDelete('cascade');

            $table->uuid('mitglied_id')->index();
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');

            /*$table->primary(['ausrueckung_id', 'mitglied_id']);*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ausrueckung_mitglied');
    }
}
