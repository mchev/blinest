<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('sender_id')->unsigned()->nullable()->index('messages_sender_id_foreign');
			$table->bigInteger('game_id')->unsigned()->index('messages_game_id_foreign');
			$table->timestamps(10);
			$table->string('sender_name');
			$table->string('sender_ip', 45);
			$table->string('message');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages');
	}

}
