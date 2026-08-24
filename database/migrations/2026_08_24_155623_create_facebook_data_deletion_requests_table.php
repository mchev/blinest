<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_data_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->string('confirmation_code', 32)->unique();
            $table->string('facebook_user_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action')->default('pending');
            $table->string('source', 32)->default('callback');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_data_deletion_requests');
    }
};
