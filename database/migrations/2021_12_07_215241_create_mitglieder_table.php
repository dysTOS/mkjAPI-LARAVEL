<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMitgliederTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mitglieder', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->string('vorname');
            $table->string('zuname');
            $table->string('email')->unique()->nullable();
            $table->string('titelVor')->nullable();
            $table->string('titelNach')->nullable();
            $table->string('geburtsdatum')->nullable();
            $table->string('geschlecht')->nullable();
            $table->string('strasse')->nullable();
            $table->string('hausnummer')->nullable();
            $table->string('ort')->nullable();
            $table->string('plz')->nullable();
            $table->string('telefonHaupt')->nullable();
            $table->string('telefonMobil')->nullable();
            $table->string('beruf')->nullable();
            $table->boolean('aktiv')->nullable();
            $table->string('eintrittDatum')->nullable();
            $table->string('austrittDatum')->nullable();
            $table->timestamps();
        });
        /*Schema::table('users', function(Blueprint $table)
        {
            $table->foreign('mitglied_id')
                ->references('id')
                ->on('mitglieder')
                ->onDelete('cascade');
        });*/


        /*DB::table('mitglieder')->insert([
            'vorname' => 'Roland',
            'zuname' => 'Sams',
            'email' => 'rolandsams@gmail.com',
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mitglieder');
    }
}
