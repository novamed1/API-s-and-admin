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

            <h2>Pipette Type Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Pipette type</li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <form role="form" id="pipetteTypeForm" method="post" data-parsley-validate="" novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Pipette Type</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['brand_id']))
                                    {!! Form::model($input, array('url' => 'admin/editpipettetype', $input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/addpipettetype', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Pipette Name</label>

                                                {!!Form::text('pipette_name',$input['pipette_name'], array( 'placeholder' => 'Enter the Pipette Name','class'=>'form-control','id'=>'pipette_name','required'=>"required")) !!}
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Alias name</label>

                                                {!!Form::text('alias_name',$input['alias_name'], array( 'placeholder' => 'Enter the Alias Name','class'=>'form-control','id'=>'alias_name')) !!}
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group">
                                        {{--<div class="col-sm-offset-7">--}}
                                            <div class="am-checkbox">
                                                @if($input['is_active'] == 1)
                                                    @php($chk = 'checked=checked')

                                                @else
                                                    @php($chk = '0')

                                                @endif

                                                <input id="check2" type="checkbox" name="is_active"
                                                       class="needsclick" {{$chk}}>
                                                <label for="check2" class="activebottom div-active">is active</label>
                                            </div>

                                        </div>
                                    {{--</div>--}}


                                </div>


                            </div>

                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                    <a href="{{url('admin/pipettetypelist')}}"
                                       class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@stop
   
