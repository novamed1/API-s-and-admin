@extends('layout.header')
@section('content')
<style>
    
.error {
           color: red;
        }

</style>
<div class="am-content">
    <div class="page-head">

        <h2>Frequency Creation</h2>
        <ol class="breadcrumb">
            <li>Home</li>
            <li>Site Configuration</li>
            <li>Add Frequency</li>

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
                            <h3>Add Frequency</h3>
                        </div>

                        <div class="panel-body">
                            @if(isset($input['id']))
                            {!! Form::model($input, array('url' => 'admin/editfrequency', $input['id'], 'files' => true)) !!}
                            @else

                            {!! Form::open(array('url' => 'admin/addfrequency', 'class' => 'form','method'=>'post')) !!}
                            @endif

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Name</label>
                                            {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-20">
                                        <div class="form-group">
                                            <label>Number of Days</label>

                                            {!!Form::text('day',$input['day'], array( 'placeholder' => 'Enter the number of days','class'=>'form-control quantity','id'=>'day','required'=>"required")) !!}
                                        </div>
                                         <span class="campaign-divmsg" id="errmsg"></span>
                                        

                                        <div class="am-checkbox">
                                           @if($input['is_active'] == 1)
                                            @php($chk = 'checked=checked')

                                            @else
                                            @php($chk = '0')

                                            @endif 

                                            <input id="check1" type="checkbox" name='is_active' class="needsclick needsclick" value='1' {{$chk}} >
                                            <label for="check1">Active</label>
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
                                            <a href="{{url('admin/frequency')}}" class="btn btn-space btn-default">Cancel</a>
                                        </div>

                                    </div>

                                </div>
              
            </div>
                </form>
        </div>
    </div>
</div>


    @stop
