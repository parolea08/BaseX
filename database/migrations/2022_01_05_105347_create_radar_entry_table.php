<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadarEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radar_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radar_id');
            $table->unsignedInteger('slice_position');
            $table->unsignedInteger('ring_position');
            $table->string('value');

            $table->foreign('radar_id')->references('id')->on('radar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radar_entry');
    }
}
