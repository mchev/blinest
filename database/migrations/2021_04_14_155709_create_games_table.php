<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('games_user_id_foreign');
			$table->boolean('public')->nullable()->index('public');
			$table->string('title');
			$table->text('description')->nullable();
			$table->string('thumbnail')->nullable();
			$table->integer('tracks_number')->default(15);
			$table->integer('effect_id')->nullable();
			$table->boolean('random')->nullable()->default(1);
			$table->bigInteger('hit')->nullable();
			$table->timestamps(10);
			$table->string('slug');
			$table->string('password', 20)->nullable();
			$table->boolean('online')->nullable();
			$table->integer('counter')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('games');
	}

}
