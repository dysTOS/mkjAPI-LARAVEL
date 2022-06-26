<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotensammlungenTables extends Migration
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
            $table->timestamps();
        });

        Schema::create('mappe_noten', function (Blueprint $table) {
            $table->uuid('mappe_id');
            $table->foreign('mappe_id')->references('id')->on('notenmappen')->onDelete('cascade');

            $table->uuid('noten_id');
            $table->foreign('noten_id')->references('id')->on('noten')->onDelete('cascade');

            $table->primary(['mappe_id', 'noten_id']);
        });

        Schema::create('konzerte', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->string('datum');
            $table->string('ort');
            $table->timestamps();
        });

        Schema::create('konzert_noten', function (Blueprint $table) {
            $table->uuid('konzert_id');
            $table->foreign('konzert_id')->references('id')->on('konzerte')->onDelete('cascade');

            $table->uuid('noten_id');
            $table->foreign('noten_id')->references('id')->on('noten')->onDelete('cascade');

            $table->primary(['konzert_id', 'noten_id']);
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
        Schema::dropIfExists('konzerte');
        Schema::dropIfExists('konzert_noten');
        Schema::dropIfExists('mappe_noten');
    }
}
