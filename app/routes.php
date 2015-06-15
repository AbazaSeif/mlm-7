<?php

// dummy route
    Route::get('d', function(){
        return Hash::make('2');
    });
//dummy route ends


// Routes that need to have auth permissions
Route::group(array('before' => 'auth'), function(){
    Route::get('/', array(
        'as' => 'home',
        'uses' => 'HomeController@home'
    ));

    Route::get('transactions', array(
        'as' => 'transactions',
        'before' => 'admin',
        'uses' => 'UserController@transactions'
    ));
    Route::get("logout", array(
        'as' => 'logout',
        'uses' => "SessionController@logout"
    ));
    Route::get('/profile', array(
        'as' => 'profile',
        'uses' => 'UserController@profile'
    ));
    Route::get('/update/profile', array(
        'as' => 'update',
        'uses' => 'UserController@update'
    ));
    Route::post('update', array(
        'as' => 'ajaxPost',
        'before' => 'ajax-calls',
        'uses' => 'UserController@ajaxPost'
    ));
    Route::get('notifications', array(
        'as' => 'notifications',
        'uses' => 'UserController@notifications'
    ));
    Route::get('notifications/{notification_id}', array(
        'as' => 'particular',
        'uses' => 'UserController@specific'
    ));
    Route::get('genealogy/{id?}', array(
        'as' => 'genealogy',
        'before' => 'admin',
        'uses' => 'UserController@genealogy'
    ));
    Route::post('notifications/{notification_id}', array(
        'before' => 'csrf',
        'uses' => 'UserController@notificationData'
    ));
    Route::get('gift', array(
        'as' => 'gift',
        'before' => 'paid|restricted',
        'uses' => 'UserController@gift'
    ));
    Route::post('gift', array(
        'before' => 'csrf',
        'uses' => 'UserController@giftData'
    ));
    Route::get('community', array(
        'as' => 'community',
        'before' => 'user_type',
        'uses' => 'UserController@community'
    ));
    Route::get('*', function(){
        return "Something is wrong";
    });
});

// routes that need to have guest/ no login permissions
Route::group(array('before' => 'guest'), function(){
    Route::get('login', array(
        'as' => 'login',
        'uses' => 'SessionController@login'
    ));
    Route::post('login', array(
        'before' => 'csrf',
        'uses' => 'SessionController@loginData'
    ));
    Route::get('registration', array(
        'as' => 'registration',
        'uses' => 'UserController@registration'
    ));
    Route::post('registration', array(
        'before' => 'csrf',
        'uses' => 'UserController@registrationData'
    ));
    Route::get('reference/{username?}', array(
        'as' => 'reference',
        'uses' => 'UserController@reference'
    ));
});

