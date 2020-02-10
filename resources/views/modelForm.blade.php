@extends('layout.header')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-slider.css')}}">
    <style>

        .location-list {
            border-top: 1px solid #FFFFFF !important;
        }

        .table > thead > tr > th {
            font-weight: bold;
        }

        .div-sec td {
            width: 18%;

        }

        .td-cross {
            font-size: 27px;
        }

        .img-div {
            padding: 8px;
            background-color: #ccc;
            width: 100%;
            margin-top: 14px;
        }

        .cls-qwe {
            margin-top: 12px;
        }

        .div-cl-row {
            margin-top: 10px;
        }

        .wizardlink {
            width: 25%;
        }

        .cancel {
            padding: 9px;
            width: 8%;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Equipment Model </h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Equipment Management</a></li>
                <li class="active">Equipment Model Creation</li>
            </ol>
        </div>
        <div class="main-content">
            <div class="row wizard-row">
                <div class="col-md-12 fuelux">
                    <div class="block-wizard panel panel-default">
                        <div id="wizard1" class="wizard wizard-ux">
                            <ul class="steps">
                                <li data-step="1" class="active wizardlink">Instrument<span class="chevron"></span></li>
                                <li data-step="2" class="wizardlink">Specifications<span class="chevron"></span></li>
                                <li data-step="3" class="wizardlink">Parts<span class="chevron"></span></li>
                                <li data-step="4" class="wizardlink">Documentation<span class="chevron"></span></li>
                                <!--                            <li data-step="4">Checklist<span class="chevron"></span></li>-->
                            </ul>
                            {{--<div class="actions">--}}
                            {{--<button type="button" class="btn btn-xs btn-prev btn-default"><i class="icon s7-angle-left"></i>Prev</button>--}}
                            {{--<button type="button" data-last="Finish" class="btn btn-xs btn-next btn-default">Next<i class="icon s7-angle-right"></i></button>--}}
                            {{--</div>--}}

                            {{--<form action="#" id="modelform"--}}
                            {{--class="form-horizontal group-border-dashed" method="post"--}}
                            {{-->--}}

                            {{--@if(isset($input['id']))--}}
                            {{--{!! Form::model($input, array('url' => 'admin/editmodel', $input['id'], 'files' => true)) !!}--}}

                            {{--@else--}}

                            {{--{!! Form::open(['url'=>'admin/model', 'files' => true,'id'=>'checkout-form']) !!}--}}
                            {{--@endif--}}

                            <div class="step-content">

                                <div data-step="1" class="step-pane active">
                                    <form action="#" id="InstrumentForm"
                                          class="form-horizontal group-border-dashed" method="post"
                                          data-parsley-validate>
                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="Id" id="Id" value={{$input['id']}}>
                                        {{--<div class="form-group no-padding">--}}
                                        {{--<div class="col-sm-7">--}}
                                        {{--<h3 class="wizard-title">Instrument Details</h3>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        <legend>Instrument Details</legend>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18">
                                                <div class="form-group">

                                                    <label>Product Type</label>
                                                    {!!Form::select("producttype",$product,$input['producttype'],array('class'=>'form-control','id'=>'producttype','required'=>""))!!}
                                                </div>


                                                <div class="form-group">

                                                    <label>Brand</label>
                                                    {!!Form::select("Brand",$brand,$input['Brand'],array('class'=>'form-control','id'=>'brand','required'=>""))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Channels</label>

                                                    {!!Form::select("channels",$channels,$input['channels'],array('class'=>'form-control','id'=>'channels','required'=>""))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Volume</label>

                                                    {!!Form::select("volume",$volumeSelect,$input['volume'],array('class'=>'form-control','id'=>'volume','required'=>""))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Channels-Number</label>

                                                    {!!Form::select("channelNo",$channelNumberSelect,$input['channelNo'],array('class'=>'form-control','required'=>""))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Model Description </label>

                                                    {!!Form::textarea("modeldescription",$input['modeldescription'],array('class'=>'form-control  ','id'=>'modeldescription','cols'=>"10", 'rows'=>"3",'required'=>""))!!}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18 cls-qwe">
                                                <div class="form-group" style="line-height: 7px;">
                                                    <label>Manufacturer </label>
                                                    <div class="styled-select-lab gender">
                                                        {!!Form::select("manufacturer[]",$manufacturer,$input['manufacturer'],array('class'=>'form-control select2','multiple'=>true,'id'=>'select2','style'=>"width:100%"))!!}
                                                    </div>
                                                </div>

                                                {{--<div class="campaign-type-frm">--}}
                                                {{--<div class="row">--}}
                                                {{--<section class="col-md-12">--}}

                                                {{--<label>--}}

                                                {{--<h5>Gender</h5>--}}

                                                {{--<div class="styled-select-lab gender">--}}

                                                {{--{!!Form::select("genderselect[]", $gender,$input['genderselect'],array('class'=>'form-control select2','multiple'=>true,'id'=>'select2','style'=>"width:100%"))!!}--}}


                                                {{--</div>--}}

                                                {{--</label>--}}
                                                {{--</section>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}


                                                <div class="form-group">
                                                    <label>Model Name</label>
                                                    {!!Form::text("model_name",$input['model_name'],array('placeholder' => 'Enter the Name','class'=>'form-control','id'=>'modelName','readonly'=>'true'))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Operation</label>

                                                    {!!Form::select("operation",$operationSelect,$input['operation'],array('class'=>'form-control','id'=>'operation','required'=>""))!!}
                                                </div>
                                                <div class="row div-cl-row">
                                                    <section class="col-md-4" id='valueform'>

                                                        <label>Volume From</label>
                                                        {!!Form::text('volume_from',$input['volume_from'], array('data-parsley-type'=>"number",'class'=>'form-control','required'=>"")) !!}

                                                    </section>
                                                    <section class="col-md-4" id='valueto'>

                                                        <label>Volume To</label>
                                                        {!!Form::text('volume_to',$input['volume_to'], array('data-parsley-type'=>"number",'class'=>'form-control fromvalue')) !!}

                                                    </section>

                                                    <section class="col-md-4" id='valuerange'>

                                                        <label>Volume Range</label>

                                                        {!!Form::select("unit",$modelunits,$input['unit'],array('class'=>'form-control','id'=>'unit','required'=>""))!!}

                                                    </section>
                                                </div>
                                                @if($input['image'])
                                                    <div class="form-group div-cl-row" id="imageshow">
                                                        <label for="modelImage" class="input"></label>
                                                        <div class="col-sm-2 txt-img">
                                                            <a class="thumbnail" href="javascript:void(0);">
                                                                <button type="button" class="close"
                                                                        data-id="{{$input['id']}}" id="image">×
                                                                </button>
                                                                <img class="form-control"
                                                                     src="{{asset('equipment_model/images/large/'.$input['image'])}}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($input['image'])
                                                    <div class="imageupload" id="imageupload" style="display:none">
                                                        <label class="input imageDesign">Image</label>
                                                        {{--<div class="col-sm-6">--}}
                                                        {{--{!!Form::file('image',"", array( 'class'=>'form-control textTransform modelimagefile')) !!}--}}
                                                        {{--</div>--}}
                                                    </div>
                                                @else
                                                    <div class="form-group div-cl-row" id="imageupload">
                                                        <label class="input" style="display: block;">Upload image
                                                            <input type="file" name="file" value=""
                                                                   class="upload file modelimagefile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        {{--<div class="col-sm-6">--}}
                                                        {{--{!!Form::file('image',"", array( 'class'=>'form-control textTransform modelimagefile')) !!}--}}
                                                        {{--</div>--}}
                                                    </div>
                                                @endif


                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">

                                                <button data-wizard="#wizard1"
                                                        class="btn btn-primary pull-right btn-space wizard-next next-step"
                                                        data-attr="#InstrumentForm">
                                                    Next Step <i class="icon s7-angle-right"></i></button>
                                                <a href="{{url('admin/modellist')}}"
                                                   class="btn btn-default pull-right btn-space cancel">Cancel
                                                </a>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div data-step="2" class="step-pane">


                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="ToleranceForm">
                                        {{--<div class="form-group no-padding">--}}
                                        {{--<div class="col-sm-7">--}}
                                        {{--<h3 class="wizard-title">Manufacturer Specifications </h3>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}


                                        {{--<div class="panel panel-default">--}}
                                        {{--<div class="panel-heading">--}}
                                        {{--<h3>Limit Tolerences</h3>--}}
                                        {{--</div>--}}

                                        <div class="cart-type">
                                            <legend>Limit Tolerences</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-2">

                                                        <label>
                                                            <h5 class="heading">Description</h5></label>

                                                        {!!Form::text('description','', array('class'=>'form-control', 'id'=>'description')) !!}

                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-2">

                                                        <label>

                                                            <h5 class="heading">Target</h5></label>

                                                        {!!Form::text('target_value','', array('class'=>'form-control numeric','id'=>'traget')) !!}

                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Accuracy</h5></label>

                                                        {!!Form::text('accuracy','', array('class'=>'form-control numeric','id'=>'accuracy')) !!}

                                                    </section>

                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Precision</h5></label>

                                                        {!!Form::text('precision','', array('class'=>'form-control numeric','id'=>'precision')) !!}

                                                    </section>

                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Accuracy(ul)</h5></label>

                                                        {!!Form::text('accuracyul','', array('class'=>'form-control numeric','id'=>'accuracyul')) !!}

                                                    </section>

                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Precision(ul)</h5></label>

                                                        {!!Form::text('precisionul','', array('class'=>'form-control numeric ','id'=>'precisionul')) !!}
                                                    </section>

                                                    @if($input['id'])
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addtolerance"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    @else
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addtolerance"><i class=''
                                                                                    aria-hidden="true">+</i></a>


                                                        </section>
                                                    @endif
                                                </div>
                                                <div class="panel panel-default">

                                                    <div class="panel-body">
                                                        <div class="widget-body">
                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    <div class="widget location-list">
                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            @if($input['id'])
                                                                                <thead>
                                                                                <tr id='toleranceBody'>
                                                                                    <th class="th-style">Description
                                                                                    </th>
                                                                                    <th class="th-style">Target</th>
                                                                                    <th class="th-style">Accuracy %</th>
                                                                                    <th class="th-style">Precision %
                                                                                    </th>
                                                                                    <th class="th-style">Accuracy(ul)
                                                                                    </th>
                                                                                    <th class="th-style">Precision(ul)
                                                                                    </th>
                                                                                    <th class="th-style"></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='toleranceBody'
                                                                                    style='display:none;'>
                                                                                    <th class="th-style">Description
                                                                                    </th>
                                                                                    <th class="th-style">Target</th>
                                                                                    <th class="th-style">Accuracy %</th>
                                                                                    <th class="th-style">Precision %
                                                                                    </th>
                                                                                    <th class="th-style">Accuracy(ul)
                                                                                    </th>
                                                                                    <th class="th-style">Precision(ul)
                                                                                    </th>
                                                                                    <th class="th-style"></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="dataAppend">
                                                                            @if($limit)
                                                                                @foreach($limit as $key=> $row)

                                                                                    <tr class="div-lits"
                                                                                        id="{{$key}}">
                                                                                        <td> {{$row['description']}}</td>
                                                                                        <td> {{$row['target_value']}}</td>
                                                                                        <td> {{$row['accuracy']}}</td>
                                                                                        <td> {{$row['precision']}}</td>
                                                                                        <td> {{$row['accuracy_ul']}}</td>
                                                                                        <td> {{$row['precesion_ul']}}</td>
                                                                                        <td><a href="javascript:void(0)"

                                                                                               onClick="remove_form({{$key}})"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                    </tr>


                                                                                    {!!Form::hidden("toleranceArray[".$key."][description]",$row['description'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}
                                                                                    {!!Form::hidden("toleranceArray[".$key."][target_value]",$row['target_value'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}
                                                                                    {!!Form::hidden("toleranceArray[".$key."][accuracy]",$row['accuracy'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}
                                                                                    {!!Form::hidden("toleranceArray[".$key."][precision]",$row['precision'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}
                                                                                    {!!Form::hidden("toleranceArray[".$key."][accuracy_ul]",$row['accuracy_ul'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}
                                                                                    {!!Form::hidden("toleranceArray[".$key."][precision_ul]",$row['precesion_ul'],array('class'=>'form-control','id'=>'foods'.'-'.$key)) !!}

                                                                                @endforeach
                                                                            @endif
                                                                            </tbody>

                                                                        </table>

                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        data-attr="#ToleranceForm"
                                                        class="btn btn-primary pull-right btn-space wizard-next">
                                                    Next Step <i class="icon s7-angle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div data-step="3" class="step-pane">
                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="PartsForm">

                                        <div class="cart-type">
                                            <legend>Spares</legend>

                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-3">

                                                        <label>

                                                            <h5 class="heading">SKU Number</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('number','', array( 'placeholder' => 'Enter the number','class'=>'form-control','id'=>'number')) !!}

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>


                                                    <section class="col-md-3">
                                                        <label>

                                                            <h5 class="heading">Spare Mode</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::select('sparemode',$spareSelect,'', array( 'placeholder' => 'Enter the spare mode','class'=>'form-control','id'=>'sparemode')) !!}

                                                        </div>


                                                    </section>
                                                    <section class="col-md-3">
                                                        <label>

                                                            <h5 class="heading">Part Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('partname','', array( 'placeholder' => 'Enter the Part Name','class'=>'form-control','id'=>'partname')) !!}

                                                        </div>


                                                    </section>

                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Price</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('Price','', array( 'placeholder' => 'Enter the Price','class'=>'form-control','id'=>'Price')) !!}

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>

                                                    <section class="col-md-1" style="    top: 31px;;float:right;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addspares"><i class=''
                                                                             aria-hidden="true">+</i></a>


                                                    </section>


                                                </div>


                                                {{--<div class="panel panel-default" id='sparesBody' style='display:none; '>--}}

                                                {{--<div class="panel-body">--}}

                                                {{--<div class="widget-body">--}}
                                                {{--<div class="row">--}}
                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="widget">--}}
                                                {{--<div>--}}
                                                {{--<table class="table table-fw-widget">--}}

                                                {{--<thead>--}}
                                                {{--<tr>--}}
                                                {{--<th class="">Spare Mode</th>--}}
                                                {{--<th>price</th>--}}
                                                {{--<th>SKU Number</th>--}}
                                                {{--<th></th>--}}

                                                {{--</tr>--}}
                                                {{--</thead>--}}
                                                {{--<tbody id="sparesAppend">--}}

                                                {{--</tbody>--}}
                                                {{--</table>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                {{--</div>--}}
                                                {{--</div>--}}


                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}


                                                <div class="panel panel-default">


                                                    <div class="panel-body">

                                                        <div class="widget-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="widget">
                                                                        <div>


                                                                            <table id='tbl-box'
                                                                                   class="table table-fw-widget">
                                                                                @if($input['id'])
                                                                                    <thead>
                                                                                    <tr id='sparesBody'>
                                                                                        <th>SKU Number</th>
                                                                                        <th class="">Spare Mode</th>
                                                                                        <th>Part Name</th>
                                                                                        <th>Price</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                @else
                                                                                    <thead>
                                                                                    <tr id='sparesBody'
                                                                                        style='display:none;'>
                                                                                        <th>SKU Number</th>
                                                                                        <th class="">Spare Mode</th>
                                                                                        <th>Part Name</th>
                                                                                        <th>Price</th>

                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                @endif
                                                                                <tbody id="sparesAppend">
                                                                                @if($spares)
                                                                                    @foreach($spares as $sparekey=> $sparerow)

                                                                                        <tr class="div-lits"
                                                                                            id="{{$sparekey}}">
                                                                                            <td> {{$sparerow['number']}}</td>
                                                                                            <td> {{$sparerow['sparemodeValue']}}</td>
                                                                                            <td> {{$sparerow['partname']}}</td>
                                                                                            <td> {{$sparerow['Price']}}</td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"

                                                                                                   onClick="remove_form({{$sparekey}})"
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                        </tr>

                                                                                        {!!Form::hidden("sparedetail[".$sparekey."][number]",$sparerow['number'],array('class'=>'form-control','id'=>'spare'.'-'.$sparekey)) !!}
                                                                                        {!!Form::hidden("sparedetail[".$sparekey."][sparemode]",$sparerow['sparemode'],array('class'=>'form-control','id'=>'spare'.'-'.$sparekey)) !!}
                                                                                        {!!Form::hidden("sparedetail[".$sparekey."][partname]",$sparerow['partname'],array('class'=>'form-control','id'=>'spare'.'-'.$sparekey)) !!}
                                                                                        {!!Form::hidden("sparedetail[".$sparekey."][Price]",$sparerow['Price'],array('class'=>'form-control','id'=>'spare'.'-'.$sparekey)) !!}

                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="cart-type">
                                            <legend>Accessories</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">

                                                    <section class="col-md-4">

                                                        <label>

                                                            <h5 class="heading">SKU Number</h5></label>

                                                        {!!Form::text('accessory_sku_number','', array('class'=>'form-control numeric','id'=>'accessory_sku_number','placeholder'=>'Enter SKU Name')) !!}

                                                    </section>


                                                    <section class="col-md-4">

                                                        <label>
                                                            <h5 class="heading">Accesory Name</h5></label>

                                                        {!!Form::text('accessory_name','', array('class'=>'form-control', 'id'=>'accessory_name','placeholder'=>'Enter Accessory Name')) !!}

                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-3">
                                                        <label>

                                                            <h5 class="heading">Accessory Price</h5></label>

                                                        {!!Form::text('accessory_price','', array('class'=>'form-control numeric','id'=>'accessory_price','placeholder'=>'Enter Accessory Price')) !!}

                                                    </section>


                                                    <section class="col-md-1" style="top: 32px;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addaccessory"><i class=''
                                                                                aria-hidden="true">+</i></a>


                                                    </section>

                                                </div>
                                                {{--<div class="panel panel-default" id='accessoryBody'--}}
                                                {{--style='display:none; '>--}}

                                                {{--<div class="panel-body">--}}

                                                {{--<div class="widget-body">--}}
                                                {{--<div class="row">--}}
                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="widget">--}}
                                                {{--<div>--}}
                                                {{--<table class="table table-fw-widget">--}}

                                                {{--<thead>--}}
                                                {{--<tr>--}}
                                                {{--<th class="">Accessory Name</th>--}}
                                                {{--<th>Accessory SKU Number</th>--}}
                                                {{--<th>Accessory Price</th>--}}
                                                {{--<th></th>--}}

                                                {{--</tr>--}}
                                                {{--</thead>--}}
                                                {{--<tbody id="accessoryAppend">--}}

                                                {{--</tbody>--}}
                                                {{--</table>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                {{--</div>--}}
                                                {{--</div>--}}


                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{----}}

                                                <div class="panel panel-default">

                                                    <div class="panel-body">

                                                        <div class="widget-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="widget">
                                                                        <div>


                                                                            <table id='tbl-box'
                                                                                   class="table table-fw-widget">
                                                                                @if($input['id'])
                                                                                    <thead>
                                                                                    <tr id='accessoryBody'
                                                                                        class="access-list">
                                                                                        <th>Accessory SKU Number
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>

                                                                                        <th>Accessory Price</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                @else
                                                                                    <thead>
                                                                                    <tr id='accessoryBody'
                                                                                        style='display:none;'>
                                                                                        <th>Accessory SKU Number
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>

                                                                                        <th>Accessory Price</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                @endif
                                                                                <tbody id="accessoryAppend">
                                                                                @if($accessory)
                                                                                    @foreach($accessory as $accessorykey=> $accessoryrow)

                                                                                        <tr class="div-lits"
                                                                                            id="{{$accessorykey}}">
                                                                                            <td> {{$accessoryrow['AccessorySKUnumber']}}</td>
                                                                                            <td> {{$accessoryrow['AccessoryName']}}</td>
                                                                                            <td> {{$accessoryrow['AccessoryPrice']}}</td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"

                                                                                                   onClick="remove_form({{$accessorykey}})"
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                        </tr>

                                                                                        {!!Form::hidden("accessorydetail[".$accessorykey."][AccessorySKUnumber]",$accessoryrow['AccessorySKUnumber'],array('class'=>'form-control','id'=>'accessory'.'-'.$accessorykey)) !!}
                                                                                        {!!Form::hidden("accessorydetail[".$accessorykey."][AccessoryName]",$accessoryrow['AccessoryName'],array('class'=>'form-control','id'=>'accessory'.'-'.$accessorykey)) !!}
                                                                                        {!!Form::hidden("accessorydetail[".$accessorykey."][AccessoryPrice]",$accessoryrow['AccessoryPrice'],array('class'=>'form-control','id'=>'accessory'.'-'.$accessorykey)) !!}

                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="cart-type">
                                            <legend>Parts Tips</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-4">

                                                        <label>

                                                            <h5 class="heading">SKU Number</h5></label>

                                                        {!!Form::text('tip_sku_number','', array('class'=>'form-control numeric','id'=>'tip_sku_number','placeholder'=>'Enter Tip SKU Name')) !!}

                                                    </section>

                                                    <section class="col-md-4">

                                                        <label>
                                                            <h5 class="heading">Tip Name</h5></label>

                                                        {!!Form::text('tip_name','', array('class'=>'form-control', 'id'=>'tip_name','placeholder'=>'Enter Tip Name')) !!}

                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-3">
                                                        <label>

                                                            <h5 class="heading">Tip Price</h5></label>

                                                        {!!Form::text('tip_price','', array('class'=>'form-control numeric','id'=>'tip_price','placeholder'=>'Enter Tip Price')) !!}

                                                    </section>


                                                    <section class="col-md-1" style="top: 32px;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addtips"><i class=''
                                                                           aria-hidden="true">+</i></a>


                                                    </section>

                                                </div>
                                            </div>
                                            {{--<div class="panel panel-default" id='tipBody'--}}
                                            {{--style='display:none; '>--}}

                                            {{--<div class="panel-body">--}}

                                            {{--<div class="widget-body">--}}
                                            {{--<div class="row">--}}
                                            {{--<div class="col-md-12">--}}
                                            {{--<div class="widget">--}}
                                            {{--<div>--}}
                                            {{--<table class="table table-fw-widget">--}}

                                            {{--<thead>--}}
                                            {{--<tr>--}}
                                            {{--<th class="">Tip Name</th>--}}
                                            {{--<th>Tip SKU Number</th>--}}
                                            {{--<th>Tip Price</th>--}}
                                            {{--<th></th>--}}

                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody id="tipAppend">--}}

                                            {{--</tbody>--}}
                                            {{--</table>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}

                                            {{--</div>--}}
                                            {{--</div>--}}


                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}


                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            @if($input['id'])
                                                                                <thead>
                                                                                <tr id='tipBody' class="tip-list">
                                                                                    <th>Tip SKU Number</th>
                                                                                    <th class="">Tip Name</th>
                                                                                    <th>Tip Price</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='tipBody' class="tip-list"
                                                                                    style='display:none;'>
                                                                                    <th>Tip SKU Number</th>
                                                                                    <th class="">Tip Name</th>
                                                                                    <th>Tip Price</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="tipAppend">
                                                                            @if($tips)
                                                                                @foreach($tips as $tipskey=>$tipsrow)

                                                                                    <tr class="div-lits"
                                                                                        id="{{$tipskey}}">
                                                                                        <td> {{$tipsrow['tipNumber']}}</td>
                                                                                        <td> {{$tipsrow['tipname']}}</td>
                                                                                        <td> {{$tipsrow['tipPrice']}}</td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               onClick="remove_form({{$tipskey}})"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                    </tr>

                                                                                    {!!Form::hidden("tipdetail[".$tipskey."][tipNumber]",$tipsrow['tipNumber'],array('class'=>'form-control','id'=>'tip'.'-'.$tipskey)) !!}

                                                                                    {!!Form::hidden("tipdetail[".$tipskey."][tipname]",$tipsrow['tipname'],array('class'=>'form-control','id'=>'tip'.'-'.$tipskey)) !!}
                                                                                    {!!Form::hidden("tipdetail[".$tipskey."][tipPrice]",$tipsrow['tipPrice'],array('class'=>'form-control','id'=>'tip'.'-'.$tipskey)) !!}

                                                                                @endforeach
                                                                            @endif
                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        data-attr="#PartsForm"
                                                        class="btn btn-primary pull-right btn-space wizard-next">
                                                    Next Step <i class="icon s7-angle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div data-step="4" class="step-pane">
                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="DocumentationForm">


                                        <div class="cart-type">
                                            <legend>Operating Manuals</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Operating Manual Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('operating_manual_name',$input['operating_manual_name'], array( 'placeholder' => 'Enter Operating Manual Name','class'=>'form-control','id'=>'operating_manual_name')) !!}

                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Operating Manual Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {{--                                                            {!!Form::file('oper_manual_doc','', array('class'=>'form-control','id'=>'oper_manual_doc','style'=>'')) !!}--}}
                                                            <input type="file" name="oper_manual_doc" value=""
                                                                   class="upload file manualfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    @if($input['id'])
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addmanualdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    @else
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addmanualdoc"><i class=''
                                                                                    aria-hidden="true">+</i></a>


                                                        </section>
                                                    @endif


                                                </div>

                                            </div>

                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            @if($input['id'])
                                                                                <thead>
                                                                                <tr id='manualdocBody'
                                                                                    class="manualdoc-list">
                                                                                    <th>Operating Manual Name</th>
                                                                                    <th class="">Operation Manual
                                                                                        Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='manualdocBody'
                                                                                    class="manualdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Operating Manual Name</th>
                                                                                    <th class="">Operation Manual
                                                                                        Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="manualdocAppend">

                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="cart-type">
                                            <legend>Specifications</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Specification Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('specification_name',$input['specification_name'], array( 'placeholder' => 'Enter Specification Name','class'=>'form-control','id'=>'specification_name')) !!}

                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Specification Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {{--{!!Form::file('specification_doc','', array('class'=>'form-control','id'=>'specification_doc')) !!}--}}
                                                            <input type="file" name="specification_doc" value=""
                                                                   class="upload file specificationfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    @if($input['id'])
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addspecdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    @else
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addspecdoc"><i class=''
                                                                                  aria-hidden="true">+</i></a>


                                                        </section>
                                                    @endif


                                                </div>

                                            </div>
                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            @if($input['id'])
                                                                                <thead>
                                                                                <tr id='specdocBody'
                                                                                    class="specdoc-list">
                                                                                    <th>Specifictaion Document Name</th>
                                                                                    <th class="">Specifictaion Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='specdocBody'
                                                                                    class="specdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Specifictaion Document Name</th>
                                                                                    <th class="">Specifictaion Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="specdocAppend">

                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="cart-type">
                                            <legend>Brouchers</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Broucher Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('broucher_name',$input['broucher_name'], array( 'placeholder' => 'Enter Broucher Name','class'=>'form-control','id'=>'broucher_name')) !!}

                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Broucher Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {{--{!!Form::file('specification_doc','', array('class'=>'form-control','id'=>'specification_doc')) !!}--}}
                                                            <input type="file" name="broucher_doc" value=""
                                                                   class="upload file broucherfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    @if($input['id'])
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addbroucherdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    @else
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addbroucherdoc"><i class=''
                                                                                      aria-hidden="true">+</i></a>


                                                        </section>
                                                    @endif


                                                </div>

                                            </div>
                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            @if($input['id'])
                                                                                <thead>
                                                                                <tr id='broucherdocBody'
                                                                                    class="broucherdoc-list">
                                                                                    <th>Broucher Document Name</th>
                                                                                    <th class="">Broucher Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='broucherdocBody'
                                                                                    class="broucherdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Broucher Document Name</th>
                                                                                    <th class="">Broucher Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="broucherdocAppend">

                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-success pull-right btn-space wizard-next"
                                                        data-attr="#DocumentationForm"
                                                        id="submitForm"><i
                                                            class="icon s7-check"></i> Complete
                                                </button>
                                            </div>
                                        </div>


                                    </form>

                                </div>


                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>

    <div style="display:none;">
        <form action="{{url("admin/modellist")}}" method="post" id="formSubmission">
            <input type="text" value="1" name="postvalue">
            <input type="text" value="{!! $input['id'] !!}" name="customerSetUpId">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="submit" id="modelSubmit">
        </form>
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>
    {{--<script src="{{asset('js/select2.min.js')}}"></script>--}}
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/wizard.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>

    {{--<script src="{{asset('js/autocomplete/jquery.autocomplete.js')}}"></script--}}
    <script src="{{asset('js/app-form-wizard.js')}}"></script>

    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/autocomplete/autocomplete.css')}}">--}}


    <script type="text/javascript">
        $("#select2").select2();
    </script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });

    </script>


    <script>

        $('body').on('click', '#submitForm', function () {
            var data = $('#InstrumentForm, #ToleranceForm, #PartsForm, #DocumentationForm').serialize();
            console.log(data);
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url("admin/addModel")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        $('#modelSubmit').trigger('click');
                        //window.location = "{{url("admin/modellist")}}";
                    }
                }
            });
        });

    </script>



    <script>
        $('body').on('click', '#InstrumentForm', function () {
            var selectedValues = [];
            $("#select2 :selected").each(function () {
                selectedValues.push($(this).text());
            });
            var brandSelect = document.getElementById("brand");
            var checkbrand = $('#brand').val();
            if (checkbrand != '0' && checkbrand != '') {
                var brand = brandSelect.options[brandSelect.selectedIndex].text;
            } else {
                var brand = '';
            }

            var checkvolume = $('#volume').val();
            var volumetypeSelect = document.getElementById("volume");
            if (checkvolume != 0 && checkvolume != '') {
                var volumetype = volumetypeSelect.options[volumetypeSelect.selectedIndex].text;
            } else {
                volumetype = '';
            }
            var checkoperation = $('#operation').val();
            var operationSelect = document.getElementById("operation");

            if (checkoperation != '0' && checkoperation != '') {
                var operation = operationSelect.options[operationSelect.selectedIndex].text;

            } else {
                operation = '';
            }
            var checkproduct = $('#producttype').val();
            var productTypeSelect = document.getElementById("producttype");
            if (checkproduct != '0' && checkproduct != '') {
                var productType = productTypeSelect.options[productTypeSelect.selectedIndex].text;
            } else {
                productType = '';
            }
            var checkunit = $('#unit').val();
            var volumerangeSelect = document.getElementById("unit");
            if (checkunit != '0' && checkunit != '') {
                var volumerange = volumerangeSelect.options[volumerangeSelect.selectedIndex].text;
            } else {
                volumerange = '';
            }
            var checkchannels = $('#channels').val();
            var channelsSelect = document.getElementById("channels");
            if (checkchannels != '0' && checkchannels != '') {
                var channels = channelsSelect.options[channelsSelect.selectedIndex].text;
            } else {
                channels = '';
            }


            var resultInput = selectedValues + " " + brand + " " + volumetype + " " + operation + " " + productType + " " + volumerange + " " + channels;

            $('#modelName').val(resultInput)
        });
    </script>
    <script>
        $(".numeric").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
        var index = '{{ $input['i'] }}';
        $('body').on('click', '#addtolerance', function () {
            var temp = index;
            var modelId = $(this).attr('rel');
            index = parseInt(index) + 1;

            if ($("#traget").val() && $("#accuracy").val() && $("#precision").val() && $("#description").val() && $('#precisionul').val() && $('#accuracyul').val()) {

                var description = $('#description').val();
                var target_value = $("#traget").val();
                var accuracy = $("#accuracy").val();
                var precision = $("#precision").val();
                var accuracyul = $("#accuracyul").val();
                var precisionul = $("#precisionul").val();
                var orderdetail = jQuery("#tolelarance").html();

                $('#dataAppend').append(_.template(orderdetail, {
                    description: description,
                    target_value: target_value,
                    accuracy: accuracy,
                    precision: precision,
                    accuracyul: accuracyul,
                    precisionul: precisionul,
                    Id: temp,
                }));
                $('#toleranceBody').show();
            } else {

//                $('.colored-warning').show();

                $('.popUp').trigger('click');


                return false;
                $('#toleranceBody').hide();
            }

        });
    </script>


    <script>
        $('body').on('click', '#addtolerance', function () {
//            console.log('hai')
            var description = $('#description').val('');
            var target_value = $("#traget").val('');
            var accuracy = $("#accuracy").val('');
            var precision = $("#precision").val('');
            var accuracyul = $("#accuracyul").val('');
            var precisionul = $("#precisionul").val('');

        });
        function remove_form(index) {

            $('#' + index).remove();
        }
    </script>

    <script>
        var spareindex = '{{ $input['j'] }}';
        $('body').on('click', '#addspares', function () {


            var sparetemp = spareindex;
            spareindex = parseInt(spareindex) + 1;

            var spares = document.getElementById("sparemode");
            var sparemodeValue = spares.options[spares.selectedIndex].text;
            var sparemode = $('#sparemode').val();
            var Price = $("#Price").val();

            var number = $("#number").val();
            var partname = $("#partname").val();

            if (sparemode != '' && sparemodeValue != '' && sparemode != '' && Price != '' && number != '') {
                var sparedetail = jQuery("#sparesunderscore").html();
                Id = $(".spares-list").length;
                Id++;
                $('#sparesAppend').append(_.template(sparedetail, {

                    sparemode: sparemode,
                    sparemodeValue: sparemodeValue,
                    partname: partname,
                    Price: Price,
                    number: number,
                    Id: sparetemp
                }));
                $('#sparesBody').show();
            } else {
                $('.popUp').trigger('click');


                return false;
                $('#sparesBody').hide();
            }


        });
    </script>
    <script>
        var accessoryindex = '{{ $input['x'] }}';
        $('body').on('click', '#addaccessory', function () {

            var accessorytemp = accessoryindex;
            accessoryindex = parseInt(accessoryindex) + 1;
            var AccessoryPrice = $("#accessory_price").val();
            var AccessorySKUnumber = $("#accessory_sku_number").val();
            var AccessoryName = $("#accessory_name").val();

            if (AccessoryPrice != '' && AccessorySKUnumber != '' && AccessoryName != '') {
                var accessorydetail = jQuery("#accessoryunderscore").html();
                accessoryId = $(".accessory-list").length;
                accessoryId++;
                $('#accessoryAppend').append(_.template(accessorydetail, {

                    AccessoryPrice: AccessoryPrice,

                    AccessorySKUnumber: AccessorySKUnumber,
                    AccessoryName: AccessoryName,
                    accessoryId: accessorytemp
                }));
                $('#accessoryBody').show();
            } else {
                $('.popUp').trigger('click');


                return false;
                $('#accessoryBody').hide();
            }


        });
    </script>

    <script>
        var tipsindex = '{{ $input['y'] }}';
        $('body').on('click', '#addtips', function () {
//console.log('hai')
//        var spares = $('#sparemode').val();
            var tipstemp = tipsindex;
            tipsindex = parseInt(tipsindex) + 1;
            var tipnameValue = $("#tip_name").val();
            var tipPrice = $("#tip_price").val();
            var tipNumber = $("#tip_sku_number").val();


            if (tipnameValue != '' && tipPrice != '' && tipNumber != '') {
                var tipdetail = jQuery("#tipunderscore").html();
                tipId = $(".tip-list").length;
                tipId++;
                $('#tipAppend').append(_.template(tipdetail, {

                    tipname: tipnameValue,

                    tipPrice: tipPrice,
                    tipNumber: tipNumber,
                    tipId: tipstemp
                }));
                $('#tipBody').show();
            } else {
                $('.popUp').trigger('click');
                return false;
                $('#tipBody').hide();
            }


        });
    </script>
    <script>
        var manualdocindex = '{{ $input['z'] }}';
        $('body').on('click', '#addmanualdoc', function () {
            var maunaltemp = manualdocindex;
            manualdocindex = parseInt(manualdocindex) + 1;

            var ManualName = $('#operating_manual_name').val();
            var manualData = $('.manualfile').prop('files')[0];

            manualDocName = new FormData();
            if (manualData) {
                var ManualDocName = manualData.name;
            }


            if (ManualName != '') {
                var manualdocdetails = jQuery("#manualdocunderscore").html();
                Id = $(".manualdoc-list").length;
                Id++;
                $('#manualdocAppend').append(_.template(manualdocdetails, {

                    ManualName: ManualName,
                    ManualDocName: ManualDocName,
                    manualData: manualData,

                    Id: maunaltemp
                }));

                $('#manualdocBody').show();
            } else {
                $('.popUp').trigger('click');


                $('#manualdocBody').hide();
            }


        });
    </script>

    <script>
        var specdocindex = '{{ $input['k'] }}';
        $('body').on('click', '#addspecdoc', function () {
            var spectemp = specdocindex;
            specdocindex = parseInt(specdocindex) + 1;
            var SpecName = $('#specification_name').val();
            var specData = $('.specificationfile').prop('files')[0];

            if (specData) {
                var SpecDocName = specData.name;
            }


            if (SpecName != '') {
                var specdocdetails = jQuery("#specdocunderscore").html();
                Id = $(".specdoc-list").length;
                Id++;
                $('#specdocAppend').append(_.template(specdocdetails, {

                    SpecName: SpecName,
                    SpecDocName: SpecDocName,
                    specData: specData,
                    Id: spectemp
                }));

                $('#specdocBody').show();
            } else {
                $('.popUp').trigger('click');


                $('#manualdocBody').hide();
            }


        });
    </script>
    <script>
        var broucherdocindex = '{{ $input['n'] }}';
        $('body').on('click', '#addbroucherdoc', function () {

            var brouchertemp = broucherdocindex;
            broucherdocindex = parseInt(broucherdocindex) + 1;

            var BroucherName = $('#broucher_name').val();
            var broucherData = $('.broucherfile').prop('files')[0];

            if (broucherData) {
                var BroucherDocName = broucherData.name;
            }


            if (BroucherName != '') {
                var broucherdocdetails = jQuery("#broucherdocunderscore").html();
                Id = $(".broucherdoc-list").length;
                Id++;
                $('#broucherdocAppend').append(_.template(broucherdocdetails, {

                    BroucherName: BroucherName,
                    BroucherDocName: BroucherDocName,
                    broucherData: broucherData,
                    Id: brouchertemp
                }));

                $('#broucherdocBody').show();
            } else {
                $('.popUp').trigger('click');


                $('#broucherdocBody').hide();
            }


        });
    </script>



    <script>
        function remove_spares(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>





    <script>
        function remove_accessory(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>
    <script>
        function remove_tips(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>

    <script>
        $("#volume").on("change", function () {

            var id = $("#volume").val();

            if (id == '1') {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();

            }
            else if (id == '2') {
//                console.log('hai')
                $('#valuerange').show();

                $('#valueto').hide();
                $("#valueform").show();

            }

            else {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();

            }

        });
    </script>


    <script>


        $('body').on('click', '#image', function (event) {
            event.preventDefault()

            var photo = '';
            var Id = $(this).attr('data-id');

            $('#imageshow').hide();
            $('#imageupload').show();
            $.ajax({
                type: 'get',
                url: "{{url("admin/modelphotoUpdate")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    photo: photo,
                    Id: Id,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }


                }
            });
        });</script>
    <script>
        function remove_manualdoc(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>
    <script>
        function remove_specdoc(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>
    <script>
        function remove_broucherdoc(Id) {

            if (Id) {
                $('#' + Id).fadeOut("slow");
                setTimeout(function () {
                    $('#' + Id).remove();
                }, 1000);
            }

        }
    </script>


    <script type="text/html" id="tolelarance" style='border:solid 2px black;'>

        <tr id="<%= Id %>" class='div-lits'>
            <td><%=description %>
                <input type="hidden" name="toleranceArray[<%=Id%>][description]" id="foods-<%= Id %>" value='<%=description%>'/>

            </td>

            <td><%= target_value %>
                <input type="hidden" name="toleranceArray[<%=Id%>][target_value]" id="foods-<%= Id %>" value='<%=target_value%>'/>
            </td>
            <td><%= accuracy %>
                <input type="hidden" name="toleranceArray[<%=Id%>][accuracy]" id="foods-<%= Id %>" value='<%=accuracy%>'/>
            </td>

            <td> <%= precision %>
                <input type="hidden" name="toleranceArray[<%=Id%>][precision]" id="foods-<%= Id %>" value='<%=precision%>'/>
            </td>
              <td> <%= accuracyul %>
                <input type="hidden" name="toleranceArray[<%=Id%>][accuracy_ul]" id="foods-<%= Id %>" value='<%=accuracyul%>'/>
            </td>toleranceArray
              <td> <%= precisionul %>
                <input type="hidden" name="toleranceArray[<%=Id%>][precision_ul]" id="foods-<%= Id %>" value='<%=precisionul%>'/>
            </td>
            <td> <a href="javascript:void(0)"
                    class="removeTolerence"
                    onClick = "remove_form(<%=Id %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>
        </tr>
    </script>
     <script type="text/html" id="sparesunderscore">
        <tr id="<%= Id %>" class="spares-list  div-lits">
         <td style="width:30%"><%=number %>
                <input type="hidden" name="sparedetail[<%=Id%>][number]" id="spare-[<%=Id%>]" value='<%=number%>'/>
            </td>
           <td style="width:30%"><%= sparemodeValue %>
                <input type="hidden" name="sparedetail[<%=Id%>][sparemode]" id="spare-[<%=Id%>]" value='<%=sparemode%>'/>
            </td>


             <td style="width:30%"><%=partname %>
                <input type="hidden" name="sparedetail[<%=Id%>][partname]" id="spare-[<%=Id%>]" value='<%=partname%>'/>
            </td>
             <td style="width:30%"><%=Price %>
                <input type="hidden" name="sparedetail[<%=Id%>][Price]" id="spare-[<%=Id%>]" value='<%=Price%>'/>

            </td>


            <td style="width:10%"> <a href="javascript:void(0)"

                    onClick = "remove_spares(<%=Id %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="manualdocunderscore">
        <tr id="<%= Id %>" class="manualdoc-list  div-lits">
         <td style="width:30%"><%=ManualName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualName]" id="manualdoc-[<%=Id%>]" value='<%=ManualName%>'/>
            </td>
           <td style="width:30%"><%= ManualDocName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualDocName]" id="manualdoc-[<%=Id%>]" value='<%=ManualDocName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    onClick = "remove_manualdoc(<%=Id %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>


     <script type="text/html" id="specdocunderscore">
        <tr id="<%= Id %>" class="specdoc-list  div-lits">
         <td style="width:30%"><%=SpecName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecName]" id="specdoc-[<%=Id%>]" value='<%=SpecName%>'/>
            </td>
           <td style="width:30%"><%= SpecDocName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecDocName]" id="specdoc-[<%=Id%>]" value='<%=SpecDocName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    onClick = "remove_specdoc(<%=Id %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="broucherdocunderscore">
        <tr id="<%= Id %>" class="broucherdoc-list  div-lits">
         <td style="width:30%"><%= BroucherName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherName]" id="broucherdoc-[<%=Id%>]" value='<%=BroucherName%>'/>
            </td>
           <td style="width:30%"><%= BroucherDocName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherDocName]" id="broucherdoc-[<%=Id%>]" value='<%=BroucherDocName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)" onClick = "remove_broucherdoc(<%=Id %>)"
                    data-original-title="Delete"><i class="s7-close td-cross" aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="tipunderscore">
        <tr id="<%= tipId %>" class="tip-list  div-lits">

  <td style="width:30%"><%=tipNumber %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipNumber]" id="spare-[<%=tipId%>]" value='<%=tipNumber%>'/>
            </td>
           <td style="width:30%"><%= tipname %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipname]" id="spare-[<%=tipId%>]" value='<%=tipname%>'/>
            </td>

             <td style="width:30%"><%=tipPrice %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipPrice]" id="spare-[<%=tipId%>]" value='<%=tipPrice%>'/>

            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    onClick = "remove_tips(<%=tipId %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>

    <script type="text/html" id="accessoryunderscore">
        <tr id="<%= accessoryId %>" class="accessory-list  div-lits">
          <td style="width:30%"><%=AccessorySKUnumber %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessorySKUnumber]" id="spare-[<%=accessoryId%>]" value='<%=AccessorySKUnumber%>'/>

            </td>
           <td style="width:30%"><%= AccessoryName %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryName]" id="spare-[<%=accessoryId%>]" value='<%=AccessoryName%>'/>
            </td>

              <td style="width:30%"><%=AccessoryPrice %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryPrice]" id="spare-[<%=accessoryId%>]" value='<%=AccessoryPrice%>'/>
            </td>
            <td style="width:10%"> <a href="javascript:void(0)"

                    onClick = "remove_accessory(<%=accessoryId %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>




            <div id="colored-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>Please fill all the required fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


@stop

