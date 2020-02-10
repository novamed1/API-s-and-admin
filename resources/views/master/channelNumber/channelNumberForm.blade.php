@extends('layout.header')
@section('content')

    <style>
        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Channel Number Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Channel Numbers</li>

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
                                <h3>Add Channel Number</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['id']))
                                    {!! Form::model($input, array('url' => 'admin/editchannelnumbers', $input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/addchannelnumbers', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                                <label class="col-sm-4">Choose Channel</label>

                                                {!!Form::select('channel_id',$ChooseChannel,$input['channel_id'], array('class'=>'form-control','id'=>'channel_id','required'=>"required")) !!}
                                            </div>


                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Channel Number</label>

                                                {!!Form::text('channel_number',$input['channel_number'], array( 'placeholder' => 'Enter the Number','class'=>'form-control','id'=>'name','required'=>"required")) !!}
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
                                    <a href="{{url('admin/productlist')}}" class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="{{asset('js/datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script>
        // var date = new Date();

        $('.datepicker')
            .datepicker({

                todayHighlight: true,
                orientation: "bottom",
                format: 'd-m-yyyy',
                //startDate: date
            })
            .on('changeDate', function (e) {
                $(this).datepicker('hide');
            });
    </script>
@stop
   
