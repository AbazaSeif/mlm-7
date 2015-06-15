<?php

class SessionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /session
	 *
	 * @return Response
	 */
	public function login()
	{
		return View::make('sessions.login')->with('title', 'Login');
	}

    public function loginData()
    {
        $rules = array(
            'username' => array('required', 'username'),
            'password' => array('required')
        );
        $validation = Validator::make(Input::all(), $rules);
        if($validation->fails()){
            return Redirect::back()->withInput()->withErrors($validation);
        }
        if(Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')))){
            return Redirect::to('/');
        } else{
            return Redirect::back()->withInput()->withErrors(array('error' => "Username or Password is incorrect."));
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }

}