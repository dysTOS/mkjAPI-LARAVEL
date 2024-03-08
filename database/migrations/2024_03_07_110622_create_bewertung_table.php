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
        Schema::create('bewertungen', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuidMorphs('bewertbar');
            $table->uuid('mitglied_id');
            $table->integer('bewertung')->nullable( );
            $table->timestamps();
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('cascade');
        });

        Schema::table('noten', function (Blueprint $table) {
            $table->integer('bewertung')->nullable()->after('schwierigkeit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noten', function (Blueprint $table) {
            $table->dropColumn('bewertung');
        });
        Schema::dropIfExists('bewertungen');
    }
};
