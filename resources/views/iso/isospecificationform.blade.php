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
        .error{
            color:red;
        }

        .fullWidth {
            width: 100%;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>ISO Specification</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Master SetUp</a></li>
                <li class="active">ISO Specification/Tolerance</li>
            </ol>
        </div>
        <div class="main-content">
            @if($input['id'])
                {!! Form::model($input, array('url' => 'admin/editisospecification/'. $input['id'])) !!}

            @else

                {!! Form::open(['url'=>'admin/isospecification']) !!}
            @endif
            <input type="hidden" name="id" value="{{$input['id']}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 style="font-weight:600;">ISO Specification/Tolerance</h3>
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label>Channel Type</label>
                                            {{Form::select('channel_id',$channels,$input['channel_id'],array('class'=>'form-control','id'=>'channels'))}}

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label>Channel Number</label>
                                            {{Form::select('channel_number',$channelNumberSelect,$input['channel_number'],array('class'=>'form-control','id'=>'channel_number'))}}

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label>Operation</label>
                                            {{Form::select('operation_id',$operations,$input['operation_id'],array('class'=>'form-control','id'=>'operation'))}}

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label>Volume Type</label>

                                            {{Form::select('volume_id',$volumes,$input['volume_id'],array('class'=>'form-control volumeChange','id'=>'volume'))}}
                                        </div>

                                    </div>
                                </div>

                            </div>
                            {{--<div class="row">--}}

                            {{----}}

                            {{--</div>--}}

                            <div class="row">





                            </div>

                            <div class="row div-cl-row">
                                <section class="col-md-4" id='valueform'>

                                    <label>Volume From</label>
                                    {!!Form::text('volume_from',$input['volume_value_from'], array('data-parsley-type'=>"number",'class'=>'form-control volume_from','required'=>"",'id'=>'volumeFrom','onkeypress'=>'return isNumberKey(event,this)')) !!}

                                </section>
                                @if((($input['id'] && $input['volume_value_to'])))


                                    <section class="col-md-4" id='valueto'>

                                        <label>Volume To</label>
                                        {!!Form::text('volume_to',$input['volume_value_to'], array('data-parsley-type'=>"number",'class'=>'form-control volume_to fromvalue','id'=>'volumeToRange','onkeypress'=>'return isNumberKey(event,this)')) !!}

                                    </section>
                                    @else
                                    <section class="col-md-4" id='volumeTo' style="display: none;">

                                        <label>Volume To</label>
                                        {!!Form::text('volume_to',$input['volume_value_to'], array('data-parsley-type'=>"number",'class'=>'form-control volume_to fromvalue','id'=>'volumeToRange')) !!}

                                    </section>

                                @endif

                                <section class="col-md-4" id='valuerange'>

                                    <label>Units</label>

                                    {!!Form::select("unit",$units,$input['unit'],array('class'=>'form-control','id'=>'unit','required'=>""))!!}

                                </section>


                            </div>


                        </div>


                    </div>


                </div>

                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="">
                            <legend>Tolerances</legend>
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

                                            <h5 class="heading">Target Volume (µl)</h5></label>

                                        {!!Form::text('toleranceArray['.$tpkey.'][target_value]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->target_value)?$input['tolerances'][$tpkey]->target_value:'', array('class'=>'form-control numeric','id'=>'traget')) !!}

                                    </section>


                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Accuracy(%)</h5></label>

                                        {!!Form::text('toleranceArray['.$tpkey.'][accuracy]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->accuracy)?$input['tolerances'][$tpkey]->accuracy:'', array('class'=>'form-control accuracy_numeric','id'=>'accuracy','onkeypress'=>'return isNumberKey(event,this)')) !!}

                                    </section>

                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Precision(%)</h5></label>

                                        {!!Form::text('toleranceArray['.$tpkey.'][precision]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->precision)?$input['tolerances'][$tpkey]->precision:'', array('class'=>'form-control precision_numeric','id'=>'precision','onkeypress'=>'return isNumberKey(event,this)')) !!}

                                    </section>

                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Accuracy(μl)</h5></label>

                                        {!!Form::text('toleranceArray['.$tpkey.'][accuracyul]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->accuracy_ul)?$input['tolerances'][$tpkey]->accuracy_ul:'', array('class'=>'form-control accuracy_ul_numeric','id'=>'accuracyul','onkeypress'=>'return isNumberKey(event,this)')) !!}

                                    </section>

                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Precision(μl)</h5></label>

                                        {!!Form::text('toleranceArray['.$tpkey.'][precisionul]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->precesion_ul)?$input['tolerances'][$tpkey]->precesion_ul:'', array('class'=>'form-control precesion_ul_numeric ','id'=>'precisionul','onkeypress'=>'return isNumberKey(event,this)')) !!}
                                    </section>

                                </div>
                            </div>
                                @endforeach
                            @endif

                        </div>


                    </div>


                    <div class="panel panel-default" style='text-align: center;'>

                        <div class="panel-body">

                            <div class="text-center">
                                <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                <a href="{{url('admin/isospecificationlist')}}" class="btn btn-space btn-default">Cancel</a>
                            </div>

                        </div>

                    </div>

                </div>



            </div>
            </form>
        </div>

    </div>



    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/jquery.validate.js')}}"></script>

    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/wizard.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>
    <script src="{{asset('css/lib/select2/js/select2.min.js')}}"></script>

    {{--<script src="{{asset('js/autocomplete/jquery.autocomplete.js')}}"></script--}}
    <script src="{{asset('js/app-form-wizard.js')}}"></script>

    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/autocomplete/autocomplete.css')}}">--}}



    <script>
        $('body').on('click', '.removeeditprice', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');


            $.ajax({
                type: 'post',
                url: "{{url("admin/deleteServicePricing")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            if (data.value == 1) {
                                $('.deletePopUp').trigger('click');
                                return false;
                            }

                        } else {
                            $('#price-' + deleteIndex).fadeOut("slow");

                            setTimeout(function () {
                                $('#price-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });
        $('body').on('click', '.remove_price', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#price-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#price-' + deleteIndex).remove();
            }, 500);
        });

        $('body').on('click', '.volumeChange', function (event) {
            event.preventDefault();
            var val = $(this).val();
            if(val==1)
            {
                $('#volumeTo').show();
            }
            else
            {
                $('#volumeTo').hide();
            }
        });

        $('body').on('change', '#unit', function (event) {
            event.preventDefault();
            var channel = $('#channel').val();
            var operation = $('#operation').val();
            var volume = $('#volume').val();
            var volumeFrom = $('#volumeFrom').val()?$('#volumeFrom').val():'';
            var volumeTo = $('#volumeToRange').val()?$('#volumeToRange').val():'';
            var volumeRange = '';
            if(volumeFrom&&volumeTo)
            {
                volumeRange = volumeFrom+'-'+volumeTo;
            }
            if(channel==''||operation==''||volume=='')
            {
                $.toast({
                    heading: 'Warning',
                    text: "Before choose unit,Channel,Operation,Volume should be selected",
                    //position: 'top-left',
                    showHideTransition: 'slide',
                    icon: 'error',

                    loader: false
                });
            }
            else
            {
                $.ajax({
                    type: 'post',
                    url: "{{url("admin/checktolerancecombination")}}",
                    data: {
                        channel:channel,operation:operation,volume:volume,volumeRange:volumeRange,"_token": "{!! csrf_token() !!}"
                    },
                    dataType: "json",
                    success: function (data) {
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
                            }

                        }
                    }

                });
            }

        });
//Decimal Number Validation
//         $(function () {
//             $(".accuracy_numeric,.precision_numeric,.precesion_ul_numeric,.accuracy_ul_numeric").keydown(function (event) {
//                 if (event.shiftKey == true) {
//                     event.preventDefault();
//                 }
//                 if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
//                 } else {
//                     event.preventDefault();
//                     $.toast({
//                         heading: 'Warning',
//                         text: 'Accept only Numbers and Decimal Values',
//                         //position: 'top-left',
//                         showHideTransition: 'slide',
//                         icon: 'error',
//
//                         loader: false
//                     });
//                 }
//                 if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
//                     event.preventDefault();
//             });
//         });

        function isNumberKey(evt, obj) {

            var charCode = (evt.which) ? evt.which : event.keyCode
            var value = obj.value;
            var dotcontains = value.indexOf(".") != -1;
            if (dotcontains)
                if (charCode == 46) return false;
            if (charCode == 46) return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        // $('#volumeFrom,#volumeToRange').bind('keyup paste', function(){
        //     this.value = this.value.replace(/[^0-9]/g, '');
        // });
        $("form").submit(function(e){
            e.preventDefault();
            var channel = $('#channels').val();
            var operation = $('#operation').val();
            var volume = $('#volume').val();
            var id = "{{$input['id']}}";
            var volumeFrom = $('#volumeFrom').val();
            var volumeTo = $('#volumeToRange').val();
            var volumeRange = '';
            if(volumeFrom&&volumeTo)
            {
                volumeRange = volumeFrom+'-'+volumeTo;
            }
            if(volume == 2){
                var volumeFrom = $('#volumeFrom').val();
            }else{
                var volumeFrom = '';
            }
            $.ajax({
                type: 'post',
                url: "{{url("admin/checktolerancecombination")}}",
                data: {
                    channel:channel,operation:operation,volume:volume,volumeFrom:volumeFrom,volumeRange:volumeRange,id:id,"_token": "{!! csrf_token() !!}"
                },
                dataType: "json",
                success: function (data) {
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
                        jQuery("#channel_number").html(json.getChannels);

                    }
                }
            });
        });

    </script>







    <script type="text/html" id="priceunderscore">
        <tr class="testexist" volume-attr="<%= volumeText %>"
        operation-attr="<%= operationText %>"
        channel-attr="<%= channelText %>"
        channelnumber-attr="<%= channelNumberText %>"
        point-attr="<%= pointText %>"

         id="price-<%= index %>" class="spares-list  div-lits">
           <td style="" class='' attr='volume'><%= volumeText %>
                <input type="hidden" name="priceDetail[<%=index%>][Id]" id="price-[<%=index%>]" value=''/>
                <input type="hidden" name="priceDetail[<%=index%>][volume]" id="price-[<%=index%>]" value='<%=volumeValue%>'/>
            </td>


             <td style="" class='' attr='operation'><%=operationText %>
                <input type="hidden" name="priceDetail[<%=index%>][operation]" id="price-[<%=index%>]" value='<%=operationValue%>'/>

            </td>
              <td style="" class='' attr='channel'><%=channelText %>
                <input type="hidden" name="priceDetail[<%=index%>][channel]" id="price-[<%=index%>]" value='<%=channelValue%>'/>
            </td>
            <td style="" class='' attr='channelnumber'><%=channelNumberText %>
                <input type="hidden" name="priceDetail[<%=index%>][channelNumber]" id="price-[<%=index%>]" value='<%=channelNumberValue%>'/>
            </td>
            <td style="" class='' attr='points'><%=pointText %>
                <input type="hidden" name="priceDetail[<%=index%>][point]" id="price-[<%=index%>]" value='<%=pointValue%>'/>
            </td>
            <td style="" class='' attr='price'><%=priceValue %>
                <input type="hidden" name="priceDetail[<%=index%>][price]" id="price-[<%=index%>]" value='<%=priceValue%>'/>
            </td>
            <td style=""> <a href="javascript:void(0)"
              data-index="<%= index %>"
                    class = "remove_price"
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
                    <p>All fields are required</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>

    <div id="exist-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>These combination already added</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


<div id="colored-remove" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Sorry!</h4>
                    <p>You can't able to delete this pricing.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>

@stop

