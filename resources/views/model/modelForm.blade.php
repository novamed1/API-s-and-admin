@extends('layout.header')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-slider.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/select2.css')}}">
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

        .required {
            color: red;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Instrument Model Creation </h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Master Set Up</a></li>
                @if($input['id'])
                    <li class="active">Instrument Model Updation</li>
                @else
                    <li class="active">Instrument Model Creation</li>
                @endif
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
                                <li data-step="4" class="wizardlink">Literature<span class="chevron"></span></li>
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
                                                    <label>Brand <span class="required">*</span></label>
                                                    {!!Form::select("Brand",$brand,$input['Brand'],array('class'=>'form-control combobox','id'=>'brand','required'=>"required"))!!}
                                                </div>

                                                <div class="form-group">
                                                    <label>Operation <span class="required">*</span></label>

                                                    {!!Form::select("operation",$operationSelect,$input['operation'],array('class'=>'form-control','id'=>'operation','required'=>"required"))!!}
                                                </div>


                                                <div class="form-group" style="    margin-top: 15px;">
                                                    <label>Channels <span class="required">*</span></label>

                                                    {!!Form::select("channelNo",$channelNumberSelect,$input['channelNo'],array('class'=>'form-control','id'=>'channelNo','required'=>"required"))!!}
                                                </div>


                                                <div class="form-group">
                                                    <label>Volume Type <span class="required">*</span></label>

                                                    {!!Form::select("volume",$volumeSelect,$input['volume'],array('class'=>'form-control','id'=>'volume','required'=>"required"))!!}
                                                </div>

                                                <div class="form-group">
                                                    <label>SKU# <span class="required"></span></label>
                                                    {{--                                                    {!!Form::text("modelSku",$input['modelSku'],array('data-parsley-type'=>"number",'class'=>'form-control modelSku','id'=>'modelSku'))!!}--}}
                                                    {!!Form::text("modelSku",$input['modelSku'],array('class'=>'form-control modelSku','id'=>'modelSku'))!!}
                                                </div>

                                                <div class="form-group">
                                                    <label>Model Sale Price <span class="required">*</span></label>

                                                    {!!Form::text("modelPrice",$input['modelPrice'],array('data-parsley-type'=>"number",'class'=>'form-control modelPrice','id'=>'modelPrice','required'=>"required"))!!}
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
                                                    <label for="check2" class="activebottom div-active">is
                                                        active</label>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18">
                                                {{--@if($input['id'])--}}
                                                {{--<div class="form-group" style="line-height: 7px; margin-top: 16px;">--}}
                                                {{--<label>Manufacturer </label>--}}
                                                {{--{{print_r($input['manufacturer'])}}--}}
                                                {{--<div class="styled-select-lab gender">--}}
                                                {{--{!!Form::text("manufacturer[]",$input['manufacturer'],array('class'=>'form-control','style'=>"width:100%"))!!}--}}
                                                {{--<input type="hidden" name="manufacturerhidden[]"--}}
                                                {{--value="{{$input['manufacturerhidden']}}"--}}
                                                {{--id="manufacturerhidden">--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--@else--}}

                                                {{--<div class="form-group" style="line-height: 7px; margin-top: 16px;">--}}
                                                {{--<label>Manufacturer </label>--}}
                                                {{--{{print_r($input['manufacturer'])}}--}}
                                                {{--<div class="styled-select-lab gender">--}}
                                                {{--{!!Form::select("manufacturer[]",$manufacturer,$input['manufacturer'],array('class'=>'form-control select2','multiple'=>true,'id'=>'select2','style'=>"width:100%"))!!}--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--@endif--}}
                                                <div class="form-group">
                                                    <label>Manufacturer <span class="required">*</span></label>
                                                    {!!Form::select("manufacturer",$manufacturer,$input['manufacturer'],array('class'=>'form-control','id'=>'manufacturer','required'=>"required"))!!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Model Name <span class="required">*</span></label>
                                                    {!!Form::text("model_name",$input['model_name'],array('placeholder' => 'Enter the Name','class'=>'form-control','id'=>'modelName','required'=>"required"))!!}
                                                </div>

                                                <div class="form-group">
                                                    <label>Channel Type <span class="required">*</span></label>

                                                    {!!Form::select("channels",$channels,$input['channels'],array('class'=>'form-control','id'=>'channels','required'=>"required"))!!}
                                                </div>

                                                <div class="form-group">
                                                    <label>Model Description </label>

                                                    {!!Form::textarea("modeldescription",$input['modeldescription'],array('class'=>'form-control  ','id'=>'modeldescription','cols'=>"10", 'rows'=>"3",'readonly'=>'true'))!!}

                                                </div>


                                                <div class="row div-cl-row">
                                                    <section class="col-md-4" id='valueform'>

                                                        <label>Volume From</label>
                                                        {!!Form::text('volume_from',$input['volume_from'], array('data-parsley-type'=>"number",'class'=>'form-control volume_from','required'=>"",'id'=>'volumeFrom')) !!}

                                                    </section>
                                                    @if(($input['id'] && $input['volume']) || !$input['id'])


                                                        <section class="col-md-4" id='valueto'>

                                                            <label>Volume To</label>
                                                            {!!Form::text('volume_to',$input['volume_to'], array('data-parsley-type'=>"number",'class'=>'form-control volume_to fromvalue','id'=>'volumeTo')) !!}

                                                        </section>

                                                    @endif

                                                    <section class="col-md-4" id='valuerange'>

                                                        <label>Units</label>

                                                        {!!Form::select("unit",$modelunits,$input['unit'],array('class'=>'form-control','id'=>'unit','required'=>""))!!}

                                                    </section>


                                                </div>

                                                <div class="form-group">
                                                    <label>Buy or Service</label>

                                                    <div class="am-radio inline">
                                                        <input type="checkbox" name="modelBuy"
                                                               class="form-control"
                                                               {{$input['model_buy']==1?'checked':''}}
                                                               id="modelBuy" value="1">
                                                        <label for="modelBuy">Buy Product</label>
                                                    </div>

                                                    <div class="am-radio inline">
                                                        <input type="checkbox" name="modelService" class="form-control"
                                                               {{$input['model_service']==1?'checked':''}}
                                                               id="modelService"
                                                               value="1">
                                                        <label for="modelService">Buy Calibration</label>
                                                    </div>


                                                </div>


                                            </div>


                                        </div>


                                        <div class="col-sm-12 col-xs-12">
                                            <div class="cart-type">
                                                <legend>Image Upload</legend>


                                                <div class="form-group div-cl-row" id="imageupload">
                                                    <section class="col-md-10">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="file" id="modelimagefile"
                                                               value=""
                                                               class="upload file modelimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="imagefileHidden"
                                                               id="imagefilehidden" value="">
                                                    </section>
                                                    <section class="col-md-2" style="top:29px;">
                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addimages"><i class=''
                                                                             aria-hidden="true">+</i></a>

                                                        <i class="fa fa-spinner fa-spin imageLoader"
                                                           style="display:none"></i>
                                                    </section>

                                                </div>


                                                <div class="panel panel-default" id="imageview"
                                                     style="{{($input['id'] != '')? '':'display:none'}}">

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
                                                                                    <tr id='imagedocBody'
                                                                                        class="imagedoc-list">
                                                                                        <th>Image Name</th>
                                                                                        <th></th>

                                                                                    </tr>
                                                                                    </thead>
                                                                                @else
                                                                                    <thead>
                                                                                    <tr id='imagedocBody'
                                                                                        class="imagedoc-list"
                                                                                        style='display:none;'>
                                                                                        <th>Image Name</th>

                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                @endif
                                                                                <tbody id="imagedocAppend">

                                                                                @if($getImageDetail)
                                                                                    @php($s = $totalimages + 1)
                                                                                    @php($ll = 1)

                                                                                    @foreach($getImageDetail as $imagekey=>$imagerow)

                                                                                        <tr class="div-lits"
                                                                                            id="image-{{$ll}}">
                                                                                            <td>
                                                                                                <img src="{{asset('equipment_model/images/icon/'.$imagerow['imagename'])}}">
                                                                                            </td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"

                                                                                                   class="remove_imagedoc"
                                                                                                   data-id="{{$ll}}"
                                                                                                   data-docu= {{$imagerow['imagename']}}
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                            {!!Form::hidden("imagedocdetail[".$ll."][imagename]",$imagerow['imagename'],array('class'=>'form-control','id'=>'imagename'.'-'.$ll)) !!}

                                                                                            {!!Form::hidden("imagedocdetail[".$ll."][imageDocName]",$imagerow['imageDocName'],array('class'=>'form-control','id'=>'imagedocname'.'-'.$ll)) !!}

                                                                                        </tr>

                                                                                        @php($ll++)
                                                                                    @endforeach



                                                                                @else
                                                                                    @php($s = 0)

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


                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <span style="color: red" ; id="duplicateModelException"></span>
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-primary pull-right btn-space wizard-next next-step"
                                                        data-attr="#InstrumentForm"
                                                        data-url="{{url('admin/checkModelCombination')}}"
                                                        data-token="{!! csrf_token() !!}">
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
                                        @if($input['id'])
                                            <div class="cart-type">
                                                <legend>Manufacturer Specification</legend>
                                                @if(count($testpoints))
                                                    @foreach($testpoints as $tpkey=>$tprow)
                                                        <div class="campaign-type-frm">
                                                            <div class="row">
                                                                <section class="col-md-2">

                                                                    <label>
                                                                        <h5 class="heading">Description</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][description]',$tprow->name, array('class'=>'form-control', 'id'=>'description','readonly')) !!}

                                                                    <span class="campaign-divmsg" id="errmob"></span>

                                                                </section>


                                                                <section class="col-md-2">

                                                                    <label>

                                                                        <h5 class="heading">Target Volume (μl)</h5>
                                                                    </label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][target_value]',(isset($limit[$tpkey]['target_value'])&&$limit[$tpkey]['target_value'])?$limit[$tpkey]['target_value']:"", array('class'=>'form-control numeric','id'=>'traget')) !!}

                                                                </section>


                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(%)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracy]',(isset($limit[$tpkey]['accuracy'])&&$limit[$tpkey]['accuracy'])?$limit[$tpkey]['accuracy']:"", array('class'=>'form-control numeric','id'=>'accuracy')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(%)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][precision]',(isset($limit[$tpkey]['precision'])&&$limit[$tpkey]['precision'])?$limit[$tpkey]['precision']:"", array('class'=>'form-control numeric','id'=>'precision')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(μl)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracyul]',(isset($limit[$tpkey]['accuracy_ul'])&&$limit[$tpkey]['accuracy_ul'])?$limit[$tpkey]['accuracy_ul']:"", array('class'=>'form-control numeric','id'=>'accuracyul')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(μl)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][precisionul]',(isset($limit[$tpkey]['precesion_ul'])&&$limit[$tpkey]['precesion_ul'])?$limit[$tpkey]['precesion_ul']:"", array('class'=>'form-control numeric ','id'=>'precisionul')) !!}
                                                                </section>

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>


                                        @else
                                            <div class="cart-type">
                                                <legend>Manufacturer Specification</legend>
                                                @if(count($testpoints))
                                                    @foreach($testpoints as $tpkey=>$tprow)
                                                        <div class="campaign-type-frm">
                                                            <div class="row">
                                                                <section class="col-md-2">

                                                                    <label>
                                                                        <h5 class="heading">Description</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][description]',$tprow->name, array('class'=>'form-control', 'id'=>'description','readonly')) !!}

                                                                    <span class="campaign-divmsg" id="errmob"></span>

                                                                </section>


                                                                <section class="col-md-2">

                                                                    <label>

                                                                        <h5 class="heading">Target Volume (μl)</h5>
                                                                    </label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][target_value]','', array('class'=>'form-control numeric','id'=>'traget')) !!}

                                                                </section>


                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(%)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracy]','', array('class'=>'form-control numeric','id'=>'accuracy')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(%)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][precision]','', array('class'=>'form-control numeric','id'=>'precision')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(μl)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracyul]','', array('class'=>'form-control numeric','id'=>'accuracyul')) !!}

                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(μl)</h5></label>

                                                                    {!!Form::text('toleranceArray['.$tpkey.'][precisionul]','', array('class'=>'form-control numeric ','id'=>'precisionul')) !!}
                                                                </section>

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>

                                        @endif


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
                                            <legend>Parts</legend>

                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-1">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('number','', array( 'placeholder' => '','class'=>'form-control','id'=>'number')) !!}

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Part Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::select('sparemode',$spareSelect,'', array( 'placeholder' => '','class'=>'form-control','id'=>'sparemode')) !!}

                                                        </div>


                                                    </section>
                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Part Description</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('partname','', array( 'placeholder' => '','class'=>'form-control','id'=>'partname')) !!}

                                                        </div>


                                                    </section>

                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Sell Price</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('Price','', array( 'placeholder' => '','class'=>'form-control sparesPrice','id'=>'Price')) !!}

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>
                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Service Price</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('servicePrice','', array( 'placeholder' => '','class'=>'form-control sparesPrice','id'=>'servicePrice')) !!}

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>

                                                    <section class="col-md-2" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="sparesfile" id="modelsparesimagefile"
                                                               value=""
                                                               class="upload file modelsparesimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modelsparesfileHidden"
                                                               id="modelsparesfilehidden" value="">
                                                    </section>

                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Sell</h5></label>

                                                        <div class="am-radio">
                                                            <input type="checkbox" name="modelBuy"
                                                                   class="form-control"
                                                                   id="partbuy">
                                                            <label for="partbuy"></label>
                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>
                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Service </h5></label>

                                                        <div class="am-radio">
                                                            <input type="checkbox" name="partsell"
                                                                   class="form-control"
                                                                   id="partsell">
                                                            <label for="partsell"></label>
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
                                                                                        <th>SKU#</th>
                                                                                        <th class="">Part Name</th>
                                                                                        <th>Part Description</th>
                                                                                        <th>Sell Price</th>
                                                                                        <th>Service Price</th>
                                                                                        <th>Image</th>
                                                                                        <th>Sell</th>
                                                                                        <th>Service</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                @else
                                                                                    <thead>
                                                                                    <tr id='sparesBody'
                                                                                        style='display:none;'>
                                                                                        <th>SKU#</th>
                                                                                        <th class="">Part Name</th>
                                                                                        <th>Part Description</th>
                                                                                        <th>Sell Price</th>
                                                                                        <th>Service Price</th>
                                                                                        <th>Image</th>
                                                                                        <th>Sell</th>
                                                                                        <th>Service</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                @endif
                                                                                <tbody id="sparesAppend">
                                                                                @if($spares)
                                                                                    @php($j = $totalspares + 1)
                                                                                    @php($i = 1)
                                                                                    @foreach($spares as $sparekey=> $sparerow)

                                                                                        <tr class="div-lits"
                                                                                            id="spares-{{$i}}">
                                                                                            <td> {{$sparerow['number']}}</td>
                                                                                            <td> {{$sparerow['sparemodeValue']}}</td>
                                                                                            <td> {{$sparerow['partname']}}</td>
                                                                                            <td> {{$sparerow['Price']}}</td>
                                                                                            <td> {{$sparerow['servicePrice']}}</td>
                                                                                            <td> {{$sparerow['image']}}</td>
                                                                                            <td>
                                                                                                @if($sparerow['part_buy'] == 1)
                                                                                                    Yes
                                                                                                @else
                                                                                                    No
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                @if($sparerow['part_sell'] == 1)
                                                                                                    Yes
                                                                                                @else
                                                                                                    No
                                                                                                @endif
                                                                                            </td>

                                                                                            <td>
                                                                                                <a href="javascript:void(0)"
                                                                                                   class="removeeditparts"
                                                                                                   data-id="{{$sparerow['spareId']}}"
                                                                                                   data-index="{{$i}}"
                                                                                                   style=""
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                            {!!Form::hidden("sparedetail[".$i."][spareId]",$sparerow['spareId'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][number]",$sparerow['number'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][sparemode]",$sparerow['sparemode'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][partname]",$sparerow['partname'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][Price]",$sparerow['Price'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][servicePrice]",$sparerow['servicePrice'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][sparesdocimageName]",$sparerow['image'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][buy]",$sparerow['part_buy'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}
                                                                                            {!!Form::hidden("sparedetail[".$i."][sell]",$sparerow['part_sell'],array('class'=>'form-control','id'=>'spare'.'-'.$i)) !!}

                                                                                        </tr>


                                                                                        @php($i++)
                                                                                    @endforeach
                                                                                @else
                                                                                    @php($j=0)
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

                                                    <section class="col-md-3">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        {!!Form::text('accessory_sku_number','', array('class'=>'form-control','id'=>'accessory_sku_number','placeholder'=>'')) !!}

                                                    </section>


                                                    <section class="col-md-3">

                                                        <label>
                                                            <h5 class="heading">Accessory Name</h5></label>

                                                        {!!Form::text('accessory_name','', array('class'=>'form-control', 'id'=>'accessory_name','placeholder'=>'')) !!}

                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Accessory Price($)</h5></label>

                                                        {!!Form::text('accessory_price','', array('class'=>'form-control numeric','id'=>'accessory_price','placeholder'=>'')) !!}

                                                    </section>

                                                    <section class="col-md-2" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="accessoryfile"
                                                               id="modelaccessoryimagefile"
                                                               value=""
                                                               class="upload file modelaccessoryimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modelaccessoryfileHidden"
                                                               id="modelaccessoryfileHidden" value="">
                                                    </section>

                                                    {{--<section class="col-md-1">--}}
                                                    {{--<label>--}}

                                                    {{--<h5 class="heading">Buy</h5></label>--}}

                                                    {{--<div class="am-radio">--}}
                                                    {{--<input type="checkbox" name="accessoriesbuy"--}}
                                                    {{--class="form-control"--}}
                                                    {{--id="accessoriesbuy" value="1">--}}
                                                    {{--<label for="accessoriesbuy"></label>--}}
                                                    {{--</div>--}}
                                                    {{--<span class="campaign-divmsg"--}}
                                                    {{--id="campaignamounterror"></span>--}}


                                                    {{--</section>--}}
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
                                                                                        <th>SKU#
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>

                                                                                        <th>Accessory Price($)</th>
                                                                                        <th>Accessory Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                @else
                                                                                    <thead>
                                                                                    <tr id='accessoryBody'
                                                                                        style='display:none;'>
                                                                                        <th>SKU#
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>
                                                                                        <th>Accessory Price($)</th>
                                                                                        <th>Accessory Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                @endif
                                                                                <tbody id="accessoryAppend">
                                                                                @if($accessory)
                                                                                    @php($x= $totalAccessory + 1)
                                                                                    @php($a = 1)
                                                                                    @foreach($accessory as $accessorykey=> $accessoryrow)

                                                                                        <tr class="div-lits"
                                                                                            id="accessory-{{$a}}">
                                                                                            <td> {{$accessoryrow['AccessorySKUnumber']}}</td>
                                                                                            <td> {{$accessoryrow['AccessoryName']}}</td>
                                                                                            <td> {{$accessoryrow['AccessoryPrice']}}</td>
                                                                                            <td> {{$accessoryrow['AccessoryImage']}}</td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"
                                                                                                   data-id="{{$accessoryrow['accessoryId']}}"
                                                                                                   data-index="{{$a}}"
                                                                                                   class="removeeditaccessory"
                                                                                                        {{--onClick="remove_form({{$a}})"--}}
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>

                                                                                            {!!Form::hidden("accessorydetail[".$a."][accessoryId]",$accessoryrow['accessoryId'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}
                                                                                            {!!Form::hidden("accessorydetail[".$a."][AccessorySKUnumber]",$accessoryrow['AccessorySKUnumber'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}
                                                                                            {!!Form::hidden("accessorydetail[".$a."][AccessoryName]",$accessoryrow['AccessoryName'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}
                                                                                            {!!Form::hidden("accessorydetail[".$a."][AccessoryPrice]",$accessoryrow['AccessoryPrice'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}
                                                                                            {!!Form::hidden("accessorydetail[".$a."][AccessoryImage]",$accessoryrow['AccessoryImage'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}
                                                                                            {!!Form::hidden("accessorydetail[".$a."][buy]",$accessoryrow['accessories_buy'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)) !!}

                                                                                        </tr>
                                                                                        @php($a++)
                                                                                    @endforeach
                                                                                @else
                                                                                    @php($x=0)
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
                                            <legend>Tips</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-3">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        {!!Form::text('tip_sku_number','', array('class'=>'form-control','id'=>'tip_sku_number','placeholder'=>'')) !!}

                                                    </section>

                                                    <section class="col-md-3">

                                                        <label>
                                                            <h5 class="heading">Tip Description</h5></label>

                                                        {!!Form::text('tip_name','', array('class'=>'form-control', 'id'=>'tip_name','placeholder'=>'')) !!}

                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Tip Price($)</h5></label>

                                                        {!!Form::text('tip_price','', array('class'=>'form-control numeric','id'=>'tip_price','placeholder'=>'')) !!}

                                                    </section>

                                                    <section class="col-md-3" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Tip
                                                            image</label>
                                                        <input type="file" name="tipfile"
                                                               id="modeltipimagefile"
                                                               value=""
                                                               class="upload file modeltipimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modeltipfileHidden"
                                                               id="modeltipfilehidden" value="">
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
                                                                                    <th>SKU#</th>
                                                                                    <th class="">Tip Description</th>
                                                                                    <th>Tip Price($)</th>
                                                                                    <th>Tip Image</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            @else
                                                                                <thead>
                                                                                <tr id='tipBody' class="tip-list"
                                                                                    style='display:none;'>
                                                                                    <th>SKU#</th>
                                                                                    <th class="">Tip Description</th>
                                                                                    <th>Tip Price($)</th>
                                                                                    <th>Tip Image</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            @endif
                                                                            <tbody id="tipAppend">
                                                                            @if($tips)
                                                                                @php($y = $totalTips + 1)
                                                                                @php($b = 1)

                                                                                @foreach($tips as $tipskey=>$tipsrow)

                                                                                    <tr class="div-lits"
                                                                                        id="tips-{{$b}}">
                                                                                        <td> {{$tipsrow['tipNumber']}}</td>
                                                                                        <td> {{$tipsrow['tipname']}}</td>
                                                                                        <td> {{$tipsrow['tipPrice']}}</td>
                                                                                        <td> {{$tipsrow['tipImage']}}</td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"
                                                                                               class="removeedittips"
                                                                                               data-id="{{$tipsrow['tipId']}}"
                                                                                               data-index="{{$b}}"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        {!!Form::hidden("tipdetail[".$b."][tipId]",$tipsrow['tipId'],array('class'=>'form-control','id'=>'tip'.'-'.$b)) !!}
                                                                                        {!!Form::hidden("tipdetail[".$b."][tipNumber]",$tipsrow['tipNumber'],array('class'=>'form-control','id'=>'tip'.'-'.$b)) !!}

                                                                                        {!!Form::hidden("tipdetail[".$b."][tipname]",$tipsrow['tipname'],array('class'=>'form-control','id'=>'tip'.'-'.$b)) !!}
                                                                                        {!!Form::hidden("tipdetail[".$b."][tipPrice]",$tipsrow['tipPrice'],array('class'=>'form-control','id'=>'tip'.'-'.$b)) !!}
                                                                                        {!!Form::hidden("tipdetail[".$b."][tipImage]",$tipsrow['tipImage'],array('class'=>'form-control','id'=>'tip'.'-'.$b)) !!}

                                                                                    </tr>

                                                                                    @php($b++)
                                                                                @endforeach



                                                                            @else
                                                                                @php($y = 0)

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

                                                            {!!Form::text('operating_manual_name',$input['operating_manual_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'operating_manual_name')) !!}

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
                                                            <input type="hidden" name="manualfileHidden"
                                                                   id="manualfilehidden" value="">

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

                                                                            @if($getManualDetail)
                                                                                @php($z = $totalManualDocs + 1)
                                                                                @php($c = 1)

                                                                                @foreach($getManualDetail as $manualkey=>$manualrow)

                                                                                    <tr class="div-lits"
                                                                                        id="manual-{{$c}}">
                                                                                        <td> {{$manualrow['ManualName']}}</td>
                                                                                        <td> {{$manualrow['ManualDocName']}}</td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_manualdoc"
                                                                                               data-id="{{$c}}"
                                                                                               data-docu= {{$manualrow['manualdocumentname']}}
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        {!!Form::hidden("manualdocdetail[".$c."][ManualName]",$manualrow['ManualName'],array('class'=>'form-control','id'=>'manualname'.'-'.$c)) !!}

                                                                                        {!!Form::hidden("manualdocdetail[".$c."][ManualDocName]",$manualrow['ManualDocName'],array('class'=>'form-control','id'=>'manualdocname'.'-'.$c)) !!}
                                                                                        {!!Form::hidden("manualdocdetail[".$c."][manualdocumentname]",$manualrow['manualdocumentname'],array('class'=>'form-control','id'=>'manualdocumentname'.'-'.$c)) !!}

                                                                                    </tr>

                                                                                    @php($c++)
                                                                                @endforeach



                                                                            @else
                                                                                @php($z = 0)

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
                                        <div class="cart-type">
                                            <legend>Specifications</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Specification Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('specification_name',$input['specification_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'specification_name')) !!}

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
                                                            <input type="hidden" name="specfileHidden"
                                                                   id="specfilehidden" value="">
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
                                                                            @if($getSpecDetail)
                                                                                @php($k = $totalSpecificationDocs + 1)
                                                                                @php($d = 1)

                                                                                @foreach($getSpecDetail as $speckey=>$specrow)

                                                                                    <tr class="div-lits"
                                                                                        id="spec-{{$d}}">
                                                                                        <td> {{$specrow['SpecName']}}</td>
                                                                                        <td> {{$specrow['SpecDocName']}}</td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_specdoc"
                                                                                               data-specid="{{$d}}"
                                                                                               data-specdocu="{{$specrow['specdocumentName']}}"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        {!!Form::hidden("specdocdetail[".$d."][SpecName]",$specrow['SpecName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)) !!}
                                                                                        {!!Form::hidden("specdocdetail[".$d."][SpecDocName]",$specrow['SpecDocName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)) !!}
                                                                                        {!!Form::hidden("specdocdetail[".$d."][specdocumentName]",$specrow['specdocumentName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)) !!}

                                                                                    </tr>

                                                                                    @php($d++)
                                                                                @endforeach



                                                                            @else
                                                                                @php($k = 0)

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
                                        <div class="cart-type">
                                            <legend>Brouchers</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {!!Form::text('broucher_name',$input['broucher_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'broucher_name')) !!}

                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Attachment</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            {{--{!!Form::file('specification_doc','', array('class'=>'form-control','id'=>'specification_doc')) !!}--}}
                                                            <input type="file" name="broucher_doc" value=""
                                                                   class="upload file broucherfile" id="broucherfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                            <input type="hidden" name="broucherfileHidden"
                                                                   id="broucherfilehidden" value="">

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
                                                                            @if($getBroucherDetail)
                                                                                @php($n = $totalBroucherDocs + 1)
                                                                                @php($e = 1)

                                                                                @foreach($getBroucherDetail as $broucherkey=>$broucherrow)

                                                                                    <tr class="div-lits"
                                                                                        id="broucher-{{$e}}">
                                                                                        <td> {{$broucherrow['BroucherName']}}</td>
                                                                                        <td> {{$broucherrow['BroucherDocName']}}</td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_broucherdoc"
                                                                                               data-broucherid="{{$e}}"
                                                                                               data-broucherdocu="{{$broucherrow['broucherdocumentname']}}"

                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        {!!Form::hidden("broucherdocdetail[".$e."][BroucherName]",$broucherrow['BroucherName'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)) !!}

                                                                                        {!!Form::hidden("broucherdocdetail[".$e."][BroucherDocName]",$broucherrow['BroucherDocName'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)) !!}
                                                                                        {!!Form::hidden("broucherdocdetail[".$e."][broucherdocumentname]",$broucherrow['broucherdocumentname'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)) !!}

                                                                                    </tr>

                                                                                    @php($e++)
                                                                                @endforeach



                                                                            @else
                                                                                @php($n = 0)

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
    <div id="saving_container" style="display:none;">
        <div id="saving"
             style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
        <img id="saving_animation" src="{{asset('img/load.gif')}}" alt="saving"
             style="z-index:100001;     margin-left: -42px;margin-top: -86px; position:fixed; left:50%; top:50%"/>

        <div id="saving_text"
             style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">
            <br>
        </div>
    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div>
        <button data-modal="colored-deletewarning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-deletewarning deletePopUp">Warning
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


    <!-- Nifty Modal-->
    <div id="form-primary" class="modal-container modal-colored-header custom-width modal-effect-9">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                            class="icon s7-close"></i></button>
                <h3 class="modal-title">Add New Manufacturer</h3>
            </div>
            <form role="form" id="manufacturerForm" method="post">
                <div class="modal-body form">
                    <div class="form-group">
                        <label>Manufacturer Name</label>
                        <input type="text" placeholder="Please provide manufacturer name" name="manufacturer"
                               class="form-control" id="manu-name">
                    </div>
                    <div class="form-group">
                        <label>Serial Number</label>
                        <input type="text" placeholder="Please provide serial number" name="serialnumber"
                               class="form-control" id="serial_no">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default modal-close closebtn">Cancel
                    </button>
                    <a href="javascript:void(0);"
                       class="btn btn-primary btn-large pull-right createVendor addService adduser"
                       id="addmanufacturer" type="submit">Add</a>

                </div>
            </form>


        </div>
    </div>
    <!-- Nifty Modal-->

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/select2.full.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/wizard.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>

    <script src="{{asset('js/app-form-wizard.js')}}"></script>


    <script>
        $('body').on('click', '#addmanufacturer', function (event) {
            event.preventDefault()

            // var form_dataOther = new FormData();
            // var other_data = $('#manufacturerForm').serializeArray();
            // console.log(other_data)
            // $.each(other_data, function (key, input) {
            //     form_dataOther.append(input.name, input.value);
            // });
            var manufacturer = $('#manu-name').val();
            var serialnumber = $('#serial_no').val();
            if (manufacturer != '' && serialnumber != '') {
                show_animation();
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    url: "{{url("admin/addmanu")}}",
                    data: {serialnumber: serialnumber, manufacturer: manufacturer},
                    // cache: false,
                    // processData: false,
                    // contentType: false,
                    dataType: "json",

                    success: function (data) {
                        hide_animation();
                        if (data.result == true) {
                            $.toast({
                                heading: 'Success',
                                text: 'Manufacturer added successfully.',
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'error',
                                bgColor: 'green',
                            });
                            $('.closebtn').trigger('click');
                            jQuery("#manufacturer").html(data.getManufacturer);
                        } else {

                            $('.popUp').trigger('click');

                        }

                        //window.location = {{url("admin/modellist")}};

                    }
                });
            } else {
                $('.popUp').trigger('click');
            }
        });
    </script>
    <script>
        var imageindex = '{!! $s !!}';
        $('body').on('click', '#addimages', function () {

            $('#imageview').show();
            var fileData = $('.modelimagefile').prop('files')[0];
            if (!fileData) {
                $.toast({
                    heading: 'Warning',
                    text: "Please choose a image and try again",
                    position: 'top-right',
                    showHideTransition: 'slide',
                    icon: 'error',
                    loader: false
                });
                return false;

            }


            var form_dataOther = new FormData();
            form_dataOther.append('modelimagefile', fileData)
            console.log(form_dataOther)
            var data = $('#InstrumentForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });
            $('#addimages').hide();
            $('.imageLoader').show();


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/modelimageupload")}}",
                dataType: "JSON",
                success: function (json) {
                    $('#addimages').show();
                    $('.imageLoader').hide();
                    $('#modelimagefile').val('');
                    if (json.result) {

                        $('#imagefilehidden').val(json.imagefileDocName);

                        var imagetemp = imageindex;
                        imageindex = parseInt(imageindex) + 1;

                        var imagename = $('#imagefilehidden').val();
                        var imageData = $('.modelimagefile').prop('files')[0];


                        console.log(imageData)

                        imageDocName = new FormData();
                        if (imageData) {
                            var imageDocName = imageData.name;
                        }


                        if (imageDocName != '') {
                            var imagedocdetails = jQuery("#imagedocunderscore").html();
                            Id = $(".imagedoc-list").length;
                            Id++;
                            $('#imagedocAppend').append(_.template(imagedocdetails, {


                                imagename: imagename,
                                imageDocName: imageDocName,
                                imageSrc: json.imgSrc,
                                Id: imagetemp
                            }));


                            $('#imagedocBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            $('#imagedocBody').hide();
                        }
                    }
                }
            });

        });
    </script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });


    </script>


    <script>

        $('body').on('click', '#submitForm', function () {

            show_animation();

            var modelId = $('#Id').val();
            var imageFile = $('#imagehidden').val();

            var form_dataOther = new FormData();

            if (imageFile == '') {
                if (document.getElementById("modelimagefile").files.length != 0) {
                    var fileData = $('.modelimagefile').prop('files')[0];
                    form_dataOther.append('modelimage', fileData);
                }
            }

            var data = $('#InstrumentForm, #ToleranceForm, #PartsForm, #DocumentationForm').serializeArray();
            console.log(data);
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/addModel")}}",
                dataType: "JSON",
                success: function (json) {
                    hide_animation();
                    if (json.result) {
                        $('#modelSubmit').trigger('click');
                        //window.location = "{{url("admin/modellist")}}";
                    }
                }
            });
        });

    </script>



    <script>
        $('body').on('mouseover', '.next-step', function () {
            // var selectedValues = [];
            // $("#select2 :selected").each(function () {
            //     selectedValues.push($(this).text());
            // });
            // var selectedValuesId = $('#manufacturer').val();
            var selectedValuestext = document.getElementById("brand");
            var selectedManu = selectedValuestext.options[selectedValuestext.selectedIndex].text;
            var selectedValues = selectedManu.substr(0, selectedManu.indexOf('-'));
            //console.log(streetaddress);

            var manufacturerSelect = document.getElementById("manufacturer");
            var checkmanfacturer = $('#manufacturer').val();
            if (checkmanfacturer != '0' && checkmanfacturer != '') {
                var manufacturer = manufacturerSelect.options[manufacturerSelect.selectedIndex].text;
            } else {
                var manufacturer = '';
            }

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
                var volumetype = $("#volume option:selected").text();
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

            var modelName = $('#modelName').val();
            if (modelName != '0' && modelName != '') {
                var modelNameCombo = modelName;
            } else {
                modelNameCombo = '';
            }
            var volumeFrom = $('#volumeFrom').val();
            var volumeTo = $('#volumeTo').val();
            if(volumeTo)
            {
                var volumeRangeCombo = volumeFrom + '-' + volumeTo;
            }
            else
            {
                var volumeRangeCombo = volumeFrom;
            }


            // var resultInput = brand + " " + modelNameCombo + "  ("+volumeRangeCombo+") "+volumerange+ " " + volumetype + " " + operation + " "  + " " + " " + channels+" "+productType;
            var resultInput = manufacturer + " " + brand + " " + modelNameCombo + " (" + volumeRangeCombo + ") " + volumerange + " " + volumetype + " " + operation + " " + channels + " " + productType;
            console.log(resultInput);
            $('#modeldescription').val(resultInput);

            $('#number').val(selectedValues);
            $('#accessory_sku_number').val(selectedValues);
            $('#tip_sku_number').val(selectedValues);
            var id = $('#Id').val();
            console.log(volumeTo);
            if(volumetype == 'Variable'){
                console.log('hi');
                if(volumeTo  == ''){
                    console.log('hisf');
                    $.toast({
                        heading: 'Warning',
                        text: 'VolumeTo field is missing',
                        position: 'top-left',
                        showHideTransition: 'slide',
                        icon: 'error',
                        loader: false
                    });
                    $('.next-step').attr('disabled', 'disabled');
                    return false;
                }else{
                    $('.next-step').removeAttr('disabled');
                    return false;
                }
            }else {

                $.ajax({
                    type: 'post',
                    url: "{{url("admin/checkModelCombination")}}",
                    data: {
                        channel: checkchannels,
                        brand: checkbrand,
                        operation: checkoperation,
                        volume: checkvolume,
                        volumeRange: volumeRangeCombo,
                        id: id,
                        "_token": "{!! csrf_token() !!}"
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data) {
                            if (data.result) {
                                $.toast({
                                    heading: 'Warning',
                                    text: data.message,
                                    //position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'error',

                                    loader: false
                                });
                                $('.next-step').attr('disabled', 'disabled');
                                return false;
                            } else {
                                $('.next-step').removeAttr('disabled');
                                return false;
                            }
                        }
                    }
                });
            }
            {{--{{url('admin/checkModelCombination')}}--}}
        });

    </script>
    <script>
        $(".numeric").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".volume_to").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".volume_from").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>

    <script>
        $(".sparesPrice").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".modelPrice").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>

    <script>
        $('body').on('change', '#channels', function () {
            var checkchannels = $('#channels').val();
            $.ajax({

                type: "get",
                url: "{{url("admin/getchannelnumbers")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    channel_id: checkchannels,
                },
                dataType: "JSON",
                success: function (json) {


                    if (json.result == true) {

//                        jQuery("#equipmentunderscore").html();
                        jQuery("#channelNo").html(json.getChannels);

                    }
                }
            });
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

        function remove_form(index) {

            $('#' + index).remove();
        }
    </script>

    <script>
                {{--var spareindex = '{{ $input['j'] }}';--}}
        var spareindex = {!! $j !!};
        $('body').on('click', '#addspares', function () {

            var fileData = $('.modelsparesimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('sparesImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/modelsparesimageUpload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        var sparetemp = spareindex;
                        spareindex = parseInt(spareindex) + 1;

                        $('#modelsparesfilehidden').val(json.spareimageDocName);


                        var sparesdocimageName = $('#modelsparesfilehidden').val();
                        var sparesData = $('.modelsparesimagefile').prop('files')[0];

                        sparesdocshowname = new FormData();
                        if (sparesData) {
                            var sparesdocshowname = sparesData.name;
                        }


                        var spares = document.getElementById("sparemode");
                        var sparemodeValue = spares.options[spares.selectedIndex].text;
                        var sparemode = $('#sparemode').val();
                        var Price = $("#Price").val();
                        var servicePrice = $("#servicePrice").val();

                        var number = $("#number").val();
                        var partname = $("#partname").val();
                        if ($("#partbuy").prop("checked") == true) {
                            var buy = 1;
                        } else {
                            var buy = 0;
                        }
                        if ($("#partsell").prop("checked") == true) {
                            var sell = 1;
                        } else {
                            var sell = 0;
                        }

                        if (sparemode != '' && sparemodeValue != '' && sparemode != '' && Price != '' && number != '' && servicePrice != '') {
                            var sparedetail = jQuery("#sparesunderscore").html();
                            Id = $(".spares-list").length;
                            Id++;
                            $('#sparesAppend').append(_.template(sparedetail, {

                                sparemode: sparemode,
                                sparemodeValue: sparemodeValue,
                                sparesdocshowname: sparesdocshowname,
                                sparesdocimageName: sparesdocimageName,
                                partname: partname,
                                Price: Price,
                                servicePrice: servicePrice,
                                number: number,
                                Id: sparetemp,
                                buy: buy,
                                sell: sell
                            }));
                            $('#sparesBody').show();
                            $('#sparemode').val('');
                             $("#Price").val('');
                             $("#servicePrice").val('');
                              $("#number").val('');
                             $("#partname").val('');
                             $("#modelsparesimagefile").val('');
                            $('#partbuy').prop('checked', false);
                            $('#partsell').prop('checked', false);
                        } else {
                            $('.popUp').trigger('click');


                            return false;
                            $('#sparesBody').hide();
                        }

                    }
                }
            });


        });
    </script>

    <script>
        var accessoryindex = {!! $x !!};

        $('body').on('click', '#addaccessory', function () {
            var fileData = $('.modelaccessoryimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('accessoryImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/modelaccessoryimageUpload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        // console.log(json.accessoryimageDocName)


                        $('#modelaccessoryfileHidden').val(json.accessoryimageDocName);

                        var accessorydocimageName = $('#modelaccessoryfileHidden').val();
                        var accessoryData = $('.modelaccessoryimagefile').prop('files')[0];

                        accessorydocshowname = new FormData();
                        if (accessoryData) {
                            var accessorydocshowname = accessoryData.name;
                        }


                        var accessorytemp = accessoryindex;
                        accessoryindex = parseInt(accessoryindex) + 1;
                        var AccessoryPrice = $("#accessory_price").val();
                        var AccessorySKUnumber = $("#accessory_sku_number").val();
                        var AccessoryName = $("#accessory_name").val();
                        var buy = $("#accessoriesbuy").val();

                        if (AccessoryPrice != '' && AccessorySKUnumber != '' && AccessoryName != '') {
                            var accessorydetail = jQuery("#accessoryunderscore").html();
                            accessoryId = $(".accessory-list").length;
                            accessoryId++;
                            $('#accessoryAppend').append(_.template(accessorydetail, {

                                AccessoryPrice: AccessoryPrice,

                                AccessorySKUnumber: AccessorySKUnumber,
                                AccessoryName: AccessoryName,
                                accessorydocshowname: accessorydocshowname,
                                accessorydocimageName: accessorydocimageName,
                                accessoryId: accessorytemp,
                                buy: buy
                            }));
                            $('#accessoryBody').show();
                         $("#accessory_price").val('');
                          $("#accessory_sku_number").val('');
                          $("#accessory_name").val('');
                          $("#modelaccessoryimagefile").val('');
                         $("#accessoriesbuy").val();
                        } else {
                            $('.popUp').trigger('click');


                            return false;
                            $('#accessoryBody').hide();
                        }

                    }
                }
            });


        });


    </script>

    <script>
        var tipsindex = {!! $y !!};


        $('body').on('click', '#addtips', function () {

            var fileData = $('.modeltipimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('tipImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/modeltipimageUpload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {


                        $('#modeltipfilehidden').val(json.tipimageDocName);

                        var tipdocimageName = $('#modeltipfilehidden').val();
                        var tipData = $('.modeltipimagefile').prop('files')[0];

                        tipdocshowname = new FormData();
                        if (tipData) {
                            var tipdocshowname = tipData.name;
                        }

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
                                tipdocimageName: tipdocimageName,
                                tipdocshowname: tipdocshowname,
                                tipId: tipstemp
                            }));
                            $('#tipBody').show();
                            $("#tip_name").val('');
                           $("#tip_price").val('');
                          $("#tip_sku_number").val('');
                          $("#modeltipimagefile").val('');
                        } else {
                            $('.popUp').trigger('click');
                            return false;
                            $('#tipBody').hide();
                        }

                    }
                }
            });


        });
    </script>
    <script>
        var manualdocindex = '{!! $z !!}';
        $('body').on('click', '#addmanualdoc', function () {


            var fileData = $('.manualfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('manualfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/manualdocupload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#manualfilehidden').val(json.manualfileDocName);

                        var maunaltemp = manualdocindex;
                        manualdocindex = parseInt(manualdocindex) + 1;

                        var ManualName = $('#operating_manual_name').val();
                        var manualdocumentname = $('#manualfilehidden').val();
                        var manualData = $('.manualfile').prop('files')[0];

                        manualDocName = new FormData();
                        if (manualData) {
                            var ManualDocName = manualData.name;
                        }

                        console.log('hi');
                        if (ManualName != '') {
                            var manualdocdetails = jQuery("#manualdocunderscore").html();
                            Id = $(".manualdoc-list").length;
                            Id++;
                            $('#manualdocAppend').append(_.template(manualdocdetails, {

                                ManualName: ManualName,
                                ManualDocName: ManualDocName,
                                manualdocumentname: manualdocumentname,
                                manualData: manualData,

                                Id: maunaltemp
                            }));


                            $('#manualdocBody').show();
                          $('#operating_manual_name').val('');
                          $('.manualfile').val('');
                        } else {
                            $('.popUp').trigger('click');


                            $('#manualdocBody').hide();
                        }
                    }
                }
            });

        });
    </script>

    <script>
        var specdocindex = '{{ $k }}';
        $('body').on('click', '#addspecdoc', function () {

            var fileData = $('.specificationfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('specificationfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/specdocupload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#specfilehidden').val(json.specfileDocName);


                        var spectemp = specdocindex;
                        specdocindex = parseInt(specdocindex) + 1;
                        var SpecName = $('#specification_name').val();
                        var specData = $('.specificationfile').prop('files')[0];
                        var specdocumentName = $('#specfilehidden').val();

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
                                specdocumentName: specdocumentName,
                                Id: spectemp
                            }));

                            $('#specdocBody').show();
                             $('#specification_name').val('');
                             $('.specificationfile').val('');
                        } else {
                            $('.popUp').trigger('click');


                            $('#manualdocBody').hide();
                        }
                    }
                }
            });


        });
    </script>
    <script>
        var broucherdocindex = '{{ $n }}';
        $('body').on('click', '#addbroucherdoc', function () {

            var fileData = $('.broucherfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('broucherfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "{{url("admin/broucherdocupload")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#broucherfilehidden').val(json.broucherfileDocName);

                        var brouchertemp = broucherdocindex;
                        broucherdocindex = parseInt(broucherdocindex) + 1;

                        var BroucherName = $('#broucher_name').val();
                        var broucherData = $('.broucherfile').prop('files')[0];
                        var broucherdocumentname = $('#broucherfilehidden').val();

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
                                broucherdocumentname: broucherdocumentname,
                                broucherData: broucherData,
                                Id: brouchertemp
                            }));

                            $('#broucherdocBody').show();
                           $('#broucher_name').val('');
                           $('#broucherfile').val('');
                        } else {
                            $('.popUp').trigger('click');


                            $('#broucherdocBody').hide();
                        }

                    }
                }
            });


        });
    </script>



    <script>

        $('body').on('click', '.removeSpares', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#spares-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#spares-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeeditparts', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "{{url("admin/deletedModelParts")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#spares-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#spares-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });


    </script>
    <script>

        $('body').on('click', '.removeaccessory', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            console.log(deleteIndex);
            $('#accessory-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#accessory-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeeditaccessory', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "{{url("admin/deletedModelAccessory")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#accessory-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#accessory-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });
    </script>
    <script>

        $('body').on('click', '.removeTips', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#tips-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#tips-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeedittips', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "{{url("admin/deletedModelTips")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#tips-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#tips-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });
    </script>

    <script>
        $("#volume").on("change", function () {

            var id = $("#volume").val();

            if (id == '1') {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();

            } else if (id == '2') {
//                console.log('hai')
                $('#valuerange').show();

                $('#valueto').hide();
                $("#valueform").show();

            } else {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();
            }

        });
    </script>


    <script>
        $('body').on('click', '.remove_manualdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteDocument = $(this).attr('data-docu');


            $.ajax({
                type: 'post',
                url: "{{url("admin/unlinkmanualdoc")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#manual-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#manual-' + deleteId).remove();
            }, 500);


        });
    </script>
    <script>
        $('body').on('click', '.remove_imagedoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteDocument = $(this).attr('data-docu');


            $.ajax({
                type: 'post',
                url: "{{url("admin/unlinkimagedoc")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#image-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#image-' + deleteId).remove();
            }, 500);


        });
    </script>
    <script>
        $('body').on('click', '.remove_specdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-specid');
            var deleteDocument = $(this).attr('data-specdocu');


            $.ajax({
                type: 'post',
                url: "{{url("admin/unlinkspecdoc")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#spec-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#spec-' + deleteId).remove();
            }, 500);

        });
    </script>
    <script>
        $('body').on('click', '.remove_broucherdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-broucherid');
            var deleteDocument = $(this).attr('data-broucherdocu');


            $.ajax({
                type: 'post',
                url: "{{url("admin/unlinkbroucherdoc")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#broucher-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#broucher-' + deleteId).remove();
            }, 500);


        });

        $('body').on('change', '#manufacturer', function (event) {

            var manufacturer = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{url("admin/getBrands")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    manufacturer: manufacturer,
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        console.log(data.data);
                        $('#brand').html(data.data);
                    }


                }
            });


        });
    </script>
    <script>
        $(document).ready(function () {
            $('.combobox').combobox()
        });

        var gaq = gaq || [];
        _gaq.push(['_setAccount', 'UA-36251023-1']);
        _gaq.push(['_setDomainName', 'jqueryscript.net']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>

    <script type="text/html" id="manualdocunderscore">
        <tr id="manual-<%= Id %>" class="manualdoc-list  div-lits">
         <td style="width:30%"><%=ManualName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualName]" id="manualname-<%=Id%>" value='<%=ManualName%>'/>
            </td>
           <td style="width:30%"><%= ManualDocName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualDocName]" id="manualdocname-<%=Id%>" value='<%=ManualDocName%>'/>
                <input type="hidden" name="manualdocdetail[<%=Id%>][manualdocumentname]" id="manualdocumentname-<%=Id%>" value='<%=manualdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_manualdoc"
                    data-id="<%= Id %>" data-docu="<%= manualdocumentname %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
    <script type="text/html" id="sparesunderscore">
        <tr id="spares-<%= Id %>" class="spares-list  div-lits">
         <td style="width:30%"><%=number %>
                         <input type="hidden" name="sparedetail[<%=Id%>][spareId]" id="price-[<%=Id%>]" value=''/>

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
            <td style="width:30%"><%=servicePrice %>
                <input type="hidden" name="sparedetail[<%=Id%>][servicePrice]" id="spare-[<%=Id%>]" value='<%=servicePrice%>'/>

            </td>
             <td style="width:30%"><%=sparesdocshowname %>
                <input type="hidden" name="sparedetail[<%=Id%>][sparesdocimageName]" id="spare-[<%=Id%>]" value='<%=sparesdocimageName%>'/>

            </td>
            <% if(buy== 1){ %>
              <td style="width:30%">Yes
               <input type="hidden" name="sparedetail[<%=Id%>][buy]" id="spare-[<%=Id%>]" value='<%=buy%>'/>
              </td>
                 <% } else {%>
               <td style="width:30%">No
               <input type="hidden" name="sparedetail[<%=Id%>][buy]" id="spare-[<%=Id%>]" value='<%=buy%>'/>
              </td>
               <% } %>

                <% if(sell == 1){ %>
              <td style="width:30%">Yes
              <input type="hidden" name="sparedetail[<%=Id%>][sell]" id="spare-[<%=Id%>]" value='<%=sell%>'/>
              </td>
               <% } else {%>
               <td style="width:30%">No
               <input type="hidden" name="sparedetail[<%=Id%>][sell]" id="spare-[<%=Id%>]" value='<%=sell%>'/>
              </td>
               <% } %>


            <td style="width:10%"> <a href="javascript:void(0)"
