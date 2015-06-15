<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /user
	 *
	 * @return Response
	 */
	public function registration()
	{
		return View::make('users.registration')->with('title', 'Registration');
	}

    public function registrationData()
    {
        $total = 0;
        $rules = array(
            'email' => array('required', 'email', 'unique:users,email'),
            'username' => array('required', 'username', 'unique:users,username'),
            'password' => array('required', 'alpha_num', 'confirmed', 'min:5'),
            'first_name' => array('required', 'name'),
            'last_name' => array('required', 'name'),
            'phone' => array('required', 'phone'),
            'payment_method' => array('required'),

        );
        if(User::all()->count() !== 0){
            $total = 1; // more than one
            $rules['referred_by'] = array('required', 'username', 'exists:users,username');
        }
        $validation = Validator::make(Input::all(), $rules);
        if($validation->fails()){
            return Redirect::back()->withInput()->withErrors($validation);
        }
        if(array_key_exists('referred_by', $rules)){
            $valid = User::where('username', Input::get('referred_by'))->first();
            if($valid->type == '0'){
                return Redirect::back()->withInput()->withErrors(array('error' => "Reference user error. Username doesn't exist"));
            }
        }
        // all the validation is ok, now insert those values into db
        $description = Input::get('other_method');
        $user = User::create(array(
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'email' => Input::get('email'),
            'username' => Input::get('username'),
            'password' => Hash::make(Input::get('password')),
            'phone' => Input::get('phone'),
            'paypal' => array_keys(Input::get('payment_method'), "paypal") ?  '1' : '0',
            'payza' => array_keys(Input::get('payment_method'), "payza") ?  '1' : '0',
            'solid_trust_pay' => array_keys(Input::get('payment_method'), "solid") ?  '1' : '0',
            'others' => array_keys(Input::get('payment_method'), "others") ?  '1' : '0',
            'description' => empty($description) ? null : Input::get('other_method'),
        ));
        $user->type = $total === 0 ? 1 : 0;
        $user->save();
        if(User::all()->count() > 1){
            $referredBy = User::where('username', "LIKE", Input::get('referred_by'))->first();
            $reference = Reference::create(array());
            $reference->user_id = $user->id;
            $reference->referredBy = $referredBy->id;
            $reference->save();
        }
        $relation = Relationship::create(array());
        $relation->parent_id = $user->id;
        $relation->save();
        if(Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')))){
            return Redirect::to('/');
        }
    }

    public function reference($username=null)
    {
        if(empty($username)){
            return View::make('users.reference')->withTitle('Reference');
        }
        // check it's username
        $validation = Validator::make(
            array(
                'username' => $username
            ),
            array(
                'username' => array('required', 'username')
            )
        );
        // username validation fails
        if($validation->fails())
            return View::make('users.reference')->withTitle('Reference');
        $search = User::where('username', 'like', $username)->where('type', '1')->count();
        if($search == 0)
            return View::make('users.reference')->withTitle('Reference');
        return Redirect::to('registration')->with('referredBy', $username);
    }

    public function notifications(){
        if(Auth::user()->id != 1)
            $notifications = Notification::where('sender', Auth::user()->id)->orWhere('receiver', Auth::user()->id)->orderBy("updated_at", "DESC")->get();
        else
            $notifications = Notification::orderBy('created_at', "DESC")->get();
        foreach($notifications as $notification){
            $notif = Notification::find($notification->id);
            if(Auth::user()->id == 1){
                $notif->seen_by_admin = 1;
                $notif->save();
            }
            if($notification->sender == Auth::user()->id){
                $notif->seen_by_sender = '1';
                $notif->save();
            } else{
                $notif->seen_by_receiver = '1';
                $notif->save();
            }
        }
        return View::make('users.notifications')->withTitle('Notifications')->with('notifications', $notifications);
    }

    public function specific($notification_id){
        $notification = Notification::find($notification_id);
        if($notification->sender == Auth::user()->id || $notification->receiver == Auth::user()->id || Auth::user()->id == 1){
            return View::make('users.specific')->withTitle('Notification')->with('notification', $notification);
        } else{
            return View::make('users.specific')->withTitle('Notification | Error')->with('error', true);
        }
    }

    public function notificationData($notification_id){
        if(!is_numeric($notification_id)){
            return View::make('users.notificationerror')->withTitle("Invalid notification")->with('message', 'id');
        } elseif(is_numeric($notification_id)){
            $n = Notification::find($notification_id);
            if(Auth::user()->id != $n->receiver){
                return View::make('users.notificationerror')->withTitle("Invalid permission")->with('message', 'permission');
            }
        }
        $notification = Notification::find($notification_id);
        $payment_id = $notification->payment_id;
        $payment = Payment::find($payment_id);
        $payment->is_accepted = '1';
        $payment->save();
        $story = array(
            'sender' => Auth::user()->id,
            'receiver' => Notification::find($notification_id)->sender,
            'description' => 'Payment made by '. User::find($notification->sender)->first_name. " ".User::find($notification->sender)->last_name." is now received by ". User::find($notification->receiver)->first_name. " ".User::find($notification->receiver)->last_name,
            'payment_id' => $payment_id,
            'seen_by_sender' => '0',
            'seen_by_receiver' => '0',
            'seen_by_admin' => '0'
        );
        Notification::create($story)->save();
        $user = User::find(Notification::find($notification_id)->sender);
        $user->type = '1';
        $user->save();

        $user_id = Notification::find($notification_id)->sender;
        $referrer = Reference::where('user_id', $user_id)->first();
        $referredBy = $referrer->referredBy;
        $values = Hierarchy::getTree(array($referredBy), true);
        $relation = Relationship::where('parent_id',$values['node'])->first();
        if($values['position'] == 'right_hand'){
            $relation->right_hand = $user_id;
        } else{
            $relation->left_hand = $user_id;
        }
        $relation->save();
        // check who'll receive the next notifications
        foreach(Payment::where('payment_to', Auth::user()->id)->where('is_accepted', '0')->get() as $payment){
            $user_id = $payment->payment_from;
            $referrer = Reference::where('user_id', $user_id)->first();
            $referredBy = $referrer->referredBy;
            $values = Hierarchy::getTree(array($referredBy), true);
            $node = $values['node'];
            //update payment_to
            $payment->payment_to = $node;
            $payment->save();
            $notification = Notification::where('payment_id', $payment->id)->first();
            $story = array(
                'receiver' => $node,
                'description' => "User ".User::find($payment->payment_from)->first_name." ".User::find($payment->payment_from)->last_name ." sent payment to ". User::find($node)->first_name." ".User::find($node)->last_name ,
                'seen_by_sender' => '0',
                'seen_by_admin' => '0',
                'seen_by_receiver' => '0'
            );
            $notification->update($story);
        }
        /*if($values['position'] == 'right_hand'){
            $notifications = Notification::where('receiver', Auth::user()->id)->orderBy('updated_at', 'DESC')->take(1000)->skip(2)->get();
            $first_notification = $notifications[0];
            $payment_from = $first_notification->sender;
            //$user_id = Notification::find($notification_id)->sender;
            $referrer = Reference::where('user_id', $payment_from)->first();
            $referredBy = $referrer->referredBy;
            $values = Hierarchy::getTree(array($referredBy), true);
            foreach($notifications as $notification){
                $notification->description = "User ".User::find($notification->sender)->first_name." ".User::find($notification->sender)->last_name." sent payment to ". User::find($values['node'])->first_name." ".User::find($values['node'])->last_name;
                $payment = Payment::find($notification->payment_id);
                $payment->payment_to = $values['node'];
                $payment->save();
                $notification->receiver = $values['node'];
                $notification->save();
            }
        }*/
        return Redirect::to('notifications')->with('message', 'success');
    }

    public function gift(){
        if(Auth::user()->id != 1){
            $user_id = Auth::user()->id;
            $referrer = Reference::where('user_id', $user_id)->first();
            $referredBy = $referrer->referredBy;
            Session::put('ref_id', $referredBy);
            $values = Hierarchy::getTree(array($referredBy), true);
            Session::put('tree', $values);
        }

        return View::make('users.gift')->withTitle('Send Gift');
    }

    public function giftData(){
        $rules = array(
            'payment_method' => array('required')
        );
        $validation = Validator::make(Input::all(), $rules);
        if($validation->fails()){
            return Redirect::back()->withInput()->withErrors($validation);
        }
        $input = array(
            'is_accepted' => '0',
        );
        $input['paypal'] = array_keys(Input::get('payment_method'), "paypal") ?  '1' : '0';
        $input['payza'] = array_keys(Input::get('payment_method'), "payza") ?  '1' : '0';
        $input['solid_trust_pay'] = array_keys(Input::get('payment_method'), "solid") ?  '1' : '0';
        if(array_diff(Input::get('payment_method'), array('paypal', 'payza', 'solid')) == true){
            $input['others'] = '1';
        } else{
            $input['others'] = '0';
        }
        $payment = Payment::create($input);
        $payment->payment_from = Auth::user()->id;
        $payment->payment_to = Session::get('tree.node');
        $payment->save();

        // get the place where the user will be saved.
        /*$user_id = Auth::user()->id;
        $referrer = Reference::where('user_id', $user_id)->first();
        $referredBy = $referrer->referredBy;
        $values = Hierarchy::getTree(array($referredBy), true);
        $relation = Relationship::where('parent_id',$values['node'])->first();
        if($values['position'] == 'right_hand'){
            $relation->right_hand = $user_id;
        } else{
            $relation->left_hand = $user_id;
        }
        $relation->save();
        */

        $notification = Notification::create(array(
            'sender' => Auth::user()->id,
            'receiver' => Session::get('tree.node'),
            'description' => "User ".Auth::user()->first_name." ".Auth::user()->last_name ." sent payment to ". User::find(Session::get('tree.node'))->first_name." ".User::find(Session::get('tree.node'))->last_name ,
            'payment_id' => $payment->id,
            'seen_by_sender' => '0',
            'seen_by_admin' => '0',
            'seen_by_receiver' => '0'
        ));
        $notification->save();
        Session::flash('success', 'yes');
        return Redirect::to('gift');

    }

    public function community(){
        return View::make('users.community')->withTitle('Community');
    }

    public function transactions(){
        return View::make('users.transactions')->withTitle("Transactions");
    }

    public function profile(){
        return View::make('users.profile')->withTitle('Profile');
    }

    public function update(){
        return View::make('users.update')->withTitle("Update");
    }

    public function ajaxPost(){
        //return Input::all();
        //return Response::make(array('error' => "No field is available"), 404);
        $validUpdateTerms = array(
            'first_name',
            'last_name',
            'phone',
            'email',
            "old_password",
            "password",
            "password_confirmation",
            "payment_method",
        );
        if(!in_array(Input::get('update-type'), $validUpdateTerms)){
            $return = array(
                'success' => false,
                'error' => array(
                    'Fields are not available to update'
                )
            );
            return Response::json($return);
        }
        $field = Input::get('update-type');
        $rules = array(
            'email' => array('required', 'email', 'unique:users,email'),
            'username' => array('required', 'username', 'unique:users,username'),
            'password' => array('required', 'alpha_num', 'confirmed', 'min:5'),
            'first_name' => array('required', 'name'),
            'last_name' => array('required', 'name'),
            'phone' => array('required', 'phone'),
            'payment_method' => array('required'),

        );
        if($field == 'first_name'){
            $validation = Validator::make(array('first_name' => Input::get('first_name')), array( 'first_name' => array('required', 'name')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $user->first_name = Input::get('first_name');
            $user->save();
            return Response::json(array(
                'success' => true
            ));
        }elseif($field == 'last_name'){
            $validation = Validator::make(array('last_name' => Input::get('last_name')), array( 'last_name' => array('required', 'name')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $user->last_name = Input::get('last_name');
            $user->save();
            return Response::json(array(
                'success' => true
            ));
        }elseif($field == 'email'){
            $validation = Validator::make(array('email' => Input::get('email')), array( 'email' => array('required', 'email', 'unique:users,email')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $user->email = Input::get('email');
            $user->save();
            return Response::json(array(
                'success' => true
            ));
        }elseif($field == 'phone'){
            $validation = Validator::make(array('phone' => Input::get('phone')), array( 'phone' => array('required', 'phone')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $user->phone = Input::get('phone');
            $user->save();
            return Response::json(array(
                'success' => true
            ));
        }elseif($field == 'password'){
            $credentials = array(
                'username' => Auth::user()->username,
                'password' => Input::get('old_password')
            );
            if(!Auth::validate($credentials)){
                $return = array(
                    'success' => false,
                    'errors' => array(
                        'Old password is not matching'
                    )
                );
                return Response::json($return);
            }
            $validation = Validator::make(Input::all(), array( 'password' => array('required', 'alpha_num', 'confirmed', 'min:5')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return Response::json(array(
                'success' => true
            ));
        }elseif($field == 'payment_method'){
            $validation = Validator::make(Input::all(), array('payment_method' => array('required')));
            if($validation->fails()){
                $return = array(
                    'success' => false,
                    'errors' => $validation->errors()->toArray()
                );
                return Response::json($return);
            }
            $user = User::find(Auth::user()->id);
            $description = Input::get('other_method');
            $input = array(
                'paypal' => array_keys(Input::get('payment_method'), "paypal") ?  '1' : '0',
                'payza' => array_keys(Input::get('payment_method'), "payza") ?  '1' : '0',
                'solid_trust_pay' => array_keys(Input::get('payment_method'), "solid") ?  '1' : '0',
                'others' => array_keys(Input::get('payment_method'), "others") ?  '1' : '0',
                'description' => empty($description) ? null : Input::get('other_method')
            );
            $user->update($input);
            return Response::json(array(
                'success' => true
            ));
        }
    }

    public function genealogy($id = null){
        if($id==null){
            $id = 1;
        }
        $tree = array();
        $value = Hierarchy::genealogy(array($id), 1, $tree);
        return View::make('users.genealogy')->withTitle('Genealogy')->with('tree', $value);

    }

}