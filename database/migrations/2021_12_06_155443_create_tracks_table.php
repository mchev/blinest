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
            $table->foreignId('playlist_id')->onDelete('cascade');
            $table->string('provider');
            $table->string('track_provider_id');
            $table->string('track_provider_url');
            $table->string('artist_name');
            $table->string('track_name');
            $table->string('preview_url');
            $table->date('release_date')->nullable();
            $table->string('artwork_url');
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
