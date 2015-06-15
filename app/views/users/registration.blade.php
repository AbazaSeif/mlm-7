@extends('layouts.default')
{{-- container load --}}
@section('container')
    @if($errors->any())
        <div class="panel panel-danger">
            <div class="panel-heading">Errors in fields</div>
            <div class="panel-body">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <div class="panel panel-info">
            <div class="panel-heading">Required fields</div>
            <div class="panel-body">
                <ol>
                    <li>Every input fields is required.</li>
                    <li>Username can contain alpha numeric characters, dot and underscore.</li>
                </ol>
            </div>
        </div>
    @endif
    {{Form::open(array('class' => 'form'))}}
    <legend>Registration</legend>
    <div class="col-xs-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" value = "{{Input::old('email')}}">
        </div>
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" value = "{{Input::old('username')}}" placeholder="Enter Username">
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter Password Confirmation">
        </div>
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" id="first_name" value = "{{Input::old('first_name')}}" placeholder="Enter First name">
        </div>
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" id="last_name" value = "{{Input::old('last_name')}}" placeholder="Enter Last name">
        </div>
        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone" value = "{{Input::old('phone')}}" placeholder="Enter Phone Number">
        </div>
        <div class="form-group {{ $errors->has('referred_by') ? 'has-error' : '' }}">
            <label for="referred_by">Referrer user</label>
            <input type="text" class="form-control" name="referred_by" value = "{{ Session::has('referredBy') ? Session::get('referredBy') : Input::old('referred_by') }}" id="referred_by" placeholder="Enter Referrer username" >
        </div>
        <div class="form-group">
            {{Form::label('payment_method', 'Payment Method')}}
            <select name="payment_method[]" id="payment_method" class="form-control" multiple>
                <option value="paypal">Paypal(E)</option>
                <option value="solid">Solid Trust Pay(E)</option>
                <option value="payza">Payza(Username)</option>
                <option value="others">Others</option>
            </select>
        </div>
        <div class="form-group">
            {{Form::label('other_method', 'Other method type(optional, REQUIRED if Others option is selected)')}}
            <input type="text" class="form-control" name="other_method" value="{{Input::old('other_method')}}" placeholder='Other payment method' />
        </div>
        <div class="form-group text-right">
            <input type="submit" class="btn btn-success" id="submit" value="Register" />
        </div>

    </div>
    {{Form::close()}}
    <div style='margin-top: 20px;'></div>
@stop