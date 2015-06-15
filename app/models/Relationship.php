<?php

class Relationship extends \Eloquent {
	protected $guarded = array('parent_id', 'left_hand', 'right_hand');
    protected $table = 'user_relation';
    protected $hidden = array('created_at', 'updated_at');
}