<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noten', function (Blueprint $table) {
            $table->id();
            $table->string('inventarId')->nullable();
            $table->string('titel');
            $table->string('komponist')->nullable();
            $table->string('arrangeur')->nullable();
            $table->string('verlag')->nullable();
            $table->string('gattung')->nullable();
            $table->string('ausgeliehenAb')->nullable();
            $table->string('ausgeliehenVon')->nullable();
            $table->string('anmerkungen')->nullable();
            $table->string('aufbewahrungsort')->nullable();
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
        Schema::dropIfExists('noten');
    }
}
