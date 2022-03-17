<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusrueckungNotenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausrueckung_noten', function (Blueprint $table) {
            $table->integer('ausrueckung_id')->unsigned()->index();
            $table->foreign('ausrueckung_id')->references('id')->on('ausrueckungen')->onDelete('cascade');

            $table->integer('noten_id')->unsigned()->index();
            $table->foreign('noten_id')->references('id')->on('noten')->onDelete('cascade');

            $table->primary(['ausrueckung_id', 'noten_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ausrueckung_noten');
    }
}
