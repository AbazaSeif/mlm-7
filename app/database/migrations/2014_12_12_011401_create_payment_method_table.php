<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentMethodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_method', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('payment_from');
            $table->integer('payment_to');
            $table->enum('paypal', array('0',1))->default('0');
            $table->enum('solid_trust_pay', array('0',1))->default('0');
            $table->enum('payza', array('0',1))->default('0');
            $table->enum('others', array('0',1))->default('0');
            $table->enum('is_accepted', array('0', '1'));
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
		Schema::drop('payment_method');
	}

}
