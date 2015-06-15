@extends('layouts.default')
@section('container')
@if(empty($error))
    <h4 class="page-header">{{$notification->description}}</h4>
    {{-- Payment is received, which means that the notification will be reversed --}}
    @if(strpos($notification->description, "receive") !== false)
        <p>Payment received by: {{User::find($notification->sender)->first_name}} {{User::find($notification->sender)->last_name}}</p>
        <p>Payment released by: {{User::find($notification->receiver)->first_name}} {{User::find($notification->receiver)->last_name}}</p>
    @else
        <p>Payment released by: {{User::find($notification->sender)->first_name}} {{User::find($notification->sender)->last_name}}</p>
        <p>Payment will be received by: {{User::find($notification->receiver)->first_name}} {{User::find($notification->receiver)->last_name}}</p>
    @endif
    <p>
        Payment Method: <ol>
                            @if(Payment::find($notification->payment_id)->paypal == '1')
                                <li>Paypal</li>
                            @endif

                            @if(Payment::find($notification->payment_id)->payza == '1')
                                <li>Payza</li>
                            @endif
                            @if(Payment::find($notification->payment_id)->solid == '1')
                                <li>Solid Trust Pay</li>
                            @endif
                            @if(Payment::find($notification->payment_id)->others == '1')
                                <li>{{User::find(Payment::find($notification->payment_id)->payment_to)->description}}</li>
                            @endif

                        </ol>
    </p>
    @if($notification->receiver == Auth::user()->id && strpos($notification->description, "receive") === false && Payment::find($notification->payment_id)->is_accepted == '0')
        {{Form::open(array())}}
            <input type="submit" class="btn btn-primary" value="Accept" />
        {{Form::close()}}
    @endif
@elseif(!empty($error) && $error == true)
    <h2 class="page-header">You're not eligible to view this notification</h2>
@endif
@stop