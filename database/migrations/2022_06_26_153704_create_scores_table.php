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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_id')->index()->nullable();
            $table->foreignId('round_id')->index();
            $table->foreignId('track_id')->index();
            $table->foreignId('answer_id')->index();
            $table->decimal('score', 7, 1);
            $table->decimal('time', 8, 6)->nullable();
            $table->smallInteger('order')->nullable();
            $table->boolean('bonus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
