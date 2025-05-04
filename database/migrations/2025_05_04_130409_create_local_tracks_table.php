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
        Schema::create('local_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('artist_name')->index();
            $table->string('track_name')->index();
            $table->string('audio_path');
            $table->string('artwork_path');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_tracks');
    }
};
