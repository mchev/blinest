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
        Schema::table('user_levels', function (Blueprint $table) {
            $table->unsignedInteger('minigame_scores_total')->default(0)->after('score_public_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_levels', function (Blueprint $table) {
            $table->dropColumn('minigame_scores_total');
        });
    }
};
