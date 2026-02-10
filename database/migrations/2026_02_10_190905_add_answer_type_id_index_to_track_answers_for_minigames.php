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
        Schema::table('track_answers', function (Blueprint $table) {
            $table->index(['answer_type_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('track_answers', function (Blueprint $table) {
            $table->dropIndex(['answer_type_id', 'id']);
        });
    }
};