class="removeSpares" data-index="<%= Id %>" data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="manualdocunderscore">
        <tr id="manual-<%= Id %>" class="manualdoc-list  div-lits">
         <td style="width:30%"><%=ManualName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualName]" id="manualname-<%=Id%>" value='<%=ManualName%>'/>
            </td>
           <td style="width:30%"><%= ManualDocName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualDocName]" id="manualdocname-<%=Id%>" value='<%=ManualDocName%>'/>
                <input type="hidden" name="manualdocdetail[<%=Id%>][manualdocumentname]" id="manualdocumentname-<%=Id%>" value='<%=manualdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_manualdoc"
                    data-id="<%= Id %>" data-docu="<%= manualdocumentname %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="imagedocunderscore">
        <tr id="image-<%= Id %>" class="imagedoc-list  div-lits">

           <td style="width:30%"><%= imageSrc %>

                <input type="hidden" name="imagedocdetail[<%=Id%>][imagename]" id="imagedocname-<%=Id%>" value='<%=imagename%>'/>
                <input type="hidden" name="imagedocdetail[<%=Id%>][imageDocName]" id="imagedocumentname-<%=Id%>" value='<%=imageDocName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_imagedoc"
                    data-id="<%= Id %>" data-docu="<%= imagename %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>


     <script type="text/html" id="specdocunderscore">
        <tr id="spec-<%= Id %>" class="specdoc-list  div-lits">
         <td style="width:30%"><%=SpecName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecName]" id="specname-[<%=Id%>]" value='<%=SpecName%>'/>
            </td>
           <td style="width:30%"><%= SpecDocName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecDocName]" id="specdocname-[<%=Id%>]" value='<%=SpecDocName%>'/>
                <input type="hidden" name="specdocdetail[<%=Id%>][specdocumentName]" id="specdocumentname-[<%=Id%>]" value='<%=specdocumentName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_specdoc"
                     data-specid="<%=Id%>" data-specdocu="<%= specdocumentName %>"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="broucherdocunderscore">
        <tr id="broucher-<%= Id %>" class="broucherdoc-list  div-lits">
         <td style="width:30%"><%= BroucherName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherName]" id="brouchername-[<%=Id%>]" value='<%=BroucherName%>'/>
            </td>
           <td style="width:30%"><%= BroucherDocName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherDocName]" id="broucherdocname-[<%=Id%>]" value='<%=BroucherDocName%>'/>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][broucherdocumentname]" id="broucherdocumentname-[<%=Id%>]" value='<%=broucherdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)" class = "remove_broucherdoc"
            data-broucherid="<%= Id %>" data-broucherdocu="<%= broucherdocumentname %>"
                    data-original-title="Delete"><i class="s7-close td-cross" aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="tipunderscore">
        <tr id="tips-<%= tipId %>" class="tip-list  div-lits">

  <td style="width:30%"><%=tipNumber %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipId]" id="tip-[<%=tipId%>]" value=''/>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipNumber]" id="tip-[<%=tipId%>]" value='<%=tipNumber%>'/>
            </td>
           <td style="width:30%"><%= tipname %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipname]" id="tip-[<%=tipId%>]" value='<%=tipname%>'/>
            </td>

             <td style="width:30%"><%=tipPrice %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipPrice]" id="tip-[<%=tipId%>]" value='<%=tipPrice%>'/>

            </td>
