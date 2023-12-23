<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MappeNotenAddOrderIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mappe_noten', function (Blueprint $table) {
            $table->integer('orderIndex')->nullable()->after("verzeichnisNr");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mappe_noten', function (Blueprint $table) {
            $table->dropColumn('orderIndex');
        });
    }
}
