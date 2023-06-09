<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstrumenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumente', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('bezeichnung');
            $table->string('marke')->nullable();
            $table->string('anschaffungsdatum')->nullable();
            $table->string('verkaeufer')->nullable();
            $table->string('anmerkungen')->nullable();
            $table->string('schaeden')->nullable();
            $table->string('aufbewahrungsort')->nullable();
            $table->foreignUuid('mitglied_id')
                ->nullable()
                ->constrained('mitglieder')
                ->onDelete('set null');
            $table->foreignUuid('gruppe_id')
                ->nullable()
                ->constrained('gruppen')
                ->onDelete('set null');
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
        Schema::dropIfExists('instrumente');
    }
}
