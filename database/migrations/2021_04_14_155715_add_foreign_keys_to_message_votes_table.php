<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMessageVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('message_votes', function(Blueprint $table)
		{
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('message_votes', function(Blueprint $table)
		{
			$table->dropForeign('message_votes_message_id_foreign');
			$table->dropForeign('message_votes_user_id_foreign');
		});
	}

}
