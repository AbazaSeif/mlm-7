<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title or 'Default' }} | MLM</title>
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/font-awesome.min.css" rel="stylesheet">
        @if(Request::path() == 'genealogy')
            <link href="/css/style.css" rel="stylesheet">
        @endif

        <style>
            body{
                margin-top: 70px;
            }
            .border-less tbody tr td, .border-less thead tr th {
                border: none;
            }
        </style>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns. -->
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsible">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Multi-level marketing</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="collapsible">
                    @if(strpos(Request::path(),'login') !== False )
                        <div class="collapse navbar-collapse pull-right" id="collapsible">
                            <p class="navbar-text"><a href="registration" class="navbar-link">Register as new member</a></p>
                        </div>
                    @elseif(strpos(Request::path(),'registration') !== False )
                        <div class="collapse navbar-collapse pull-right" id="collapsible">
                            <p class="navbar-text"><a href="login" class="navbar-link">Login as existing member</a></p>
                        </div>
                    @elseif(strpos(Request::path(),'reference') !== False )
                        <div class="collapse navbar-collapse pull-right" id="collapsible">
                            <p class="navbar-text">Reference | <a href="registration">Register</a> | <a href="login">Login</a> </p>
                        </div>
                    @else
                        @if(Auth::user()->type == '1')
                        <form class="navbar-form navbar-left" role="search" id="ref">
                            <div class="input-group">
                              <span class="input-group-addon">Reference Link</span>
                              <input type="text" id="reflink" class="form-control" value="{{url('reference')}}/{{Auth::user()->username}}">
                            </div>
                        </form>
                        @endif
                        <ul class="nav navbar-nav navbar-right">
                            <li {{Request::path() == '/' ? 'class = "active"': ''}}><a href="/">Home</a></li>
                            @if(Auth::user()->id == 1)
                                <li {{Request::path() == 'transactions' ? 'class = "active"': ''}}><a href="/transactions">Transactions</a></li>
                            @endif
                            @if(Auth::user()->id == 1)
                                <li {{Request::path() == 'genealogy' ? 'class = "active"': ''}}><a href="/genealogy">Genealogy</a></li>
                            @endif
                            @if(Auth::user()->type == 0 && Payment::where('payment_from', Auth::user()->id)->count() == 0)
                                <li {{Request::path() == 'gift' ? 'class = "active"': ''}}><a href="/gift">Send gift</a></li>
                            @endif
                            @if( Auth::user() && Auth::user()->type === '1')
                                <li {{Request::path() == 'community' ? 'class = "active"': ''}}><a href="/community">Community</a></li>
                            @endif
                            <li {{Request::path() == 'notifications' ? 'class = "active"': ''}}><a href="/notifications">Notifications <span class="badge">{{Notification::where('sender', Auth::user()->id)->where('seen_by_sender', '0')->count() + Notification::where('receiver', Auth::user()->id)->where('seen_by_receiver', '0')->count()}}</span></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Options <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/profile"><i class="fa fa-user"></i> Profile</a></li>
                                    <li><a href="/update/profile"><i class="fa fa-gear"></i> Update profile</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>
        <!-- container -->
        <div class="container">
            @yield('container')
        </div>
        <!-- container ends -->
        <!-- Scripts -->
        <script src="/js/jquery-1.11.1.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script>
            if(document.getElementById('ref') !== null){
                $("#ref").submit(function(e){
                    e.preventDefault();
                });
            }
            if(document.getElementById("reflink") !== null){
                $("#reflink").click(function(){
                    $(this).select();
                });
            }
        </script>
        @yield('scripts')
    </body>
</html>