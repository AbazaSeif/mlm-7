<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->increments('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100);
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('phone',100);
            $table->enum('type', array(0,1));
            $table->enum('paypal', array('0',1))->default('0');
            $table->enum('solid_trust_pay', array('0',1))->default('0');
            $table->enum('payza', array('0',1))->default('0');
            $table->enum('others', array('0',1))->default('0');
            $table->text('description')->nullable();
            $table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
