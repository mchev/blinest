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
        Schema::create('user_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->unsignedInteger('level')->default(1)->index();
            $table->unsignedBigInteger('total_xp')->default(0)->index();
            $table->unsignedBigInteger('current_xp')->default(0);
            $table->unsignedBigInteger('xp_for_next_level')->default(100);
            $table->unsignedBigInteger('score_public_rooms')->default(0)->index();
            $table->unsignedInteger('rooms_created_count')->default(0);
            $table->unsignedInteger('months_seniority')->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_levels');
    }
};
