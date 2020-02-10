<!DOCTYPE html>
<html>
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">


    <title>Big Truck</title>
    {{--<link rel="shortcut icon" href="{{asset('img/images/bigtruck-book.png')}}" type="image/x-icon">--}}
    {{--<link rel="icon" href="{{asset('img/images/bigtruck-book.png')}}" type="image/x-icon">--}}
    <link rel="icon" type="image/png" href="{{asset('img/images/favicon-16x16.png')}}" sizes="16x16" />

    <link rel="stylesheet" type="text/css" href="{{asset('lib/stroke-7/style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('lib/jquery.nanoscroller/css/nanoscroller.css')}}"><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="{{asset('lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}">
        <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css">
        <script src="{{asset('lib/jquery/jquery.min.js')}}" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('lib/datetimepicker/css/bootstrap-datetimepicker.min.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('lib/select2/css/select2.min.css')}}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('lib/bootstrap-slider/css/bootstrap-slider.css')}}"/>



</head>

<header>

    <div class="am-wrapper">
    <nav class="navbar navbar-default navbar-fixed-top am-top-header">

        <div class="container-fluid">
            <div class="navbar-header">
                <div class="page-title"><span>Dashboard</span></div><a href="{{url('dashboard')}}" class="am-toggle-left-sidebar navbar-toggle collapsed"><span class="icon-bar"><span></span><span></span><span></span></span></a><img src = "{{asset('img/bigtruck-logo.png')}}" class=""></img>
            </div>
            <div id="am-navbar-collapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav am-user-nav">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><img src="{{asset('img/profile-default-male.png')}}"><span class="user-name">Samantha Amaretti</span><span class="angle-down s7-angle-down"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="#"> <span class="icon s7-user"></span>My profile</a></li>
                            <li><a href="#"> <span class="icon s7-config"></span>Settings</a></li>
                            <li><a href="#"> <span class="icon s7-help1"></span>Help</a></li>
                            <li><a href="{{url('logout')}}"> <span class="icon s7-power"></span>Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav am-top-menu">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle">Master <span class="angle-down s7-angle-down"></span></a>
                        <ul role="menu" class="dropdown-menu">

                            <li><a href="{{url('list-truck-type')}}">Truck Type</a></li>
                            <li><a href="{{url('list-truck-capacity')}}">Truck Capacity</a></li>
                            <li><a href="{{url('list-truck-units')}}">Truck Units</a></li>
                            <li><a href="{{url('list-truck-fueltype')}}">Truck Fuel Type</a></li>
                            <li><a href="{{url('list-truck-documenttype')}}">Truck Document</a></li>
                            <li><a href="{{url('list-truck-period')}}">Truck Period</a></li>
                            <li><a href="{{url('list-truck-subscription')}}">Truck Subscription</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle">User <span class="angle-down s7-angle-down"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="{{url('add-user')}}">User</a></li>
                            <li><a href="#">User Role</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle">UserForm <span class="angle-down s7-angle-down"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="{{url('user-list')}}">Userlist</a></li>
                            <li><a href="{{('add-user')}}">AddUser</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
        <nav class="am-right-sidebar">
            <div class="sb-content">
                <div class="user-info"><img src="{{asset('img/profile-default-male.png')}}"><span class="name">Samantha Amaretti<span class="status"></span></span><span class="position">Art Director</span></div>
                <div class="tab-navigation">
                    <ul role="tablist" class="nav nav-tabs nav-justified">
                        <li role="presentation" class="active"><a href="#tab1" aria-controls="home" role="tab" data-toggle="tab"> <span class="icon s7-smile"></span></a></li>
                        <li role="presentation"><a href="#tab2" aria-controls="profile" role="tab" data-toggle="tab"> <span class="icon s7-chat"></span></a></li>
                        <li role="presentation"><a href="#tab3" aria-controls="messages" role="tab" data-toggle="tab"> <span class="icon s7-help2"></span></a></li>
                        <li role="presentation"><a href="#tab4" aria-controls="settings" role="tab" data-toggle="tab"> <span class="icon s7-ticket"></span></a></li>
                    </ul>
                </div>

            </div>
        </nav>

    </div>
</header>
<body>
@include('layout/leftmenu')



@yield('content')

@include('pagescript/pageScript')
@include('layout/footer')
