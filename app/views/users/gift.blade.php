@extends('layouts.default')
@if(Auth::user()->id == 1)
    @section('container')
        <h3 class="text-warning text-center">You can't send gift</h3>
    @stop
@else
    @section('container')
        @if(Session::has('success'))
        <div class="panel panel-success">
            <div class="panel-heading">Payment Successful!</div>
            <div class="panel-body">
                Payment has been successfully made, waiting for approval.
            </div>
        </div>
        @endif
        @if($errors->any())
        <div class="panel panel-danger">
            <div class="panel-heading">Errors occurred</div>
            <div class="panel-body">
                <ol>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ol>
            </div>
        </div>
        @endif
        <h4 class="page-header">Send gift</h4>
        <div class="col-xs-6 pull-left">
            <h4 class="text-primary text-center">You're invited by: <span class="text-success">{{User::find(Session::get('ref_id'))->first_name}} {{User::find(Session::get('ref_id'))->last_name}}</span></h4>
        </div>
        <div class="col-xs-6">
            <h4 class="text-primary text-center">Payment will be received by: <span class="text-success"> {{User::find(Session::get('tree.node'))->first_name}} {{User::find(Session::get('tree.node'))->last_name}}</span></h4>
        </div>
        {{Form::open(array('role' => 'form'))}}
            <legend>Credentials</legend>

            <div class="col-xs-6">
                 <div class="form-group">
                    {{Form::label('payment_method', 'Payment Method')}}
                    <select name="payment_method[]" id="payment_method" class="form-control" multiple>
                        @if(User::find(Session::get('tree.node'))->paypal == '1')
                            <option value="paypal">Paypal(E)</option>
                        @endif
                        @if(User::find(Session::get('tree.node'))->solid_trust_pay == '1')
                            <option value="solid">Solid Trust Pay(E)</option>
                        @endif
                        @if(User::find(Session::get('tree.node'))->payza == '1')
                            <option value="payza">Payza(Username)</option>
                        @endif
                        @if(User::find(Session::get('tree.node'))->others == '1')
                            <option value="others">{{User::find(Session::get('tree.node'))->description}}</option>
                        @endif
                    </select>
                </div>
                {{--<div class="form-group">
                    {{Form::label('other_method', 'Other method type(optional, if Others selected)')}}
                    <textarea name="other_method" class="form-control" rows="3"></textarea>
                </div>--}}
                <div class="form-group pull-right">
                    <input type="submit" value="Pay" class="btn btn-success" />
                </div>
            </div>
        {{Form::close()}}
        <div class="col-xs-6">

        </div>
    @stop
@endif