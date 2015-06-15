@extends('layouts.default')
@section('container')
@if(!empty($message) && $message == 'success')
    <div class="panel panel-success">
        <div class="panel-heading">Payment received</div>
        <div class="panel-body">Payment is received</div>
    </div>
@endif
<ol>
    @foreach($notifications as $notification)
        <li>{{$notification->description}} <a href="notifications/{{$notification->id}}" class="btn btn-sm">Check</a></li>
    @endforeach
</ol>
@stop