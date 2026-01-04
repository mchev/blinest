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
            $table->integer('tracks_listened_count')->default(0)->after('total_answers_count')
                ->comment('Nombre de tracks écoutées par le joueur dans ce round (même sans trouver de réponse)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('round_standings', function (Blueprint $table) {
            $table->dropColumn('tracks_listened_count');
        });
    }
};
