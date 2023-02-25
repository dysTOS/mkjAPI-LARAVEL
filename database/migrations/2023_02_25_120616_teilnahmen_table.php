<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeilnahmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teilnahmen', function (Blueprint $table) {
            $table->uuid('mitglied_id');
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');

            $table->uuid('termin_id');
            $table->foreign('termin_id')->references('id')->on('ausrueckungen')->onDelete('cascade');
            $table->string('status')->nullable();

            $table->primary(['mitglied_id', 'termin_id']);

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
        Schema::dropIfExists('teilnahmen');
    }
}
