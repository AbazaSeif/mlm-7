@extends('layouts.default')
@section('container')
    <ol>
        @foreach(Payment::orderBy('updated_at', "DESC")->get() as $payment)
            <li><h3>Payment from <span class="text-danger">{{User::find($payment->payment_from)->first_name}} {{User::find($payment->payment_from)->last_name}}</span> to <span class="text-danger">{{User::find($payment->payment_to)->first_name}} {{User::find($payment->payment_to)->last_name}}</span> is {{ $payment->is_accepted == '0' ? 'waiting for approval' : 'received' }}.</h3></li>
        @endforeach
    </ol>
@stop