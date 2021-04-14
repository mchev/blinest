<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('labs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('labs_user_id_foreign');
			$table->bigInteger('parent_id')->unsigned()->nullable()->index('labs_parent_id_foreign');
			$table->string('theme', 191);
			$table->string('type', 191);
			$table->text('body');
			$table->dateTime('planned_at')->nullable();
			$table->dateTime('opened_at')->nullable();
			$table->dateTime('closed_at')->nullable();
			$table->dateTime('rejected_at')->nullable();
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
		Schema::drop('labs');
	}

}
