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
        Schema::create('kommentare', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuidMorphs('commentable');
            $table->uuid('mitglied_id')->nullable();
            $table->string('mitglied_name');
            $table->uuid('parent_comment_id')->nullable();
            $table->integer('number_child_comments')->default(0);
            $table->text('text')->nullable();
            $table->timestamps();
            $table->foreign('parent_comment_id')->references('id')->on('kommentare')->onDelete('cascade');
            $table->foreign('mitglied_id')->references('id')->on('mitglieder')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kommentare');
    }
};
