<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToModeratorTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('moderator_tickets', function(Blueprint $table)
		{
			$table->foreign('game_id')->references('id')->on('games')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('track_id')->references('id')->on('tracks')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('updated_by')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('moderator_tickets', function(Blueprint $table)
		{
			$table->dropForeign('moderator_tickets_game_id_foreign');
			$table->dropForeign('moderator_tickets_track_id_foreign');
			$table->dropForeign('moderator_tickets_updated_by_foreign');
			$table->dropForeign('moderator_tickets_user_id_foreign');
		});
	}

}
