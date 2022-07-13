<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->string('photo_path')->nullable();
            $table->integer('tracks_by_round')->unsigned()->default(15);
            $table->integer('track_duration')->unsigned()->default(30);
            $table->integer('pause_between_tracks')->unsigned()->default(0);
            $table->integer('pause_between_rounds')->unsigned()->default(10);
            $table->boolean('is_public')->default(false);
            $table->boolean('is_pro')->default(false);
            $table->boolean('is_random')->default(true);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_chat_active')->default(true);
            $table->boolean('is_playing')->default(false);
            $table->string('discord_webhook_url')->nullable();
            $table->string('color')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
