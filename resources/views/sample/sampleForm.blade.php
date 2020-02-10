@extends('layout.header')
@section('content')
    <style>
        .error {
            color: red;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Samples Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Samples</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>
                    <form role="form" id="myform" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Create Samples</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['id']))
                                    {!! Form::model($input, array('url' => 'admin/editsample', $input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/addsample', 'class' => 'form','method'=>'post')) !!}
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18">
                                                <div class="form-group">

                                                    <label class="col-sm-3 control-label">Name</label>


                                                    {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Unit</label>

                                                    {!!Form::select("mode",$samples,$input['mode'],array('class'=>'form-control','id'=>'unit','required'=>"required"))!!}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Description</label>

                                                    {!!Form::text('description',$input['description'], array( 'placeholder' => 'Enter the description','class'=>'form-control','id'=>'capacity','required'=>"required")) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div>
                                            <section class="col-md-4">
                                                <div class="am-checkbox">
                                                    @if($input['temperature'] == 1)
                                                        @php($chk = 'checked=checked')

                                                    @else
                                                        @php($chk = '0')

                                                    @endif

                                                    <input id="temp1" type="checkbox" name='temperature'
                                                           class="needsclick needsclick" value='1' {{$chk}} >
                                                    <label for="temp1">Temperature
                                                    </label>
                                                </div>
                                            </section>

                                            <section class="col-md-4">
                                                <div class="am-checkbox">
                                                    @if($input['barometric_pressure'] == 1)
                                                        @php($chk = 'checked=checked')

                                                    @else
                                                        @php($chk = '0')

                                                    @endif
                                                    <input id="baro1" type="checkbox" name='barometric_pressure'
                                                           class="needsclick needsclick" value='1' {{$chk}}>
                                                    <label for="baro1">Barometric Pressure</label>
                                                </div>
                                            </section>
                                            <section class="col-md-4">
                                                <div class="am-checkbox">
                                                    @if($input['relative_humidity'] == 1)
                                                        @php($chk = 'checked=checked')

                                                    @else
                                                        @php($chk = '0')

                                                    @endif

                                                    <input id="relative" type="checkbox" name='relative_humidity'
                                                           class="needsclick needsclick" value='1' {{$chk}} >
                                                    <label for="relative">Relative Humidity</label>
                                                </div>
                                            </section>
                                            <section class="col-md-4">
                                                <div class="am-checkbox">
                                                    @if($input['accuracy'] == 1)
                                                        @php($chk = 'checked=checked')

                                                    @else
                                                        @php($chk = '0')

                                                    @endif

                                                    <input id="check1" type="checkbox" name='accuracy'
                                                           class="needsclick needsclick" value='1' {{$chk}} >
                                                    <label for="check1">Accuracy (Systematic Error 0r Trueness)</label>
                                                </div>
                                            </section>
                                            <section class="col-md-4">
                                                <div class="am-checkbox">
                                                    @if($input['precision'] == 1)
                                                        @php($chk = 'checked=checked')

                                                    @else
                                                        @php($chk = '0')

                                                    @endif

                                                    <input id="random" type="checkbox" name='precision'
                                                           class="needsclick needsclick" value='1' {{$chk}}>
                                                    <label for="random">Precision (Random Error) </label>
                                                </div>
                                            </section>


                                        </div>
                                    </div>
                                    <div class="am-checkbox" style='margin-left: 62%;'>

                                        @if($input['is_active'] == 1)
                                            @php($chk = 'checked=checked')

                                        @else
                                            @php($chk = '0')

                                        @endif

                                        <input id="active" type="checkbox" name='is_active'
                                               class="needsclick needsclick" value='1' {{$chk}} >
                                        <label for="active">Active </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                    <a href="{{url('admin/samplelist')}}" class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                </div>
                </form>
            </div>


        </div>

    </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>

    <script src="{{asset('js/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('js/formvalidation/framework.bootstrap.js')}}"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/formvalidation/formValidation.min.css')}}">
    <!--<script type="text/javascript">
        $('#myform')
                .formValidation({
                    framework: 'bootstrap',
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: 'Name is required'
                                }
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: 'Description is required'
                                }
                            }
                        },
                        mode: {
                            validators: {
                                notEmpty: {
                                    message: 'Mode is required'
                                }
                            }
                        },


                    }

                });

    </script>-->

@stop
