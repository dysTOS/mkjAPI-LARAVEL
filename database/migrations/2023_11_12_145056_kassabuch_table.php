<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KassabuchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kassabuch', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name')->unique();
            $table->boolean('aktiv')->default(false);
            $table->double('kassastand')->default(0);
            $table->foreignUuid('gruppe_id')
                ->nullable()
                ->constrained('gruppen')
                ->onDelete('set null');
            $table->string('anmerkungen')->nullable();
            $table->string('color', 8)->nullable();
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
        Schema::dropIfExists('kassabuch');
    }
}
