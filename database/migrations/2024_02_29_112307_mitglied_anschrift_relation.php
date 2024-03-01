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
        Schema::table('mitglieder', function (Blueprint $table) {
            $table->uuid('anschrift_id')->nullable()->after('user_id');
            $table->foreign('anschrift_id')
            ->references('id')
            ->on('anschriften')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitglieder', function (Blueprint $table) {
            $table->dropForeign(['anschrift_id']);
            $table->dropColumn('anschrift_id');
        });
    }
};
