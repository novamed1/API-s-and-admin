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

        <h2>Master</h2>
        <ol class="breadcrumb">
            <li>Home</li>
            <li>Master</li>
            <li>Equipment parts</li>

            <li class="active"></li>

        </ol>
    </div>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">

                <div class="error">
                    @include('notification/notification')
                </div>

                <form role="form"  id="myform"  method="post" enctype="multipart/form-data" data-parsley-validate="" novalidate="">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Add Equipment parts</h3>
                        </div>

                        <div class="panel-body">
                            @if(isset($input['id']))
                            {!! Form::model($input, array('url' => 'admin/editparts', $input['id'], 'files' => true)) !!}
                            @else

                            {!! Form::open(array('url' => 'admin/addparts', 'class' => 'form','method'=>'post')) !!}
                            @endif

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Name</label>
                                           {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}
                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Description</label>
                                            {!!Form::text('description',$input['description'], array( 'placeholder' => 'Enter the Description','id'=>'description','class'=>'form-control','required'=>"required")) !!}

                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Make</label>
                                            {!!Form::text('make',$input['make'], array( 'placeholder' => 'Enter the Make','id'=>'make','class'=>'form-control','required'=>"required")) !!}

                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Quantity</label>
                                            {!!Form::text('quantity',$input['quantity'], array('data-parsley-type'=>"number", 'placeholder' => 'Enter the Quantity','id'=>'quantity','class'=>'form-control','required'=>"required")) !!}
                                         
                                        </div>
                                       

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-20">
                                        <div class="form-group">
                                            <label>Equipment Id</label>

                                            {!!Form::select("modelId",$modelname,$input['modelId'],array('id'=>'modelId','class'=>'form-control','required'=>'required'))!!}
                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Item Number</label>
                                            {!!Form::text('item_number',$input['item_number'], array('class'=>'form-control','id'=>'item_number','required'=>"required")) !!}

                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Price</label>
                                            {!!Form::text('price',$input['price'], array('data-parsley-type'=>"number", 'placeholder' => 'Enter the Price','id'=>'price','class'=>'form-control price','id'=>'price','required'=>"required")) !!}
                                        
                                        </div>
                                      


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
                                <button type="submit" class="btn btn-space btn-primary" id='submit'>Submit</button>
                                <a href="{{url('admin/partslist')}}" class="btn btn-space btn-default">Cancel</a>
                            </div>

                        </div>

                    </div>
 </form>
            </div>
           
        </div>
    </div>
</div>



@stop

