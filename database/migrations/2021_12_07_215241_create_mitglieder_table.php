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
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->string('vorname');
            $table->string('zuname');
            $table->string('email')->unique();
            $table->string('titel_vor')->nullable();
            $table->string('titel_nach')->nullable();
            $table->date('geb_datum')->nullable();
            $table->string('geschlecht')->nullable();
            $table->string('strasse')->nullable();
            $table->string('hausnummer')->nullable();
            $table->string('ort')->nullable();
            $table->string('plz')->nullable();
            $table->string('tel_haupt')->nullable();
            $table->string('tel_mobil')->nullable();
            $table->string('beruf')->nullable();
            $table->boolean('aktiv')->nullable();
            $table->date('eintritt_datum')->nullable();
            $table->date('austritt_datum')->nullable();
            $table->timestamps();


        });

        DB::table('mitglieder')->insert([
            'vorname' => 'Roland',
            'zuname' => 'Sams',
            'email' => 'rolandsams@gmail.com',
        ]);
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
