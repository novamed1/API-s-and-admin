<!DOCTYPE html>
<html lang="en"></html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="{{asset('lib/stroke-7/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('lib/jquery.nanoscroller/css/nanoscroller.css')}}"/><!--[if lt IE 9]>

    <![endif]-->
    <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css"/>
</head>
<body class="am-splash-screen">
<div class="am-wrapper am-login">
    <div class="am-content">
        <div class="main-content">
            <div class="login-container">
                <div class="panel panel-default">
                    <div class="panel-heading" style="color:#fff; font-size: 23px;">Admin Login</div>
                    <div class="panel-body">
                        <div class="alert-danger">
                        @include('notification.notification')
                    </div>
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                        <form role="form" action="#"  data-parsley-validate="" method="post" class="form-horizontal">
                            {!! csrf_field() !!}
                            <div class="login-form">
                                <div class="form-group">
                                    <div class="input-group"><span class="input-group-addon"><i class="icon s7-user"></i></span>
                                        <input id="email" name="email" type="text" placeholder="Username"   autocomplete="off" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"><span class="input-group-addon"><i class="icon s7-lock"></i></span>
                                        <input id="password" type="password" name="password" placeholder="Password"   class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group login-submit">
                                    <button data-dismiss="modal" type="submit" class="btn btn-primary btn-lg">Login</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('lib/jquery/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('lib/jquery.nanoscroller/javascripts/jquery.nanoscroller.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/main.js')}}" type="text/javascript"></script>
<script src="{{asset('lib/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>

</script>
<script type="text/javascript">
    $(document).ready(function(){
        //initialize the javascript
        App.init();
    });
</script>



</body>