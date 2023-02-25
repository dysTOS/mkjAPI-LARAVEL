<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotenmappenTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notenmappen', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->boolean('hatVerzeichnis')->default(false);
            $table->timestamps();
        });

        Schema::create('mappe_noten', function (Blueprint $table) {
            $table->uuid('mappe_id');
            $table->foreign('mappe_id')->references('id')->on('notenmappen')->onDelete('cascade');

            $table->uuid('noten_id');
            $table->foreign('noten_id')->references('id')->on('noten')->onDelete('cascade');
            $table->string('verzeichnisNr')->nullable();

            $table->primary(['mappe_id', 'noten_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notenmappen');
        Schema::dropIfExists('mappe_noten');
    }
}
