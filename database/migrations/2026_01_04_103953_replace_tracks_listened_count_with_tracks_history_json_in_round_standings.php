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
        Schema::table('round_standings', function (Blueprint $table) {
            // Supprimer tracks_listened_count
            $table->dropColumn('tracks_listened_count');

            // Ajouter tracks_history (JSON) avec les détails de chaque track
            // Structure: [{"track_id": 1, "answer_id": 123, "response_time": 5.2, "position": 1, "score": 10.0}, ...]
            $table->json('tracks_history')->nullable()->after('total_answers_count')
                ->comment('Historique détaillé des tracks jouées: track_id, answer_id, response_time, position, score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('round_standings', function (Blueprint $table) {
            $table->dropColumn('tracks_history');
            $table->integer('tracks_listened_count')->default(0)->after('total_answers_count');
        });
    }
};
