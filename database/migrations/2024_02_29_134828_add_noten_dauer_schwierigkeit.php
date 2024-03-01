<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('noten', function (Blueprint $table) {
            $table->time('dauer')->nullable()->after('gattung');
            $table->integer('schwierigkeit')->nullable()->after('dauer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noten', function (Blueprint $table) {
            $table->dropColumn('dauer');
            $table->dropColumn('schwierigkeit');
        });
    }
};
