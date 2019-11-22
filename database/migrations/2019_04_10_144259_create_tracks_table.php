<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('provider_item_id')->nullable();
            $table->string('provider');
            $table->string('artist_name');
            $table->string('track_name');
            $table->year('year')->nullable();
            $table->string('custom_answer')->nullable();
            $table->unsignedInteger('down_rate')->nullable();
            $table->string('artwork_url');
            $table->string('preview_url');
            $table->string('filetype');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
