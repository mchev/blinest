<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeratorTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moderator_tickets', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('moderator_tickets_user_id_foreign');
			$table->bigInteger('track_id')->unsigned()->nullable()->index('moderator_tickets_track_id_foreign');
			$table->bigInteger('game_id')->unsigned()->index('moderator_tickets_game_id_foreign');
			$table->text('message');
			$table->boolean('status')->nullable();
			$table->bigInteger('updated_by')->unsigned()->nullable()->index('moderator_tickets_updated_by_foreign');
			$table->timestamps(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('moderator_tickets');
	}

}
