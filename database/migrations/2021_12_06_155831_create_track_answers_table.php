<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained('tracks')->onDelete('cascade');
            $table->string('answer_type_id');
            $table->string('value');
            $table->decimal('score', 3, 1)->default(0.5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_answers');
    }
}
