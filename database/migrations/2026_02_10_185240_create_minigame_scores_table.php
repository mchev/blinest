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
        Schema::create('minigame_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('game_type', 32)->index();
            $table->unsignedInteger('score')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::table('minigame_scores', function (Blueprint $table) {
            $table->index(['user_id', 'game_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minigame_scores');
    }
};
