@extends('layout.header')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-slider.css')}}">
    <style>

        .wizard-link {
            width: 16.666666667%;
            /*width: 25%;*/
        }

        .labelheading {
            font-weight: 600;
        }

        .panel-body {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 2px;
            padding: 20px;
            position: relative;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-top: 1px solid #eee;
        }

        .panel-body h5, .panel-body h4 {
            font-weight: 600;
            font-size: 19px;
            /* background-color: #62cd31; */
            /* color: #62cb31; */
            color: #f58634;
        }

        .cancel {
            padding: 9px;
            width: 8%;
        }

        .customerdesign {
            margin-top: 20px;
        }

        .customerSpan {
            display: inline-block;
            min-width: 149px;
            font-weight: bold;

        }

        .customerTextbox {
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
            width: 50%;
        }

        .wizardDesign {
            position: relative;
            bottom: 33px;
            width: 99%;
            margin-left: 10px;
        }
    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Customer Setup</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Management</a></li>
                <li class="active">Customer Setup</li>
            </ol>
        </div>


        <div class="panel-body">
            <!--                    <p>
                                    The jQuery Validation Plugin provides drop-in validation for your existing forms, while making all kinds of customizations to fit your application really easy.
                                </p>-->

            <form id="userForm" method="post" data-parsley-validate action="{{url('admin/addCustomerSetup')}}">


                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="customerName" id="customerName"
                       value="{{$customerId}}">
                <div class="col-lg-12" style="">

                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">

                        <h5 class="heading" style="margin-bottom: 22px;">Mode Of Payment</h5>

                        <div class="col-lg-4">


                            @if($selectPayMethods)
                                @foreach($selectPayMethods as $paykey=>$payval)
                                    <?php
                                        if($customer_setups)
                                            {
                                                if($customer_setups['payMethod']==$payval->id)
                                                {
                                                    $checked = 'checked';
                                                }
                                                else
                                                {
                                                    $checked = '';
                                                }
                                            }
                                            else
                                                {
                                                    $checked = '';
                                                }

                                    ?>

                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="radio" name="payMethod"
                                                   class="{{$payval->name}}"
                                                   id="pay-{{$payval->id}}"
                                                   value="{{$payval->id}}" {{$checked}}>
                                            <label for="pay-{{$payval->id}}">{{$payval->name}}</label>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        </div>


                        <div class="form-group" style="margin-top: 134px;">
                            <label style="font-size: 15px">Payment Terms:</label>
                            {!!Form::textarea('paymentTerms',isset($customer_setups['payTerms'])?$customer_setups['payTerms']:'', array('class'=>'form-control','id'=>'paymentTerms','cols'=>30,'rows'=>'5')) !!}

                        </div>


                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Cal Specifications</h5>


                        @if($calSpecification)
                            @foreach($calSpecification as $speckey=>$specval)
                                <?php
                                    if($customer_setups)
                                        {
                                            if($customer_setups['calSpecification']==$specval->id)
                                            {
                                                $checked = 'checked';
                                            }
                                            else
                                            {
                                                $checked = '';
                                            }
                                        }
                                        else
                                            {
                                                $checked = '';
                                            }

                                ?>
                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="calSpecification"
                                               class="{{$specval->cal_specification}} chooseSpec"
                                               id="{{$specval->id}}"
                                               value="{{$specval->id}}" {{$checked}}>
                                        <label for="{{$specval->id}}">{{$specval->cal_specification}}</label>
                                    </div>

                                </div>


                            @endforeach

                        @endif

                        <div class="form-group speccomment" style="display:none;">

                            <label class="labelheading">Specification Comments</label>
                            {!!Form::textarea('specComments',isset($customer_setups['specComments'])?$customer_setups['specComments']:'', array( 'placeholder' => 'Enter the Comment','class'=>'form-control','id'=>'specComments','rows'=>'5')) !!}
                        </div>


                        {{--<div class="form-group" style="margin-top: 134px;" >--}}
                        {{--<label style="font-size: 15px">Comments:</label>--}}

                        {{--</div>--}}

                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>


                        <div>
                            <b>
                                (By default, pipettes are calibrated to the manufacturer's specifications)</b>

                        </div>


                    </div>
                </div>

                <hr/>


                <div class="col-lg-12" style="margin-bottom: 30px;">


                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">
                        <h5 style="margin-bottom: 22px;">Cal Frequency</h5>

                        @if($calFrequency)
                            @foreach($calFrequency as $freqkey=>$freqval)
                                <?php
                                    if($customer_setups)
                                        {
                                            if($customer_setups['calFrequency']==$freqval->id)
                                            {
                                                $checked = 'checked';
                                            }
                                            else
                                            {
                                                $checked = '';
                                            }
                                        }
                                        else
                                            {
                                                $checked = '';
                                            }

                                ?>

                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="calFrequency"
                                               class="{{$freqval->name}}"
                                               id="freq-{{$freqval->id}}"
                                               value="{{$freqval->id}}" {{$checked}} >
                                        <label for="freq-{{$freqval->id}}">{{$freqval->name}}</label>
                                    </div>
                                </div>




                            @endforeach

                        @endif
                        <?php
                        if($customer_setups)
                        {
                            if($customer_setups['calFrequency'])
                            {
                                $exact_date = '';
                            }
                            else
                            {
                                $exact_date = isset($customer_setups['excatDate']) ? date('m-d-Y',strtotime(str_replace('/','-',$customer_setups['excatDate']))) : '';
                            }
                        }
                        else
                            {
                                $exact_date = '';
                            }


                        ?>

                        {{--<div class="form-group" style="margin-top:106px;">--}}
                            {{--<label style="font-size: 15px">Exact Date:</label>--}}
                            {{--{!!Form::text('exactDate',$exact_date, array('class'=>'form-control datepicker','id'=>'exactDate')) !!}--}}

                        {{--</div>--}}

                        <span>
                                    {{--<b>(By default, end of the month is printed as the next due date on the certificate.If you require exact date, please specify)</b>--}}
                                    <b>(By default, end of the month is printed as the next due date on the certificate.)</b>
                                </span>

                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Labeling</h5>

                        @if($selectLabeling)
                            @foreach($selectLabeling as $labelkey=>$labelval)
                                <?php
                                    if(isset($customer_setups['assetLabel']) && $customer_setups['assetLabel'])
                                        {

                                if(in_array($labelval->id,$customer_setups['assetLabel']))
                                    {
                                        $checked = 'checked';
                                    }
                                    else
                                        {
                                            $checked = '';
                                        }
                                        }
                                        else
                                            {
                                                $checked='';
                                            }
                                ?>

                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="checkbox" name="labeling[]"
                                               class="{{$labelval->name}}"
                                               id="label-{{$labelval->id}}"
                                               value="{{$labelval->id}}" {{$checked}}>
                                        <label for="label-{{$labelval->id}}">{{$labelval->name}}</label>
                                    </div>
                                </div>

                            @endforeach
                        @endif


                    </div>


                </div>
                <hr/>


                <div class="col-lg-12" style="">

                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">

                        <h5 class="heading" style="margin-bottom: 22px;">Shipping</h5>

                        @if($selectShipping)
                            @foreach($selectShipping as $shipkey=>$shipval)
                                <?php
                                if((isset($customer_setups['shipping']) && $customer_setups['shipping']==$shipval->id))
                                {
                                    $checked = 'checked';
                                }
                                else
                                {
                                    $checked = '';
                                }
                                ?>
                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="shipValue"
                                               class="{{$shipval->name}}"
                                               id="ship-{{$shipval->id}}"
                                               value="{{$shipval->id}}" {{$checked}} >
                                        <label for="ship-{{$shipval->id}}">{{$shipval->name}}</label>
                                    </div>
                                </div>

                            @endforeach
                        @endif

                        <div class="form-group" style="margin-bottom: 30px;margin-top: 109px;">
                            <label style="font-size: 15px">Comments:</label>
                            {!!Form::textarea('shippingComment',isset($customer_setups['shippingComments'])?$customer_setups['shippingComments']:"", array( 'placeholder' => 'Enter the Comment','class'=>'form-control','id'=>'shippingComment','rows'=>'5')) !!}

                        </div>


                    </div>
                    <div class="col-lg-6">

                        <h5 class="heading" style="margin-bottom: 22px;">Service Plan</h5>

                        <div class="col-lg-3">


                            @if($selectServicePlan)
                                @foreach($selectServicePlan as $serplankey=>$serplanval)
                                    <?php
                                        if((isset($customer_setups['plans'])&&$customer_setups['plans']))
                                            {

                                    if(in_array($serplanval->id,$customer_setups['plans']))
                                    {
                                        $checked = 'checked';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }

                                    }
                                    else
                                        {
                                            $checked = '';
                                        }
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="checkbox"
                                                   data-parsley-multiple="groups"
                                                   name="servicePlan[]"
                                                   data-parsley-errors-container="#error-container1"
                                                   class="{{$serplanval->service_plan_name}}"
                                                   id="serviceplan-{{$serplanval->id}}"
                                                   value="{{$serplanval->id}}" {{$checked}}>
                                            <label for="serviceplan-{{$serplanval->id}}">{{$serplanval->service_plan_name}}</label>
                                        </div>
                                    </div>


                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>

                <hr/>


                <br/>
                <div style="margin-left: 44%;">
                    <button class="btn btn-sm btn-primary m-t-n-xs"
                            id="customerSetupSubmission"
                            style="background-color: #f58634;border-color:#f58634;"><strong>Submit</strong></button>
                </div>

            </form>
        </div>



    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div style="display:none;">
        <form action="{{url("admin/customerlists")}}" method="post" id="formSubmission">
            <input type="text" value="1" name="postvalue">
            <input type="text" value="{!! $input['id'] !!}" name="customerSetUpId">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="submit" id="submitForm">
        </form>
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>

    <script src="{{asset('js/underscore/underscore.js')}}"></script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });

    </script>

    {{--<script>--}}

        {{--$('body').on('click', '#customerSetupSubmission', function () {--}}
            {{--var data = $(' #userForm').serialize();--}}
            {{--var payMethod = $("input[name='payMethod']:checked"). val();--}}
            {{--console.log(payMethod);--}}
            {{--console.log(data);--}}
            {{--$.ajax({--}}
                {{--headers: {--}}
                    {{--'X-CSRF-Token': "{{ csrf_token() }}"--}}
                {{--},--}}
                {{--type: "POST",--}}
                {{--data: data,--}}
                {{--url: "{{url("admin/addCustomerSetup")}}",--}}
                {{--dataType: "JSON",--}}
                {{--success: function (json) {--}}
                    {{--if (json.result) {--}}
                        {{--$('#submitForm').trigger('click');--}}
                        {{--//window.location = "{{url("admin/customerSetup")}}";--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}

    {{--</script>--}}


    <script type="text/javascript">

        $('body').on('click', '.chooseSpec', function () {
            var value = $(this).attr('id');

            if (value == 3) {
                $('.speccomment').show();
            } else {
                $('.speccomment').hide();
            }
        });
    </script>





    <script type="text/html" id="tolelarance">

        <tr id="<%= Id %>" class="talerance-list index">


        <td><%=description %>
            <input type="hidden" name="orderlimits[<%=Id%>][description]" id="foods-<%= Id %>" value='<%=description%>'/>

        </td>

        <td><%= target_value %>
            <input type="hidden" name="orderlimits[<%=Id%>][target_value]" id="foods-<%= Id %>" value='<%=target_value%>'/>
        </td>
        <td><%= accuracy %>
            <input type="hidden" name="orderlimits[<%=Id%>][accuracy]" id="foods-<%= Id %>" value='<%=accuracy%>'/>
        </td>


        <td> <%= precision %>
            <input type="hidden" name="orderlimits[<%=Id%>][precision]" id="foods-<%= Id %>" value='<%=precision%>'/>
        </td>
    </tr>


</script>
@stop

