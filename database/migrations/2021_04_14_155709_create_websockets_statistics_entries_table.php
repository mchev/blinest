<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsocketsStatisticsEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('websockets_statistics_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('app_id', 191);
			$table->integer('peak_connection_count');
			$table->integer('websocket_message_count');
			$table->integer('api_message_count');
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
		Schema::drop('websockets_statistics_entries');
	}

}
