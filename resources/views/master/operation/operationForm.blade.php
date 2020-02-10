@extends('layout.header')
@section('content')

   <style>
   .error {
           color: red;
        }
        .div-active{
        margin-top: 38px;
    }
</style>
    <div class="am-content">
        <div class="page-head">

            <h2>Operation Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Operation</li>

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
                                <h3>Add Operation</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['brand_id']))
                            {!! Form::model($input, array('url' => 'admin/editoperation', $input['id'], 'files' => true)) !!}
                            @else

                            {!! Form::open(array('url' => 'admin/addoperation', 'class' => 'form','method'=>'post')) !!}
                            @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Operation</label>

                                            {!!Form::text('operation_name',$input['operation_name'], array( 'placeholder' => 'Enter the Operation Name','class'=>'form-control','id'=>'operation_name','required'=>"required")) !!}
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
                                            <a href="{{url('admin/operationlist')}}" class="btn btn-space btn-default">Cancel</a>
                                        </div>

                                    </div>

                                </div>
                         </form>
                        </div>
                   
                </div>
            </div>
        </div>
@stop
   
