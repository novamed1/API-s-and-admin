@extends('layout.header')
@section('content')
<style>
    .div-txt{
        width: 80px;
    }
</style>
<div class="am-content">
    <div class="page-head">

        <h2>Master</h2>
        <ol class="breadcrumb">
            <li>Add Service Plan</li>

            <li class="active"></li>

        </ol>
    </div>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                @include('notification/notification')
                <form role="form"  id="myform" data-parsley-validate="" method="post" enctype="multipart/form-data">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Add Model</h3>
                        </div>

                        <div class="panel-body">
                            @if(isset($input['id']))
                            {!! Form::model($input, array('url' => 'admin/modellist', $input['id'], 'files' => true)) !!}
                            @else

                            {!! Form::open(array('url' => 'admin/model', 'class' => 'form','method'=>'post')) !!}
                            @endif

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Name</label>


                                            {!!Form::text('name','', array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Point</label>
                                            {!!Form::text('point','', array( 'placeholder' => 'Enter the point','class'=>'form-control','id'=>'capacity','required'=>"required")) !!}

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Found</label>
                                            {!!Form::text('found','', array( 'placeholder' => 'Enter the found','class'=>'form-control','id'=>'capacity','required'=>"required")) !!}

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-20">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Calibrate</label>

                                            {!!Form::text('calibrate','', array( 'placeholder' => 'Enter the calibrate','class'=>'form-control','id'=>'capacity','required'=>"required")) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CustomerId </label>
                                            {!!Form::select("customer_type",$service,'',array('class'=>'form-control','id'=>'unit','required'=>"required"))!!}  

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">SampleId </label>
                                            {!!Form::select("sample_id",$sample,'',array('class'=>'form-control','id'=>'unit','required'=>"required"))!!}  

                                        </div>
                                         <div class="am-checkbox">
                                            

                                            <input id="check1" type="checkbox" name='is_active' class="needsclick needsclick" value='1' >
                                            <label for="check1">Active</label>
                                        </div>

                                    </div>
                                      
                                </div>
                                 <div class="text-inverse">
                                            <button type="submit" class="btn btn-space btn-primary" id='submit'>Submit</button>

                                            <a href="#" class="btn btn-space btn-default">Cancel</a>
                                        </div>
                               
                            </div>
                        </div>
                 </form>
            </div>
        </div>
    </div>
</div>
    @stop
