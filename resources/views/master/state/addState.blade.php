@extends('layout.header')
@section('content')
    <style>

        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }

          .alert-danger
        {
            color:red;
        }
        

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>State Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master Setup</li>
                <li>Add State</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">

                    <div class="error">
                        @include('notification/notification')
                    </div>


                     @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                   <!--  <form role="form" id="myform" method="post"  data-parsley-validate=""
                          novalidate=""> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add State</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['id']) && $input['id']!='')
                                {!! Form::model($input, array('url' => 'admin/updateState'."/".$input['id'],'method' => 'PUT')) !!}                               
                                 @else

                                    {!! Form::open(array('url' => 'admin/saveState', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Name</label>
                                                {!!Form::text('state_name',$input['state_name'], array( 'placeholder' => 'Enter the State name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                            </div>

                                           
                                           
                                        </div>
                                        

                                    </div>
                                </div>


                            </div>
                            <div class="panel panel-default" style='text-align: center;'>

                                <div class="panel-body">

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                        <a href="{{url('admin/statelist')}}" class="btn btn-space btn-default">Cancel</a>
                                    </div>

                                </div>

                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>

  

@stop

