@extends('layouts.default')
@if($message == 'permission')
    <h4 class="text-center">You can't accept this payment</h4>
@elseif($message == 'id')
    <h4 class="text-center">It's not a valid notification</h4>
@endif