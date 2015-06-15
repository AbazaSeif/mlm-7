@extends('layouts.default')
@section('container')
    <p>First name: {{Auth::user()->first_name}}</p>
    <p>Last name: {{Auth::user()->last_name}}</p>
    <p>Email: {{Auth::user()->email}}</p>
    <p>Username: {{Auth::user()->username}}</p>
    <p>Phone: {{Auth::user()->phone}}</p>
    <p>User type name: {{Auth::user()->type == '1' ? "Paid" : "Normal"}} </p>
    <p>
        Payment Method(s):
            <ol>
                @if(Auth::user()->paypal == 1)
                    <li>Paypal</li>
                @endif
                @if(Auth::user()->payza == 1)
                    <li>Payza</li>
                @endif
                @if(Auth::user()->solid_trust_pay == 1)
                    <li>Solid Trust Pay</li>
                @endif
                @if(Auth::user()->others == 1)
                    <li>{{Auth::user()->description}}</li>
                @endif
            </ol>
    </p>
    <a class="btn btn-primary" href="/update/profile">Update profile</a>
@stop