<?php

Validator::extend('username', function($attribute, $value){
    return preg_match('/^[A-Za-z0-9\._]+$/', $value);
});

Validator::extend('name', function($attribute, $value){
    return preg_match("/^[a-zA-Z\s]+$/", $value);
});

Validator::extend('phone', function($attribute, $value){
    return preg_match("/^(88)?[0-9]{11}$/", $value);
});