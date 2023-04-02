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
        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex(['track_id']);
            $table->dropIndex(['answer_id']);

            $table->index('user_id');
            $table->index('created_at');

            $table->dropColumn('order');
            $table->dropColumn('bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->smallInteger('order')->nullable();
            $table->boolean('bonus')->nullable();

            $table->index(['track_id']);
            $table->index(['answer_id']);

            $table->dropIndex('user_id');
            $table->dropIndex('created_at');
        });
    }
};
