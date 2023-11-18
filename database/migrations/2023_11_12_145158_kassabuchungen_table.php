<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KassabuchungenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kassabuchungen', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('typ', 2);
            $table->string('nummer', 8);
            $table->string('datum');
            $table->foreignUuid('anschrift_id')
                ->nullable()
                ->constrained('anschriften')
                ->onDelete('set null');
            $table->foreignUuid('kassabuch_id')
                ->constrained('kassabuch')
                ->onDelete('cascade');
            $table->double('gesamtpreis');
            $table->json('positionen')->nullable();
            $table->json('konditionen')->nullable();
            $table->string('bezahltDatum')->nullable();
            $table->string('anmerkungen')->nullable();
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
        Schema::dropIfExists('kassabuchungen');
    }
}
