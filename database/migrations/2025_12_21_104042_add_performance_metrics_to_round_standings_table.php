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
            $table->decimal('average_response_time', 8, 3)->nullable()->after('total_score')->comment('Temps moyen de réponse en secondes');
            $table->integer('fast_answers_count')->default(0)->after('average_response_time')->comment('Nombre de réponses rapides (bonus vitesse)');
            $table->integer('total_answers_count')->default(0)->after('fast_answers_count')->comment('Nombre total de réponses trouvées');
            $table->integer('win_streak')->default(0)->after('total_answers_count')->comment('Nombre de victoires consécutives dans cette room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('round_standings', function (Blueprint $table) {
            $table->dropColumn([
                'average_response_time',
                'fast_answers_count',
                'total_answers_count',
                'win_streak',
            ]);
        });
    }
};
