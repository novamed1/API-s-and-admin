@extends('layout.header')
@section('content')

    <style>
        .error {
            color: red;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Standard Equipment Details</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master SetUp</li>
                <li>Standard Equipment Details</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    {{--<form role="form" id="testPlanForm" method="post" data-parsley-validate="" novalidate="">--}}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Standard Equipment Details</h3>
                            </div>

                            <div class="panel-body">
                                @if(($input['id']))
                                    {!! Form::model($input, array('url' => 'admin/editdevice/'.$input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/adddevice', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Standard Equipment</label>

                                                <input type="hidden" value="{!! $input['id'] !!}" name="id" id="ID">
                                                {!!Form::select("devicemodelId",$devicemodel,$input['devicemodelId'],array('class'=>'form-control','id'=>'devicemodel','required'=>"required",'placeholder'=>'Please choose device model'))!!}

                                            </div>
                                            <div class="form-group sensitivityform"  style="{{$input['devicemodelId'] =="1" ? '':'display:none'}}">

                                                <label>Sensitivity</label>


                                                {!!Form::select("sensitivity",$sensitivity,$input['sensitivity'],array('class'=>'form-control','id'=>'sensitivity','required'=>"required",'placeholder'=>'Please choose sensitivity'))!!}

                                            </div>
                                            <div class="form-group">
                                                <label>Manufacturer Serial Number</label>

                                                {!!Form::text('serial_no',$input['serial_no'], array( 'placeholder' => 'Enter the Serial number','class'=>'form-control','id'=>'description','required'=>"required")) !!}
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">


                                            <div class="form-group unitsForm" style="{{$input['devicemodelId'] =="1" ? '':'display:none'}}">

                                                <label>Units</label>


                                                {!!Form::select("unit",$unit,$input['unit'],array('class'=>'form-control','id'=>'unit'))!!}

                                            </div>
                                            <div class="form-group">

                                                <label>Calibration Frequency</label>


                                                {!!Form::select("frequencyId",$frequency,$input['frequencyId'],array('class'=>'form-control','id'=>'frequency','required'=>'required','placeholder'=>'Please choose frequency'))!!}

                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Last Date</label>

                                                {!!Form::text('last_cal_date',$input['last_cal_date'], array( 'placeholder' => 'Enter the Last date','class'=>'form-control datepicker','id'=>'lastdate','required'=>"required",'autocomplete'=>"off")) !!}
                                            </div>

                                            <div class="form-group col-md-4">

                                                <label>Next Date</label>


                                                {!!Form::text("nextduedate",$input['nextduedate'],array('placeholder' => 'Enter the next due date','class'=>'form-control datepicker','id'=>'nextdate','required'=>'required','autocomplete'=>"off"))!!}

                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-7">
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
                                    <button type="submit" class="btn btn-space btn-primary" id="devicesubmit">Submit</button>
                                    <a href="{{url('admin/devicelist')}}" class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    {{--</form>--}}
                </div>

            </div>
        </div>
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>

    <script>

        $('body').on('change', '#devicemodel', function (event) {

            var deviceModelId = $(this).val();
            console.log(deviceModelId);

            if(deviceModelId ==1){
                $('.sensitivityform').fadeIn();
                $('.unitsForm').fadeIn();
            }
            else
            {
                $('.sensitivityform').fadeOut();
                $('.unitsForm').fadeOut();
            }
        });
        //Check manufacture serial number:

        $('body').on('click', '#devicesubmit', function (event) {
            event.preventDefault();
            var description = $('#description').val();
            var id = $('#ID').val();

                $.ajax({
                    type: 'post',
                    url: "{{url("admin/checkSerialNumber")}}",
                    data: {
                        serialno:description,id:id,"_token": "{!! csrf_token() !!}"
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            if (data.result == false) {

                                $.toast({
                                    heading: 'Warning',
                                    text: data.message,
                                    position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'error',

                                    loader: false
                                });
                            }
                            else
                            {
                                $("form").submit();
                            }

                        }
                    }

                });

        });

    </script>
@stop

