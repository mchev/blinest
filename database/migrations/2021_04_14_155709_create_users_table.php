<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191)->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps(10);
			$table->string('provider', 191)->nullable();
			$table->string('provider_id', 191)->nullable();
			$table->dateTime('banned_until')->nullable();
			$table->dateTime('last_login_at')->nullable();
			$table->string('last_login_ip', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
