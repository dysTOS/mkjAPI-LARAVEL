<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruppenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gruppen', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name')->unique();
            $table->uuid('gruppenleiter_mitglied_id')->nullable();
            $table->boolean('register')->nullable();
            $table->char('color',10)->nullable();
            $table->timestamps();

            $table->foreign('gruppenleiter_mitglied_id')
                ->references('id')
                ->on('mitglieder')
                ->onDelete('set null');
        });

        Schema::create('mitglied_gruppe', function (Blueprint $table) {
            $table->uuid('mitglied_id');
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');

            $table->uuid('gruppen_id');
            $table->foreign('gruppen_id')->references('id')->on('gruppen')->onDelete('cascade');
            $table->primary(['mitglied_id', 'gruppen_id']);

            $table->timestamps();
        });

        Schema::table('ausrueckungen', function(Blueprint $table)
        {
            $table->foreign('gruppe_id')
                ->references('id')
                ->on('gruppen')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gruppen');
        Schema::dropIfExists('mitglied_gruppe');
    }
}
