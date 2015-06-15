<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRelationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_relation', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('left_hand')->nullable();
            $table->integer('right_hand')->nullable();
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
		Schema::drop('user_relation');
	}

}
