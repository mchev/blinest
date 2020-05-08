<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->dateTimeTz('date');
            $table->boolean('type')->nullable();
            $table->string('title', 100);
            $table->text('description');
            $table->string('address')->nullable();
            $table->float('price')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
