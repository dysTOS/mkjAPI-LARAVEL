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
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->text('beschreibung')->nullable();
            $table->boolean('oeffentlich')->default(true);
            $table->text('infoMusiker')->nullable();
            $table->string('ort')->nullable();
            $table->string('treffzeit')->nullable();
            $table->string('kategorie');
            $table->string('status');
            $table->date('vonDatum')->index();
            $table->date('bisDatum');
            $table->string('vonZeit')->nullable();
            $table->string('bisZeit')->nullable();
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
        Schema::dropIfExists('ausrueckungen');
    }
}
