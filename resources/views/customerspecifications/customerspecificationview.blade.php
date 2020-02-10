<div class="model-body mainbody">
    <div class="panel-heading">
        <h3 style="font-weight:600;">Cal Specification</h3>
    </div>

    {!! Form::open(['url'=>'admin/customerspecification']) !!}

    <div class="panel-body">
        <input type="hidden" name="CustomerId" id="CustomerId" value="{{$input['customer_id']}}">

        <input type="hidden" name="id" value="{{$input['id']}}" id="id">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="m-t-18">
                    <div class="form-group">

                        <label>Channel Type</label>
                        {{Form::select('channel_id',$channels,$input['channel_id'],array('class'=>'form-control','id'=>'channel'))}}

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
                {!!Form::text('volume_from',$input['volume_value_from'], array('data-parsley-type'=>"number",'class'=>'form-control volume_from','required'=>"",'id'=>'volumeFrom')) !!}

            </section>
            @if((($input['id'] && $input['volume_value_to'])))


                <section class="col-md-4" id='valueto'>

                    <label>Volume To</label>
                    {!!Form::text('volume_to',$input['volume_value_to'], array('data-parsley-type'=>"number",'class'=>'form-control volume_to fromvalue','id'=>'volumeToRange')) !!}

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

                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracy]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->accuracy)?$input['tolerances'][$tpkey]->accuracy:'', array('class'=>'form-control accuracy_numeric','id'=>'accuracy')) !!}

                                </section>

                                <section class="col-md-2">
                                    <label>

                                        <h5 class="heading">Precision(%)</h5></label>

                                    {!!Form::text('toleranceArray['.$tpkey.'][precision]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->precision)?$input['tolerances'][$tpkey]->precision:'', array('class'=>'form-control precision_numeric','id'=>'precision')) !!}

                                </section>

                                <section class="col-md-2">
                                    <label>

                                        <h5 class="heading">Accuracy(μl)</h5></label>

                                    {!!Form::text('toleranceArray['.$tpkey.'][accuracyul]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->accuracy_ul)?$input['tolerances'][$tpkey]->accuracy_ul:'', array('class'=>'form-control accuracy_ul_numeric','id'=>'accuracyul')) !!}

                                </section>

                                <section class="col-md-2">
                                    <label>

                                        <h5 class="heading">Precision(μl)</h5></label>

                                    {!!Form::text('toleranceArray['.$tpkey.'][precisionul]',(isset($input['tolerances'][$tpkey])&&$input['tolerances'][$tpkey]->precesion_ul)?$input['tolerances'][$tpkey]->precesion_ul:'', array('class'=>'form-control precesion_ul_numeric ','id'=>'precisionul')) !!}
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
                    <a href="javascript:void(0)" class="btn btn-space btn-primary" id="submitcalspec">Submit</a>
                    {{--<button type="submit" class="btn btn-space btn-primary">Submit</button>--}}
                </div>

            </div>

        </div>

    </div>

</div>


<style type="text/css">

    .service-details-modal p.mod-service-img {
        position: absolute;
        padding: 0;
    }

    .service-details-modal h5 {
        /*padding-left: 100px;*/
        font-size: 18px;
        margin-bottom: 5px;
    }

    .service-details-modal p {
        /*padding-left: 100px;*/
        margin-bottom: 10px;
    }

</style>

<script>

    $('#volumeFrom,#volumeToRange').bind('keyup paste', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $('body').on('click', '#submitcalspec', function (event) {
        event.preventDefault();
        var customerID = $('#CustomerId').val();
        var id = $('#id').val();
        var channel = $('#channel').val();
        var operation = $('#operation').val();
        var volume = $('#volume').val();
        var volumeFrom = $('#volumeFrom').val() ? $('#volumeFrom').val() : '';
        var volumeTo = $('#volumeToRange').val() ? $('#volumeToRange').val() : '';
        var volumeRange = '';
        if (volumeFrom && volumeTo) {
            volumeRange = volumeFrom + '-' + volumeTo;
        }
        if (volume == 2) {
            var volumeFrom = $('#volumeFrom').val();
        } else {
            var volumeFrom = '';
        }
        if (channel == '' || operation == '' || volume == '') {
            $.toast({
                heading: 'Warning',
                text: "Before choose unit,Channel,Operation,Volume should be selected",
                //position: 'top-left',
                showHideTransition: 'slide',
                icon: 'error',

                loader: false
            });
        } else {
            $.ajax({
                type: 'post',
                url: "{{url("admin/checkcustomertolerancecombination")}}",
                data: {
                    customerID: customerID,
                    channel: channel,
                    id: id,
                    operation: operation,
                    volume: volume,
                    volumeFrom: volumeFrom,
                    volumeRange: volumeRange,
                    "_token": "{!! csrf_token() !!}"
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.result) {
                        console.log(data.result);
                        $.toast({
                            heading: 'Warning',
                            text: data.message,
                            position: 'top-left',
                            showHideTransition: 'slide',
                            icon: 'error',

                            loader: false
                        });
                    } else {
                        {{--                        window.location = "{{url("admin/customerspecification")}}";--}}


                        $("form").submit();
                    }
                }

            });
        }

    });
    //Decimal Number Validation

        $("#accuracy,.precision_numeric,.precesion_ul_numeric,.accuracy_ul_numeric").keydown(function (event) {
            if (event.shiftKey == true) {
                event.preventDefault();
            }
            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
            } else {
                event.preventDefault();
                $.toast({
                    heading: 'Warning',
                    text: 'Accept only Numbers and Decimal Values',
                    //position: 'top-left',
                    showHideTransition: 'slide',
                    icon: 'error',

                    loader: false
                });
            }
            if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();
        });


</script>