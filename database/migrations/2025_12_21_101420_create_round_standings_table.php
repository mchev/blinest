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
        Schema::create('round_standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->integer('position')->comment('Position finale: 1 = 1er, 2 = 2ème, 3 = 3ème, etc.');
            $table->decimal('total_score', 10, 2)->comment('Score total du joueur pour ce round');
            $table->integer('elo_before')->comment('ELO avant le round');
            $table->integer('elo_after')->comment('ELO après le round');
            $table->integer('elo_change')->comment('Changement d\'ELO (peut être négatif)');
            $table->boolean('is_elo_counted')->default(false)->comment('Indique si ce standing compte dans le calcul ELO (rooms publiques avec 3+ joueurs)');
            $table->timestamps();

            // Index pour accès rapide
            $table->index(['round_id', 'user_id']);
            $table->index(['user_id', 'position']);
            $table->index(['round_id', 'position']);
            $table->index('room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_standings');
    }
};
