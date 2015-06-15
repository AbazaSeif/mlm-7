@extends('layouts.default')
{{-- container loader --}}
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
    @endif
    {{Form::open(array('class' => 'form-horizontal', 'role'=>'form'))}}
        <legend>Login</legend>
        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-6">
                {{Form::text('username', null, array('class'=>'form-control', 'id' => 'username', 'placeholder' => 'Username')) }}
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-6">
                {{Form::password('password', array('class'=>'form-control', 'id'=>'password', 'placeholder' => 'Password'))}}
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <input type="submit" class="btn btn-success" value="Login" />
            </div>
        </div>
    {{Form::close()}}
@stop