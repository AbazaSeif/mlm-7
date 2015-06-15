@extends('layouts.default')
@section('container')
    <div class="panel panel-success" id="success">
        <div class="panel-heading">
            Success
            <button type="button" class="close panel-close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        </div>
        <div class="panel-body">Operation was successful</div>
    </div>
    <div class="panel panel-danger" id="error">
        <div class="panel-heading">
            Error
            <button type="button" class="close panel-close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        </div>
        <div class="panel-body"><ol id="etxt"></ol></div>
    </div>

    <h3 class="page-header">Update your profile</h3>
    <div class="well well-sm">
        First name:&nbsp;&nbsp;&nbsp;{{Auth::user()->first_name}}
        <a data-action="first_name" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
    </div>
    <div class="well well-sm">
        Last name:&nbsp;&nbsp;&nbsp;{{Auth::user()->last_name}}
        <a data-action="last_name" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
    </div>
    <div class="well well-sm">
        Email:&nbsp;&nbsp;&nbsp;{{Auth::user()->email}}
        <a data-action="email" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
    </div>
    <div class="well well-sm">
        Phone:&nbsp;&nbsp;&nbsp;{{Auth::user()->phone}}
        <a data-action="phone" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
    </div>
    <div class="well well-sm">
            Password:&nbsp;&nbsp;&nbsp;******
            <a data-action="password" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
        </div>
    <div class="well well-sm">
        Payment Method(s):&nbsp;&nbsp;&nbsp;
        <a data-action="payment_method" href="#" data-loadable="no" class="load-modal pull-right">Update</a>
        <ol>
            @if(Auth::user()->paypal)
                <li>Paypal</li>
            @endif
            @if(Auth::user()->payza)
                <li>Payza</li>
            @endif
            @if(Auth::user()->solid_trust_pay)
                <li>Solid Trust Pay</li>
            @endif
            @if(Auth::user()->others)
                <li>{{Auth::user()->description}}</li>
            @endif
        </ol>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="modalLabel">Update <span id="action"></span></h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="update-form">
                        <input type="hidden" value="" name="update-type" id="update-type" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#success").hide();
            $("#error").hide();
        });
        $(".load-modal").click(function(e){
            e.preventDefault();
            if($(this).attr("data-action") == "first_name"){
                $("#action").text("First name");
                $("#update-type").val("first_name");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "first_name"
                        }
                    ).text("First name")
                ).append(
                    $("<input>", {
                            type: "text",
                            class: "form-control",
                            id: "first_name",
                            name: "first_name",
                            placeholder: "First name"
                        })
                ).appendTo("#update-form");
            } else if($(this).attr("data-action") == "last_name"){
                $("#action").text("Last name");
                $("#update-type").val("last_name");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "last_name"
                        }
                    ).text("Last name")
                ).append(
                    $("<input>", {
                            type: "text",
                            class: "form-control",
                            id: "last_name",
                            name: "last_name",
                            placeholder: "Last name"
                        })
                ).appendTo("#update-form");
            } else if($(this).attr("data-action") == "phone"){
                $("#action").text("Phone");
                $("#update-type").val("phone");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "phone"
                        }
                    ).text("Phone")
                ).append(
                    $("<input>", {
                            type: "text",
                            class: "form-control",
                            id: "phone",
                            name: "phone",
                            placeholder: "Phone"
                        })
                ).appendTo("#update-form");
            } else if($(this).attr("data-action") == "password"){
                $("#action").text("Password");
                $("#update-type").val("password");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "old_password"
                        }
                    ).text("Old password")
                ).append(
                    $("<input>", {
                            type: "password",
                            class: "form-control",
                            id: "old_password",
                            name: "old_password",
                            placeholder: "Old password"
                        })
                ).appendTo("#update-form");

                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "password"
                        }
                    ).text("New password")
                ).append(
                    $("<input>", {
                            type: "password",
                            class: "form-control",
                            id: "password",
                            name: "password",
                            placeholder: "New password"
                        })
                ).appendTo("#update-form");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "password_confirmation"
                        }
                    ).text("Confirm password")
                ).append(
                    $("<input>", {
                            type: "password",
                            class: "form-control",
                            id: "password_confirmation",
                            name: "password_confirmation",
                            placeholder: "Confirm password"
                        })
                ).appendTo("#update-form");
            } else if($(this).attr("data-action") == "email"){
                $("#action").text("Email");
                $("#update-type").val("email");
                $("<div>", {class: "form-group new"}).append(
                    $("<label>", {
                        for: "email"
                        }
                    ).text("Email")
                ).append(
                    $("<input>", {
                            type: "text",
                            class: "form-control",
                            id: "email",
                            name: "email",
                            placeholder: "Email"
                        })
                ).appendTo("#update-form");
            } else if($(this).attr("data-action") == "payment_method"){
                $("#action").text("Payment Method");
                $("#update-type").val("payment_method");
                    $("<div>", {class: "form-group new"}).append(
                        $("<label>", {
                            for: "payment_method"
                            }
                        ).text("Payment Method(s)")
                    ).append(
                        $("<select>", {
                                class: "form-control",
                                id: "payment_method",
                                name: "payment_method[]",
                                multiple: "multiple"
                        }).append(
                            $("<option>",{
                                    value: "paypal",
                                    text: "Paypal(E)"
                                }
                        )).append(
                            $("<option>",{
                                    value: "solid",
                                    text: "Solid Trust Pay(E)"
                                }
                        )).append(
                            $("<option>",{
                                    value: "payza",
                                    text: "Payza(Username)"
                                })
                        ).append(
                            $("<option>",{
                                    value: "others",
                                    text: "Others"
                            })
                        )
                    ).append(
                        $("<div>",{
                            class: "form-group"
                        }).append(
                            $("<label>",{
                                for: "other_method",
                                text: "Other method type(optional, REQUIRED if Others option is selected)"
                            })
                        ).append(
                            $("<input>",{
                                type: "text",
                                class: "form-control",
                                id: "other_method",
                                name: "other_method",
                                placeholder: "Other Payment Method"
                            })
                        )
                    ).appendTo("#update-form");
            };
            $("#modal").modal();
        });
        $(".panel-close").click(function(e){
            e.preventDefault();
            $(this).parent().parent().hide();
        });
        $("#modal").on('hidden.bs.modal', function (e) {
            $(".new").remove();
        });
        $("#update-form").on("submit", function(e){
            e.preventDefault();
            $("#update").click();
        });
        $("#update").on("click", function(){
            $("#success").hide();
            $("#error").hide();
            $data = new Object();
            $('form#update-form :input').each(function(i, v) {
                $data[$(this).attr('name')] = $(this).val();
            });
            $.ajax({
                url: '/update',
                type: 'post',
                dataType: 'json',
                data: $data,
                statusCode: {
                    404: function (response) {
                        console.log(response);
                    },
                    500: function (response) {
                        $("#etxt").text(response.responseJSON.error);
                        console.log(response);
                    }
                },

                success: function(data) {
                    if(data.success == true){
                        $("#success").show();
                        $("#modal").modal('hide');
                    } else{
                        $("#etxt").empty();
                        $.each(data.errors, function(index, error){
                            $("<li>",{
                                text: error
                            }).appendTo("#etxt");
                        });
                        $("#modal").modal('hide');
                        $("#error").show();
                    }
                }
            });
        });
    </script>
@stop