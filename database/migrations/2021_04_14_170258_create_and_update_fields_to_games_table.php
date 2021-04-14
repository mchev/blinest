<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAndUpdateFieldsToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('discord_webhook_url')->nullable();
            $table->string('color', 6)->nullable();
            $table->boolean('pro')->nullable();
            $table->string('description', 255)->change();
            $table->dropColumn('effect_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('discord_webhook_url');
            $table->dropColumn('color');
            $table->dropColumn('pro');
            $table->text('description')->change();
            $table->integer('effect_id')->nullable();
        });
    }
}