<td style="width:30%"><%=tipdocshowname %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipImage]" id="tip-[<%=Id%>]" value='<%=tipdocimageName%>'/>

            </td>
            <td style="width:10%"> <a href="javascript:void(0)"

class='removeTips' data-index="<%= tipId %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>

    <script type="text/html" id="accessoryunderscore">
        <tr id="accessory-<%= accessoryId %>" class="accessory-list  div-lits">
          <td style="width:30%"><%=AccessorySKUnumber %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][accessoryId]" id="accessory-[<%=accessoryId%>]" value=''/>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessorySKUnumber]" id="accessory-[<%=accessoryId%>]" value='<%=AccessorySKUnumber%>'/>

            </td>
           <td style="width:30%"><%= AccessoryName %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryName]" id="accessory-[<%=accessoryId%>]" value='<%=AccessoryName%>'/>
            </td>

              <td style="width:30%"><%=AccessoryPrice %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryPrice]" id="accessory-[<%=accessoryId%>]" value='<%=AccessoryPrice%>'/>
            </td>
              <td style="width:30%"><%=accessorydocshowname %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryImage]" id="accessory-[<%=accessoryId%>]" value='<%=accessorydocimageName%>'/>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][buy]" id="accessory-[<%=accessoryId%>]" value='<%=buy%>'/>

            </td>
            <td style="width:10%"> <a href="javascript:void(0)"
                    class="removeaccessory" data-index="<%= accessoryId %>"

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

       <div id="colored-deletewarning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>This spare is in progress.You can't able to delete.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


@stop

