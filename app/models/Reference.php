<?php

class Reference extends \Eloquent {
	protected $guarded = array('user_id', 'referredBy');
    protected $table = 'referred';

}