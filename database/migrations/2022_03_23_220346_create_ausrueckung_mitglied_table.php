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
            $table->integer('ausrueckung_id')->unsigned()->index();
            $table->foreign('ausrueckung_id')->references('id')->on('ausrueckungen')->onDelete('cascade');

            $table->integer('mitglied_id')->unsigned()->index();
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['ausrueckung_id', 'mitglied_id']);
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
