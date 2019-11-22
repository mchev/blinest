<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_track', function (Blueprint $table) {
            
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('track_id');

            $table->foreign('round_id')->references('id')->on('rounds')->onDelete('cascade');
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('round_track');
    }
}
