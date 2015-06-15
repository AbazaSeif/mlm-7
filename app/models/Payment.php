<?php

class Payment extends \Eloquent {
	protected $guarded = array('payment_from', 'payment_to');
    protected $table = 'payment_method';
}