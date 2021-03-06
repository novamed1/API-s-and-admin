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

        .fullWidth {
            width: 100%;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Service Plan Creation</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Master Setup</a></li>
                <li class="active">Service Plan Creation</li>
            </ol>
        </div>
        <div class="main-content">
            @if($input['id'])
                {!! Form::model($input, array('url' => 'admin/editServicePlan/'. $input['id'])) !!}

            @else

                {!! Form::open(['url'=>'admin/serviceplan']) !!}
            @endif
            <input type="hidden" name="id" value="{{$input['id']}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 style="font-weight:600;">Service Plan Creation</h3>
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">

                                            <label>Plan Name</label>
                                            {!!Form::text('name',$input['name'], array( 'class'=>'form-control','id'=>'name','required'=>"",'placeholder'=>'Please Enter Plan Name')) !!}

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label>Product Type</label>

                                            {{Form::select('producttype',$producttype,$input['producttype'],array('class'=>'form-control'))}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{--<div class="row">--}}

                            {{----}}

                            {{--</div>--}}

                            <div class="row">



                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label>Customer Type</label>

                                            {{Form::select('servicePlanType',$servicePlanType,$input['servicePlanType'],array('class'=>'form-control'))}}
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-sm-4 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label fullWidth">Certificate</label>
                                            <div class="col-sm-6">

                                                @if($input['issue_certificate'] == '1')

                                                    @php($issuechk = 'checked=checked')

                                                @else
                                                    @php($issuechk = '0')

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" checked="checked" name="issueCertificate"
                                                           id="issueYes" value="1">
                                                    <label for="issueYes" {{$issuechk}}>Yes</label>
                                                </div>

                                                @if($input['issue_certificate'] == '2')

                                                    @php($issuechkfalse = 'checked=checked')
                                                    {{--@php($value='1')--}}

                                                @else
                                                    @php($issuechkfalse = '0')
                                                    {{--@php($value='0')--}}

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" name="issueCertificate" id="issueNo"
                                                           value="2" {{$issuechkfalse}}>
                                                    <label for="issueNo">No</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label fullWidth">As Found</label>
                                            <div class="col-sm-6">
                                                @if($input['as_found'] == '1')

                                                    @php($foundchk = 'checked=checked')
                                                    {{--@php($value='1')--}}

                                                @else
                                                    @php($foundchk = '0')
                                                    {{--@php($value='0')--}}

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" checked="checked" class="asFoundyes"
                                                           name="asFound" id="foundyes" value="1" {{$foundchk}}>
                                                    <label for="foundyes">Yes</label>
                                                </div>
                                                @if($input['as_found'] == '2')

                                                    @php($foundchkfalse = 'checked=checked')

                                                @else
                                                    @php($foundchkfalse = '0')

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" name="asFound" class="asFoundNo"
                                                           id="foundno"
                                                           value="2" {{$foundchkfalse}}>
                                                    <label for="foundno">No</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-sm-4 col-xs-12">
                                    <div class="m-t-10">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label fullWidth">As Calibrated</label>
                                            <div class="col-sm-6">
                                                @if($input['as_calibrate'] == '1')

                                                    @php($calibratechk = 'checked=checked')
                                                    {{--@php($value='1')--}}

                                                @else
                                                    @php($calibratechk = '0')
                                                    {{--@php($value='0')--}}

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" checked="" name="asCalibrated"
                                                           class="asCalibratedYes"
                                                           id="calibratedyes" value="1" {{$calibratechk}}>
                                                    <label for="calibratedyes">Yes</label>
                                                </div>
                                                @if($input['as_calibrate'] == '2')

                                                    @php($calibratechkfalse= 'checked=checked')
                                                    {{--@php($value='1')--}}

                                                @else
                                                    @php($calibratechkfalse = '0')
                                                    {{--@php($value='0')--}}

                                                @endif
                                                <div class="am-radio inline">
                                                    <input type="radio" name="asCalibrated" class="asCalibratedNo"
                                                           id="calibratedno"
                                                           value="2" {{$calibratechkfalse}}>
                                                    <label for="calibratedno">No</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">


                            <div class="col-sm-6 col-xs-12 disableFound" disabled="disabled"
                                 style="{{(($input['as_found'] =="1" && $input['id'] !='') || $input['id'] == '')? '':'display:none'}}">

                                <div class="cart-type-2">
                                    <legend>As Found Requirements</legend>
                                    <div class="form-group">
                                        <label>Test Points </label>

                                        @if($selectTestPoints)
                                            @foreach($selectTestPoints as $testkey)
                                                @if (in_array($testkey->id, $asFoundDetails))

                                                    @php($chk = 'checked=checked')

                                                @else
                                                    @php($chk = '0')

                                                @endif

                                                <div class="am-radio inline" style="margin-left: 32px;">
                                                    <input type="checkbox"
                                                           name="asFoundTestPoint[{{$testkey->name}}]"
                                                           id="{{$testkey->id}}"
                                                           value="{{$testkey->id}}"/{{$chk}}>
                                                    <label for="{{$testkey->id}}">{{$testkey->name}}</label>
                                                </div>
                                            @endforeach
                                        @endif


                                    </div>
                                    <div class="form-group">
                                        <label>No of Readings</label>

                                        {!!Form::select("foundReading",$reading,$input['foundReading'],array('class'=>'form-control','id'=>'freading'))!!}

                                    </div>

                                </div>

                            </div>


                            {{--</div>--}}
                            {{----}}


                            <div class="col-sm-6 col-xs-12 disableCalibrated"
                                 style="{{(($input['as_calibrate'] =="1" && $input['id'] !='') || $input['id'] == '')? '':'display:none'}}">
                                <div class="cart-type-2">
                                    <legend>As Calibrated Requirements</legend>
                                    <div class="form-group">
                                        <label>Test Points </label>


                                        @if($selectTestPoints)
                                            @foreach($selectTestPoints as $testkey)

                                                @if (in_array($testkey->id, $asCalibrateDetails))

                                                    @php($chk1 = 'checked=checked')

                                                @else
                                                    @php($chk1 = '0')

                                                @endif

                                                <div class="am-radio inline" style="margin-left: 32px;">
                                                    <input type="checkbox"
                                                           name="asCalibratedTestPoints[{{$testkey->name}}]"
                                                           id="{{$testkey->name}}"
                                                           value="{{$testkey->id}}"/{{$chk1}}>
                                                    <label for="{{$testkey->name}}">{{$testkey->name}}</label>
                                                </div>
                                            @endforeach
                                        @endif


                                        {{--</div>--}}
                                    </div>
                                    <div class="form-group">
                                        <label>No of Readings</label>


                                        {!!Form::select("foundReading1",$reading,$input['foundReading1'],array('class'=>'form-control','id'=>'freading1'))!!}


                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>


                </div>


                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="">
                            <legend>Pricing Criteria</legend>
                            <div class="row">
                                <div class="campaign-type-frm">

                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Volume Type</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::select('volume',$volume,'', array('class'=>'form-control','id'=>'volumePrice')) !!}

                                        </div>


                                    </section>
                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Operation</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::select('operation',$operationSelect,'', array('class'=>'form-control','id'=>'operationPrice')) !!}

                                        </div>


                                    </section>
                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Channel Type</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::select('channels',$channels,'', array('class'=>'form-control','id'=>'channelPrice')) !!}

                                        </div>


                                    </section>

                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Channel Numbers</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::select('channel_numbers',$channelNumbers,'', array('class'=>'form-control','id'=>'channelnumber')) !!}

                                        </div>


                                    </section>
                                    <section class="col-md-2">
                                        <label>

                                            <h5 class="heading">Test Channels</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::select('points',$channelCrops,'', array('class'=>'form-control','id'=>'pointsPrice')) !!}

                                        </div>


                                    </section>

                                    <section class="col-md-1">
                                        <label>

                                            <h5 class="heading">Price</h5></label>

                                        <div class="styled-select-lab gender">

                                            {!!Form::text('Price','', array( 'placeholder' => 'Price','class'=>'form-control pricePrice','id'=>'pricePrice')) !!}

                                        </div>
                                        <span class="campaign-divmsg"
                                              id="campaignamounterror"></span>


                                    </section>

                                    <section class="col-md-1" style="    top: 32px;;float:right;">


                                        <a href="javascript:void(0)"
                                           class="btn btn-space btn-primary"
                                           id="addPrice"><i class=''
                                                            aria-hidden="true">+</i></a>


                                    </section>


                                </div>

                            </div>

                            <div class="panel panel-default" id='priceBody'>
                                <div class="panel-body">
                                    <div class="widget-body">
                                        <div class="cart-type-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="widget">
                                                        <div>
                                                            <table class="table table-fw-widget">
                                                                @if($input['id'])
                                                                    <thead>
                                                                    <tr id='toleranceBody'>
                                                                        <th class="">Volume Type</th>
                                                                        <th>Operation</th>
                                                                        <th>Channel Type</th>
                                                                        <th>Channel Numbers</th>
                                                                        <th>Test Channels</th>
                                                                        <th>Price</th>
                                                                        <th></th>
                                                                    </tr>
                                                                    </thead>
                                                                @else
                                                                    <thead>
                                                                    <tr id='toleranceBody'>
                                                                        <th class="">Volume Type</th>
                                                                        <th>Operation</th>
                                                                        <th>Channel Type</th>
                                                                        <th>Channel Numbers</th>
                                                                        <th>Test Channels</th>
                                                                        <th>Price</th>
                                                                        <th></th>
                                                                    </tr>
                                                                    </thead>

                                                                @endif
                                                                <tbody id="priceAppend">
                                                                @if($priceDetail)

                                                                    @php($j=$totalPriceDetails + 1)
                                                                    @php($i = 1)
                                                                    @foreach($priceDetail as $pricekey=> $pricerow)

                                                                        <tr id="price-{{$i}}"
                                                                            class="spares-list div-lits testexist"

                                                                            volume-attr="{{$pricerow['volume']}}"
                                                                            operation-attr="{{$pricerow['operation']}}"
                                                                            channel-attr="{{$pricerow['channel']}}"
                                                                            channelnumber-attr="{{$pricerow['channelnumber']}}"
                                                                            point-attr="{{$pricerow['point']}}"
                                                                        >

                                                                            <td> {{$pricerow['volume']}}</td>
                                                                            <td> {{$pricerow['operation']}}</td>
                                                                            <td> {{$pricerow['channel']}}</td>
                                                                            <td> {{$pricerow['channelnumber']}}</td>
                                                                            <td> {{$pricerow['point']}}</td>
                                                                            <td> {{$pricerow['price']}}</td>
                                                                            {{--<td> {{$pricerow['precesion_ul']}}</td>--}}
                                                                            <td><a href="javascript:void(0)"
                                                                                   class="removeeditprice"
                                                                                   data-id="{{$pricerow['Id']}}"
                                                                                   data-index="{{$i}}"


                                                                                ><i class="s7-close td-cross"
                                                                                    aria-hidden="true"></i></a>
                                                                            </td>
                                                                            {!!Form::hidden("priceDetail[".$i."][Id]",$pricerow['Id'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][volume]",$pricerow['volumeId'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][operation]",$pricerow['operationId'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][channel]",$pricerow['channelId'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][channelNumber]",$pricerow['channelNumberId'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][point]",$pricerow['pointId'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {!!Form::hidden("priceDetail[".$i."][price]",$pricerow['price'],array('class'=>'form-control','id'=>'price'.'-'.$i)) !!}
                                                                            {{--{!!Form::hidden("priceDetail[".$pricekey."][precision_ul]",$pricerow['precesion_ul'],array('class'=>'form-control','id'=>'price'.'-'.$pricekey)) !!}--}}

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


                    </div>


                    <div class="panel panel-default" style='text-align: center;'>

                        <div class="panel-body">

                            <div class="text-center">
                                <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                <a href="{{url('admin/devicemodellist')}}" class="btn btn-space btn-default">Cancel</a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            </form>
        </div>

    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>

    <div>
        <button data-modal="colored-remove" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-remove deletePopUp">Sorry!
        </button>
    </div>

    <div>
        <button data-modal="exist-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUpExist">Warning
        </button>
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
        $('body').on('change', '#channelPrice', function () {
            var checkchannels = $('#channelPrice').val();
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
                        jQuery("#channelnumber").html(json.getChannels);

                    }
                }
            });
        });

    </script>


    <script>
        $('body').on('change', '#channelnumber', function () {
            var checkchannelnumber = $('#channelnumber').val();
            $.ajax({


                type: "get",
                url: "{{url("admin/getchannelpoint")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    channel_number: checkchannelnumber,
                },
                dataType: "JSON",
                success: function (json) {


                    if (json.result == true) {
                        jQuery("#pointsPrice").html(json.getChannels);

                    }
                }
            });
        });

    </script>

    <script type="text/javascript">
        $("#select2").select2();
    </script>



    <script>
        $('body').on('click', '.asFoundNo', function () {

            $('.disableFound').hide();
        });
        $('body').on('click', '.asFoundyes', function () {

            $('.disableFound').show();
        });
        $('body').on('click', '.asCalibratedNo', function () {

            $('.disableCalibrated').hide();
        });
        $('body').on('click', '.asCalibratedYes', function () {

            $('.disableCalibrated').show();
        });
    </script>

    {{--<script>--}}

    {{--function remove_price(index) {--}}
    {{--alert(index)--}}

    {{--$('#' + index).remove();--}}
    {{--}--}}
    {{--</script>--}}

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
    </script>


    <script>
        $(".pricePrice").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });


        var index = {!! $j !!};

        $('body').on('click', '#addPrice', function () {
//     console.log('hai')
            var temp = index;
//            console.log(temp);
            index = parseInt(index) + 1;

            if ($("#volumePrice").val() && $("#operationPrice").val() && $("#channelPrice").val() && $("#channelnumber").val() && $("#pointsPrice").val() && $('#pricePrice').val()) {

                var volumeText = $("#volumePrice option:selected").text();
                var volumeValue = $("#volumePrice").val();

                var operationText = $("#operationPrice option:selected").text();
                var operationValue = $("#operationPrice").val();

                var channelText = $("#channelPrice option:selected").text();
                var channelValue = $("#channelPrice").val();

                var pointText = $("#pointsPrice option:selected").text();
                var pointValue = $("#pointsPrice").val();
                var priceValue = $("#pricePrice").val();

                var channelNumberText = $("#channelnumber option:selected").text();
                var channelNumberValue = $("#channelnumber").val();
                var check = 0;

                $( ".testexist" ).each(function( index ) {
                    var volume = $(this).attr('volume-attr');
                    var operation = $(this).attr('operation-attr');
                    var channel = $(this).attr('channel-attr');
                    var channelnumber = $(this).attr('channelnumber-attr');
                    if(volume==volumeText && operation==operationText && channel==channelText && channelnumber==channelNumberText)
                    {
                        check=1;
                        $('.popUpExist').trigger('click');

                    }
                });
                if(check==1)
                {
                    return false;
                }


                var pricedetail = jQuery("#priceunderscore").html();
                Id = $(".spares-list").length;
                Id++;


                $('#priceAppend').append(_.template(pricedetail, {

                    volumeText: volumeText,
                    volumeValue: volumeValue,
                    index: temp,
                    operationText: operationText,
                    operationValue: operationValue,
                    channelText: channelText,
                    channelValue: channelValue,
                    pointText: pointText,
                    pointValue: pointValue,
                    priceValue: priceValue,
                    channelNumberText: channelNumberText,
                    channelNumberValue: channelNumberValue
                }));



                $('#priceBody').show();

                $('#volumePrice').val('');
                $('#operationPrice').val('');
                $('#channelPrice').val('');
                $('#channelnumber').val('0');
                $('#pointsPrice').val('');
                $('#pricePrice').val('');
            } else {

                $('.popUp').trigger('click');
                return false;
                // $('#toleranceBody').hide();
            }

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

