@extends('layout.header')
@section('content')
<style>
    .error {
           color: red;
        }
</style>
<div class="am-content">
    <div class="page-head">

        <h2> Standard Equipment Creation</h2>
        <ol class="breadcrumb">
            <li>Home</li>
             <li>Site Configuration</li>
            <li>Add Standard Equipment</li>
           

            <li class="active"></li>

        </ol>
    </div>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="error">
                     @include('notification/notification')
                </div>
                <form role="form"  id="myform"  method="post" enctype="multipart/form-data">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Create Standard Equipment</h3>
                        </div>

                        <div class="panel-body">
                            @if(isset($input['id']))
                            {!! Form::model($input, array('url' => 'admin/editcustomertype', $input['id'], 'files' => true)) !!}
                            @else

                            {!! Form::open(array('url' => 'admin/addcustomertype', 'class' => 'form','method'=>'post')) !!}
                            @endif

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label class="">Name</label>
                                            {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label class="">Description</label>
                                            {!!Form::textarea('description',$input['description'], array( 'placeholder' => '','class'=>'form-control','id'=>'description','required'=>"required")) !!}

                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
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
                            </div>
                            </div>
                        </div>
                            
                        
                        </div>
              
            
            <div class="panel panel-default" style='text-align: center;'>

                                    <div class="panel-body">

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                            <a href="{{url('admin/devicemodellist')}}" class="btn btn-space btn-default">Cancel</a>
                                        </div>

                                    </div>

                                </div>
                </form>
        </div>
    </div>
</div>
    @stop
