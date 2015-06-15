@extends('layouts.default')
@section('container')
    <div class="tree">
        <ul>
            @if(empty($tree[0]) == false)
                <li>
                    <a href="#">{{User::find($tree[0])->first_name}} {{User::find($tree[0])->last_name}}</a>
                    <ul>
                        @if(empty($tree[1]) == false)
                            <li>
                                <a href="#">{{User::find($tree[1])->first_name}} {{User::find($tree[1])->last_name}}</a>
                                <ul>
                                    @if(empty($tree[3]) == false)
                                        <li><a href="#">{{User::find($tree[3])->first_name}} {{User::find($tree[3])->last_name}}</a>
                                            <ul>
                                                @if(empty($tree[7]) == false)
                                                    <li><a href="#">{{User::find($tree[7])->first_name}} {{User::find($tree[7])->last_name}}</a></li>
                                                @endif
                                                @if(empty($tree[8]) == false)
                                                    <li><a href="#">{{User::find($tree[8])->first_name}} {{User::find($tree[8])->last_name}}</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if(empty($tree[4]) == false)
                                        <li><a href="#">{{User::find($tree[4])->first_name}} {{User::find($tree[4])->last_name}}</a>
                                            <ul>
                                                @if(empty($tree[11]) == false)
                                                    <li><a href="#">{{User::find($tree[11])->first_name}} {{User::find($tree[11])->last_name}}</a></li>
                                                @endif
                                                @if(empty($tree[10]) == false)
                                                    <li><a href="#">{{User::find($tree[10])->first_name}} {{User::find($tree[10])->last_name}}</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if(empty($tree[2]) == false)
                            <li>
                                <a href="#">{{User::find($tree[2])->first_name}} {{User::find($tree[2])->last_name}}</a>
                                @if(empty($tree[5]) == false)
                                    <ul>
                                        <li><a href="#">{{User::find($tree[5])->first_name}} {{User::find($tree[5])->last_name}}</a>
                                            <ul>
                                                @if(empty($tree[9]) == false)
                                                    <li><a href="#">{{User::find($tree[9])->first_name}} {{User::find($tree[9])->last_name}}</a></li>
                                                @endif
                                                @if(empty($tree[12]) == false)
                                                    <li><a href="#">{{User::find($tree[12])->first_name}} {{User::find($tree[12])->last_name}}</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                @endif
                                @if(empty($tree[6]) == false)
                                        <li><a href="#">{{User::find($tree[6])->first_name}} {{User::find($tree[6])->last_name}}</a>
                                            <ul>
                                                @if(empty($tree[13]) == false)
                                                    <li><a href="#">{{User::find($tree[13])->first_name}} {{User::find($tree[13])->last_name}}</a></li>
                                                @endif
                                                @if(empty($tree[14]) == false)
                                                    <li><a href="#">{{User::find($tree[14])->first_name}} {{User::find($tree[14])->last_name}}</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    </ul>
                                @endif
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
@stop