<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLabVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lab_votes', function(Blueprint $table)
		{
			$table->foreign('lab_id')->references('id')->on('labs')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lab_votes', function(Blueprint $table)
		{
			$table->dropForeign('lab_votes_lab_id_foreign');
			$table->dropForeign('lab_votes_user_id_foreign');
		});
	}

}
