<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnschriftenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anschriften', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('vorname')->nullable();
            $table->string('zuname')->nullable();
            $table->string('anrede')->nullable();
            $table->string('titelVor')->nullable();
            $table->string('titelNach')->nullable();
            $table->string('geburtsdatum')->nullable();
            $table->string('firma')->nullable();
            $table->string('strasse')->nullable();
            $table->string('hausnummer')->nullable();
            $table->string('ort')->nullable();
            $table->string('plz', 8)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telefonHaupt')->nullable();
            $table->string('telefonMobil')->nullable();
            $table->string('IBAN')->nullable();
            $table->string('BIC')->nullable();
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
        Schema::dropIfExists('anschriften');
    }
}
