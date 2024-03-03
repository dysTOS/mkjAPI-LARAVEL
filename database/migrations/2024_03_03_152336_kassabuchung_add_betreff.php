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
        Schema::table('kassabuchungen', function (Blueprint $table) {
            $table->string('betreff')->after('datum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kassabuchungen', function (Blueprint $table) {
            $table->dropColumn('betreff');
            });
    }
};
