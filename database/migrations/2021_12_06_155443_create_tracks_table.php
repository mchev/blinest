<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('playlist_id')->constrained('playlists')->onDelete('cascade');
            $table->string('provider');
            $table->string('provider_id');
            $table->string('provider_url')->nullable();
            $table->string('preview_url');
            $table->string('artwork_url');
            $table->integer('counter')->nullable();
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
        Schema::dropIfExists('tracks');
    }
}
