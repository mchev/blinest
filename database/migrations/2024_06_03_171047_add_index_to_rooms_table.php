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
        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['is_public', 'is_playing']);
        });
        Schema::table('rounds', function (Blueprint $table) {
            $table->index(['is_playing']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['is_public', 'is_playing']);
        });
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropIndex(['is_playing']);
        });
    }
};
