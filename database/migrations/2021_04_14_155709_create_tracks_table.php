<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tracks', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('tracks_user_id_foreign');
			$table->bigInteger('game_id')->unsigned()->index('tracks_game_id_foreign');
			$table->string('provider_item_id', 191)->nullable();
			$table->string('provider');
			$table->string('artist_name');
			$table->string('track_name');
			$table->string('custom_answer', 50)->nullable();
			$table->integer('down_rate')->unsigned()->nullable();
			$table->string('artwork_url');
			$table->string('preview_url');
			$table->timestamps(10);
			$table->bigInteger('hit')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tracks');
	}

}
