<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radar', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('moderator_id');
            $table->text('description');
            $table->timestamps();

            // add foreign key reference
            $table->foreign('moderator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radar');
    }
}
