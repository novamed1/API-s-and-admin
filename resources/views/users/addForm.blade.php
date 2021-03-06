@extends('layout.header')
@section('content')

    <style>
        .error {
            color: red;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>User Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>User Management</li>
                <li>User Creation</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <form role="form" id="testPlanForm" method="post" data-parsley-validate="" novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add User</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['id']))
                                    {!! Form::model($input, array('url' => 'admin/edituser', $input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/adduser', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Name</label>

                                            {!!Form::text('first_name',$input['firstName'], array( 'placeholder' => 'Enter the name','class'=>'form-control','id'=>'first_name','required'=>"required")) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group sensitivityform" style="">

                                            <label>Email Id</label>


                                            {!!Form::text("email",$input['emailId'],array('class'=>'form-control','placeholder' => 'Enter the email','id'=>'email','required'=>'required'))!!}

                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group sensitivityform" style="">

                                            <label>Choose Role</label>

                                            {!!Form::select("role",$roles,$input['role'],array('class'=>'form-control','id'=>'email','required'=>'required'))!!}

                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group sensitivityform" style="">

                                            <label>Password</label>


                                            <input id="password" type="password" name="password" placeholder=""   class="form-control" >

                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group sensitivityform" style="">
                                            <label>*Click checkbox if you want to activate the user</label>
                                            <div class="am-checkbox">
                                                @if($input['is_active'] == 1)
                                                    @php($chk = 'checked=checked')

                                                @else
                                                    @php($chk = '0')

                                                @endif
                                                <input id="check2" type="checkbox" name="is_active"
                                                       class="needsclick" {{$chk}}>
                                                <label for="check2" class="activebottom">is active</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                            </div>

                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                    <a href="{{url('admin/userlist')}}" class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>


@stop

