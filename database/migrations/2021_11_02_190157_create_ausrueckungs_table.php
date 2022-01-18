<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusrueckungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausrueckungen', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('beschreibung')->nullable();
            $table->string('infoMusiker')->nullable();
            $table->boolean('oeffentlich')->default(true);
            $table->string('kategorie');
            $table->string('status');
            $table->dateTime('von');
            $table->dateTime('bis');
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
        Schema::dropIfExists('ausrueckungs');
    }
}
