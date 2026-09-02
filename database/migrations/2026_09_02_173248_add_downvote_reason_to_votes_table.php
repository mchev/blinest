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
        Schema::table('votes', function (Blueprint $table) {
            $table->string('downvote_reason', 32)->nullable()->after('votes');
            $table->index(['votable_type', 'votable_id', 'downvote_reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['votable_type', 'votable_id', 'downvote_reason']);
            $table->dropColumn('downvote_reason');
        });
    }
};
