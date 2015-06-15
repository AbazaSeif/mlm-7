<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('sender');
            $table->integer('receiver');
            $table->text('description');
            $table->integer('payment_id');
            $table->enum('seen_by_sender', array('0', '1'));
            $table->enum('seen_by_admin', array('0','1'));
            $table->enum('seen_by_receiver', array('0','1'));
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
