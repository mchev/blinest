<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_checkout_session_id')->unique();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('eur');
            $table->string('month_key', 7);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donor_email')->nullable();
            $table->timestamp('donated_at');
            $table->timestamps();

            $table->index('month_key');
            $table->index(['user_id', 'month_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
