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
            $table->unsignedInteger('rounds_played_count')->default(0)->after('rooms_created_count');
            $table->unsignedInteger('correct_answers_count')->default(0)->after('rounds_played_count');
            $table->unsignedInteger('tracks_liked_count')->default(0)->after('correct_answers_count');
            $table->unsignedInteger('messages_count')->default(0)->after('tracks_liked_count');
            $table->unsignedInteger('playlists_created_count')->default(0)->after('messages_count');
            $table->unsignedInteger('unique_rooms_played_count')->default(0)->after('playlists_created_count');
            $table->decimal('best_round_score', 8, 1)->default(0)->after('unique_rooms_played_count');
            $table->unsignedInteger('consecutive_days_streak')->default(0)->after('best_round_score');
            $table->date('last_login_date')->nullable()->after('consecutive_days_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_levels', function (Blueprint $table) {
            $table->dropColumn([
                'rounds_played_count',
                'correct_answers_count',
                'tracks_liked_count',
                'messages_count',
                'playlists_created_count',
                'unique_rooms_played_count',
                'best_round_score',
                'consecutive_days_streak',
                'last_login_date',
            ]);
        });
    }
};
