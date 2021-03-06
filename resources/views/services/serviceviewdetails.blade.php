@extends('layout.header')
@section('content')

    <link rel="stylesheet" href="{{asset('css/service-req.css')}}" type="text/css">
    <style>
        .notportalaccess {
            display: none;
        }

        /*.addtoportal{*/
        /*display: block;*/
        /*}*/
        .right {
            float: right;
        }

        .rightside {
            right: 20px;
        }

        .model-heading {
            font-weight: 600;
            text-align: center;
        }

        .serviceListdropdown {
            width: 100%;
            margin-left: -5px;
        }

        .viewDetail > td {
            width: 10%;
        }

        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        table {
            font-family: arial, sans-serif;
            /*border-collapse: collapse;*/
            width: 75%;
            align-content: center;
        }

        td, th {
            /*border: 1px solid #dddddd;*/
            text-align: right;
            padding: 8px;
        }

    </style>

    <style>
        .right {
            float: right !important;
        }

        .customerpopup {
            width: 56% !important;
        }

        .cuustomercontent {

            max-width: 100% !important;
        }

        .modal-header {
            padding: 10px !important;
        }

        .modal-body {
            padding: 1px;
        }

        .inline-edit {
            font-size: 12px;
            height: 35px;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Customer Service Details (Service Number :<span></span> {{$getServiceDetails->service_number}})</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services & Workorders</a></li>
                <li><a href="#">Customer Service Orders</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="{{url('admin/buyservice')}}" class="btn btn-space btn-primary">Go Back</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                <div class="panel panel-default keywordsearchpanel">

                </div>
            </div>

            <form role="form" id="servicesForm" data-parsley-validate>
                <div class="row">
                    {{--<div class="panel panel-default">--}}

                    {{--<div class="panel-body">--}}
                    <div class="col-sm-12">
                        <div class="widget widget-fullwidth widget-small">

                            <div class="flash-message">
                                @include('notification/notification')
                            </div>


                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    @if($customerinfo)
                                        <div class="">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details
                                                        <span class="right" id="editCustomerProperty"><a
                                                                    href="javascript:void(0)" id="CustomerPropertyEdit"
                                                                    data-id="{{$customerinfo['getCustomer']->id}}"
                                                                    data-attr="customer" class="cusomerDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinCustomerProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName">{{$customerinfo['getCustomer']->customer_name}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1">{{$customerinfo['getCustomer']->address1}}</span>
                                                            <br><span
                                                                    id="changedCustomerCity">{{$customerinfo['getCustomer']->city}}</span>
                                                            <br><span
                                                                    id="changedCustomerState">{{$customerinfo['getCustomer']->state}}</span>
                                                            <br><span
                                                                    id="changedCustomerzipcode">{{$customerinfo['getCustomer']->zip_code}}</span><br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel">{{$customerinfo['getCustomer']->customer_telephone}}</span>

                                                        </address>
                                                        <address><strong>Email Id
                                                                @if($customerinfo['access'] == 0)
                                                                    <img src="{{asset('img/verify2.png')}}"
                                                                         title="Email Verified">
                                                                @else
                                                                    <img src="{{asset('img/failure.png')}}"
                                                                         title="Email Not Verified">
                                                                @endif
                                                                <br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail">{{$customerinfo['getCustomer']->customer_email}}

                                                                </span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details
                                                        <span class="right" id="editBillingProperty"><a
                                                                    href="javascript:void(0)" id="billingPropertyEdit"
                                                                    curr-id="{{$customerinfo['getCustomerBilling']->id}}"
                                                                    customer-id="{{$customerinfo['getCustomerBilling']->customer_id}}"
                                                                    data-attr="billing" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinBillingProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="billing_name">{{$customerinfo['getCustomerBilling']->billing_contact}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="billing_address1">{{$customerinfo['getCustomerBilling']->address1}}</span>
                                                            <br><span
                                                                    id="billing_city">{{$customerinfo['getCustomerBilling']->city}}</span>
                                                            <br><span
                                                                    id="billing_state">{{$customerinfo['getCustomerBilling']->state}}</span>
                                                            <br><span
                                                                    id="billing_zipcode">{{$customerinfo['getCustomerBilling']->zip_code}}</span>
                                                            <br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="billing_phone">{{$customerinfo['getCustomerBilling']->phone}}</span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="billing_email">{{$customerinfo['getCustomerBilling']->email}}</span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details<span class="right"
                                                                                                     id="editShippingProperty"><a
                                                                    href="javascript:void(0)" id="shippingPropertyEdit"
                                                                    curr-id="{{$customerinfo['getCustomerShipping']->id}}"
                                                                    customer-id="{{$customerinfo['getCustomerShipping']->customer_id}}"
                                                                    data-attr="shipping" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinShippingProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="shipping_name">{{$customerinfo['getCustomerShipping']->customer_name}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="shipping_address1">{{$customerinfo['getCustomerShipping']->address1}}</span>
                                                            <br><span
                                                                    id="shipping_city">{{$customerinfo['getCustomerShipping']->city}}</span>
                                                            <br><span
                                                                    id="shipping_state">{{$customerinfo['getCustomerShipping']->state}}</span>
                                                            <br><span
                                                                    id="shipping_zipcode">{{$customerinfo['getCustomerShipping']->zip_code}}</span>
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><span
                                                                    id="shipping_phone">{{$customerinfo['getCustomerShipping']->phone_num}}</span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="shipping_email">{{$customerinfo['getCustomerShipping']->email}}</span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Payment Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong>{{$customerinfo['getCustomer']->customer_name}}
                                                                <br>
                                                            </strong>{{$customerinfo['getCustomerShipping']->address1}}
                                                            <br>{{$customerinfo['getCustomerShipping']->city}}
                                                            <br>{{$customerinfo['getCustomerShipping']->state}}
                                                            <br>{{$customerinfo['getCustomerShipping']->zip_code}}
                                                            <br>{{(isset($customerinfo['payment']->pay_terms)&&$customerinfo['payment']->pay_terms)?$customerinfo['payment']->pay_terms:''}}
                                                            @if(isset($customerinfo['payment']->pay_method)&&$customerinfo['payment']->pay_method)
                                                                <?php if($customerinfo['payment']->pay_method==1)
                                                                    {
                                                                        $pay_method = 'Credit card';
                                                                    }
                                                                    elseif($customerinfo['payment']->pay_method==2)
                                                                    {
                                                                        $pay_method = 'Wire/EFT';
                                                                    }
                                                                    else

                                                                        {
                                                                            $pay_method = 'PO#';
                                                                        }

                                                                    ?>
                                                                    <br>{{$pay_method}}
                                                            @endif

                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr>{{$customerinfo['getCustomerShipping']->phone_num}}
                                                        </address>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endif


                                    <div class="">
                                        <div class="col-sm-12">

                                            <div class="widget widget-fullwidth widget-small">

                                                <div class="flash-message">
                                                    @include('notification/notification')
                                                </div>


                                                <div class="sms-table-list" id="pageResult">


                                                    <div id="pageresult">
                                                        <input type="hidden" class="form-control serviceList"
                                                               name="serviceID"
                                                               value="{{$serviceID}}">

                                                        @if($data)
                                                            <input type="hidden"
                                                                   value="{{$customerinfo['getPlans']}}"
                                                                   name="plans">


                                                            <div class="col-sm-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">
                                                                        {{--<div class="col-sm-3"--}}
                                                                        {{--style="border: 1px solid #ddd">--}}
                                                                        {{--<h3 style="font-size: 18px;font-style: italic;"--}}
                                                                        {{--class="model-heading">{{$row['modelDescription']}}--}}
                                                                        {{--({{$row['quantity']}})</h3>--}}

                                                                        {{--<div class="row"--}}
                                                                        {{--style="    margin-top: 25px;">--}}
                                                                        {{--<div class="col-sm-6">--}}
                                                                        {{--<address>--}}

                                                                        {{--<strong>Contact--}}
                                                                        {{--Name : </strong><a--}}
                                                                        {{--mailto:#>{{$row["contactName"]}}</a>--}}


                                                                        {{--</address>--}}
                                                                        {{--</div>--}}
                                                                        {{--<div class="col-sm-6">--}}
                                                                        {{--<strong>Address--}}
                                                                        {{--: </strong><a--}}
                                                                        {{--mailto:#>{{$row["location"]}}</a>--}}
                                                                        {{--</div>--}}
                                                                        {{--</div>--}}


                                                                        {{--<address--}}
                                                                        {{--</address>--}}
                                                                        {{--<address><strong>Customer Telephone<br></strong><a--}}
                                                                        {{--mailto:#>{{$row["customerTelephone"]}} </a>--}}
                                                                        {{--</address>--}}


                                                                        {{--</div>--}}
                                                                        <div class="col-sm-12">
                                                                            <table id="example2"
                                                                                   class="footable table table-bordered table-hover">


                                                                                <tr>
                                                                                    {{--<th>Contact Name</th>--}}
                                                                                    {{--<th>Telephone</th>--}}
                                                                                    {{--<th>Location</th>--}}
                                                                                    <th>Asset #</th>
                                                                                    <th>Serial #</th>
                                                                                    <th>Instrument</th>
                                                                                    <th>Last Cal Date</th>
                                                                                    <th style="width:15%!important;">
                                                                                        Frequency
                                                                                    </th>
                                                                                    {{--<th class="nextDueDate-{{$row["buy_service_model_id"]}}">--}}
                                                                                    <th>
                                                                                        Next Due Date
                                                                                    </th>
                                                                                    {{--<th>--}}
                                                                                    {{--Next Due Date--}}
                                                                                    {{--</th>--}}


                                                                                    {{--<th class="exactDate-{{$row["buy_service_model_id"]}}"--}}
                                                                                    {{--style="display: none;">Exact Date--}}
                                                                                    {{--</th>--}}
                                                                                    <th style="width:15%!important;">
                                                                                        Service
                                                                                        Plan
                                                                                    </th>
                                                                                    <th>Price</th>
                                                                                </tr>

                                                                                <!--                                                                                    --><?php //$model_index = 1; ?>
                                                                                <?php $sub_total = 0 ?>
                                                                                @foreach($data as $key=>$row)
                                                                                    <div class="ser-detail-sec-inr">
                                                                                        <?php $total_equipments = $row['quantity'];
                                                                                        //                                                                                        $sub_index = 1;
                                                                                        ?>

                                                                                        @for($i=0;$i<$total_equipments;$i++)
                                                                                            <tbody>

                                                                                            <tr class="viewDetail">

                                                                                                {{--<td>{{$row["contactName"]}}</td>--}}
                                                                                                {{--<td>{{$row["customerTelephone"]}}</td>--}}

                                                                                                <td><input type="text"
                                                                                                           class="form-control serviceList"
                                                                                                           name="model[{{$row["buy_service_model_id"]}}][{{$i}}][asset_no]">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input type="text"
                                                                                                           class="form-control serviceList"
                                                                                                           name="model[{{$row["buy_service_model_id"]}}][{{$i}}][serial_no]">
                                                                                                </td>
                                                                                                <td>{{$row['modelDescription']}}</td>
                                                                                                <td>
                                                                                                    <input
                                                                                                            type="text"
                                                                                                            class="form-control serviceList lastdatepicker lastCalDateVal_{{$i}}"
                                                                                                            data-unique={{$i}} data-id={{$row["buy_service_model_id"]}}
                                                                                                                    id="lastCal-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                            name="model[{{$row["buy_service_model_id"]}}][{{$i}}][last_cal_date]"
                                                                                                            value="">
                                                                                                    <input
                                                                                                            type="hidden"
                                                                                                            class="form-control serviceList lastdatepicker lastCalDateVal_{{$i}}"
                                                                                                            data-unique={{$i}} data-id={{$row["buy_service_model_id"]}}
                                                                                                                    id="LastCaldate-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                            name="model[{{$row["buy_service_model_id"]}}][{{$i}}][lastcaldate]"
                                                                                                            value="{{date('m/d/Y')}}">
                                                                                                </td>
                                                                                                <input type="hidden"
                                                                                                       name="model[{{$row["buy_service_model_id"]}}][{{$i}}][hiddenfreq]"
                                                                                                       value="{{$row['frequency']}}">
                                                                                                <td>

                                                                                                    {!!Form::select("model[".$row['buy_service_model_id']."][".$i."][frequency]",$frequency,$row['frequency'],array('class'=>'changeFrequency serviceList serviceListdropdown frequencyOption_'.$i.' form-control','data-id'=>$row['buy_service_model_id'],'data-val'=>$i,'id'=>'frequencyOption_'.$row['buy_service_model_id']))!!}

                                                                                                    <span class="exactTextDate-{{$row["buy_service_model_id"]}}-{{$i}} popuptext"
                                                                                                          id="myPopup-{{$row['buy_service_model_id']}}-{{$i}}"
                                                                                                          style="display: none;    margin-left: -5px;
    margin-top: 3px;">

                                                                                                            <input
                                                                                                                    type="text"
                                                                                                                    class="form-control serviceList exactdatepicker"
                                                                                                                    placeholder="Pick up date"
                                                                                                                    data-id="{{$row['buy_service_model_id']}}"
                                                                                                                    data-val="{{$i}}"
                                                                                                                    attr="nextDue-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                                    id="exactDate-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                                    name="model[{{$row["buy_service_model_id"]}}][{{$i}}][exact_date]">
                                                                                                        </span>
                                                                                                    <input
                                                                                                            type="hidden"
                                                                                                            class="form-control serviceList"
                                                                                                            placeholder="Pick up date"
                                                                                                            data-id="{{$row['buy_service_model_id']}}"
                                                                                                            data-val="{{$i}}"
                                                                                                            id="intervalperiod-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                            name="model[{{$row["buy_service_model_id"]}}][{{$i}}][intervalperiod]">
                                                                                                </td>

                                                                                                <td class="nextDueTextDate-{{$row["buy_service_model_id"]}}-{{$i}}">
                                                                                                    <?php
                                                                                                    if ($row['frequency'] && $row['days']) {

                                                                                                        $next_due_date = date('m/t/Y', strtotime("+" . 3 . " months"));

                                                                                                    } else {
                                                                                                        $next_due_date = '';
                                                                                                    }
                                                                                                    ?>
                                                                                                    <input type="text"
                                                                                                           id="nextDue-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                           value="{{$next_due_date}}"
                                                                                                           readonly
                                                                                                           class="form-control serviceList calByDate_{{$i}} nextDue-{{$row["buy_service_model_id"]}}-{{$i}}"
                                                                                                           name="model[{{$row["buy_service_model_id"]}}][{{$i}}][next_due_date]">
                                                                                                </td>

                                                                                                <td>
                                                                                                    {!!Form::select("model[".$row['buy_service_model_id']."][".$i."][service_plan]",$row['service_plans'],'',array('class'=>'change_service_plan serviceList serviceListdropdown form-control','id'=>'service_plan_'.$row["buy_service_model_id"].'_'.$i,'unq_id'=>$row["buy_service_model_id"],'current_row'=>$i))!!}

                                                                                                </td>

                                                                                                <td> $ <span
                                                                                                            id="service_price_{{$row["buy_service_model_id"]}}_{{$i}}">{{$row['service_price']}}</span>

                                                                                                    <input type="hidden"
                                                                                                           class="serviceList"
                                                                                                           name="model[{{$row["buy_service_model_id"]}}][{{$i}}][servicePriceId]"
                                                                                                           value="{{$row['service_price_id']}}"
                                                                                                           id="service_price_id_{{$row["buy_service_model_id"]}}_{{$i}}">
                                                                                                </td>

                                                                                                <?php $sub_total = $sub_total + $row['service_price'] ?>
                                                                                                <input type="hidden"
                                                                                                       name="sub_total"
                                                                                                       value="{{$sub_total}}">

                                                                                            </tbody>

                                                                                            <!--                                                                                            --><?php //$sub_index++; ?>
                                                                                        @endfor
                                                                                        @endforeach

                                                                                    </div>


                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <!--                                                                --><?php //$model_index++; ?>

                                                        @endif


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
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="am-radio inline">
                                    <input type="radio" checked="checked" name="accesstype"
                                           id="accessonly" value="1" class="accesstype">
                                    @if($customerinfo['access'] == 1)

                                        <label for="accessonly">Access for customer portal</label>
                                        <input type="hidden" value="1" name="logincredentials">
                                    @else

                                        <label for="accessonly">Create Equipment</label>
                                        <input type="hidden" value="0" name="logincredentials">
                                    @endif
                                </div>

                                <div class="am-radio inline">
                                    <input type="radio" name="accesstype" id="accesswithrequest"
                                           value="2" class="accesstype">
                                    @if($customerinfo['access'] == 1)
                                        <label for="accesswithrequest">Access for customer portal with service
                                            request</label>

                                        <input type="hidden" value="1" name="logincredentials" class="loginaccess">
                                    @else

                                        <label for="accesswithrequest">Create equipment with service request</label>
                                        <input type="hidden" value="0" name="logincredentials" class="loginaccess">

                                    @endif
                                </div>
                                <div class="am-radio inline">
                                    <div class="shippingDiv" style="display: none;">
                                        <label>Shipping Date</label>

                                        <p class=""><input type="text" id="shippingDate" autocomplete="off"
                                                           class="form-control datepickerselect"
                                                           name="shippingDate"></p>
                                    </div>
                                </div>
                                @if($customerinfo['access'] == 1)
                                    <div class="am-radio inline"><strong>Email Id<br></strong><a
                                                mailto:#><span

                                                    id="changedCustomerEmail">{{$customerinfo['getCustomer']->customer_email}}</span></a>
                                        <input id="adminverify" type="checkbox"
                                               name="admin_email_verify" value="1">
                                        <label for="adminverify"> is Verify</label>
                                    </div>
                                @endif
                                <div style="float:right;">
                                    {{--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>--}}
                                    {{--<div class="shipping_charge" style="display: none">--}}
                                        {{--<a data-toggle="modal" class="btn btn-primary" data-target="#myModal"--}}
                                           {{--id="shipping_detail"> Service--}}
                                            {{--Details </a>--}}
                                    {{--</div>--}}
                                    {{--<div class="addtoportal">--}}
                                        <a href="javascript:void(0)" id="addToPortal"
                                           class="btn btn-space btn-primary">Add to portal</a>
                                    {{--</div>--}}

                                    <span style="font-size: 20px;display:none" id="addtoportSpinner"><i
                                                class="fa fa-spinner fa-spin"></i> </span>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header"
                                 style="background-color: #003162;color: #ffffff; padding: 20px 15px;">
                                <button type="button" class="close" data-dismiss="modal" style="color: white">&times;
                                </button>
                                <h3 class="modal-title" style="text-align: center; ">Total Instruments
                                    - {{count($data)}}</h3>
                            </div>
                            <div class="modal-body" style="padding: 20px 15px;">
                                <div class="row">
                                    <table align="right">
                                        <tr>
                                            <td>Sub Total :</td>
                                            <td style="width: 170px"><input type="text" name="sub-total" id="sub-total"
                                                                            style="text-align: right"
                                                                            class="form-control"
                                                                            value="{{$sub_total}}" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>Shipping Charge :</td>
                                            <td style="width: 170px"><input type="text"
                                                                            name="shipping_cost"
                                                                            style="text-align: right"
                                                                            id="shipping_cost"
                                                                            class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Service Tax :</td>
                                            <td style="width: 170px"><input type="text" name="service_tax"
                                                                            id="service_tax" style="text-align: right"
                                                                            class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Total :</td>
                                            <td style="width: 170px;"><input type="text" name="total" id="total"
                                                                             style="text-align: right;font-size: medium"
                                                                             class="form-control" readonly></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="modal-footer">
                                    <a href="javascript:void(0)" class="btn btn-primary" data-dismiss="modal"
                                       id="addToPortal">Add To Portal</a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <input type="hidden" value="{{$getServiceDetails->customer_id}}" name="customer_id">

            </form>
        </div>
    </div>


    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>


    <script type="text/javascript">

        $('body').on('click', '#accesswithrequest', function () {

            console.log('show');
            $('.shippingDiv').show();
            $('.shipping_charge').show();
            $('.addtoportal').removeClass('addtoportal').addClass('notportalaccess');
        });

        // $('body').on('click', '.logincredentail', function () {
        //     // var loginaccess = $('.logincredential').val();
        //     var loginaccess = $(this).val();
        //     console.log(loginaccess);
        //     if (loginaccess == 1) {
        //         $('.shipping_charge').show();
        //     } else {
        //         $('.addtoportal').css('display:none');
        //     }
        // });


    </script>
    <script type="text/javascript">

        $('body').on('click', '#accessonly', function () {
            console.log('hide');

            $('.shippingDiv').hide();
            $('.shipping_charge').hide();
            $('.notportalaccess').removeClass('notportalaccess').addClass('addtoportal');
        });
        $('#shipping_cost').bind('keyup paste', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            var shipping_cost = $(this).val();
            var sub_total = $('#sub-total').val();
            var service_tax = $('#service_tax').val();
            if (shipping_cost != '' && service_tax != '') {
                var totalcost = parseInt(sub_total) + parseInt(shipping_cost) + parseInt(service_tax);
            } else if (service_tax != '') {
                var totalcost = parseInt(sub_total) + parseInt(service_tax);
            } else if (shipping_cost != '') {
                var totalcost = parseInt(sub_total) + parseInt(shipping_cost);
            } else {
                var totalcost = parseInt(sub_total);
            }
            $('#total').val(totalcost);
        });
        $('#service_tax').bind('keyup paste', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            var service_tax = $(this).val();
            console.log(service_tax);
            var sub_total = $('#sub-total').val();
            var shipping_cost = $('#shipping_cost').val();
            if (shipping_cost != '' && service_tax != '') {
                var totalcost = parseInt(sub_total) + parseInt(shipping_cost) + parseInt(service_tax);
            } else if (service_tax != '') {
                var totalcost = parseInt(sub_total) + parseInt(service_tax);
            } else if (shipping_cost != '') {
                var totalcost = parseInt(sub_total) + parseInt(shipping_cost);
            } else {
                var totalcost = parseInt(sub_total);
            }
            $('#total').val(totalcost);
        });


    </script>
    <script>
        $('body').on('change', '.change_service_plan', function () {
            var cus_service_model_id = $(this).attr('unq_id');
            var current_row = $(this).attr('current_row');
            var service_plan_id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: {cus_service_model_id: cus_service_model_id, service_plan_id: service_plan_id},
                url: "{{url('admin/pricingValue')}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        $('#service_price_' + cus_service_model_id + '_' + current_row).text(json.data.price);
                        $('#service_price_id_' + cus_service_model_id + '_' + current_row).val(json.data.id);
                        $.toast({
                            heading: 'Alert',
                            text: "Price has been changed",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });
                    } else {
                        $('#service_price_id_' + cus_service_model_id + '_' + current_row).val('');
                        $('#service_price_' + cus_service_model_id + '_' + current_row).text(0);
                        $.toast({
                            heading: 'Warning',
                            text: "No price value added for this combination",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loader: false
                        });


                    }
                }
            })
            ;
        });

        $('body').on('click', '#addToPortal', function () {


            var datastring = $('#servicesForm').serialize();
            // console.log(datastring.model);
            var inputs = $(".serviceList");
            var accesstype = $("#accesswithrequest").is(":checked");
            if (accesstype) {
                var shippingDate = $('#shippingDate').val();
                if (!shippingDate) {
                    $.toast({
                        heading: 'Alert',
                        text: 'shippingDate is required',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        icon: 'error',
                        loader: false
                    });
                    return false;
                }
            }

            // var pickupdate = $('')

            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: datastring,
                url: "{{url('admin/addToPortal')}}",
                dataType: "JSON",
                beforeSend: function () {
                    $('#addToPortal').hide();
                    // $('#shipping_detail').hide();
                    $('#addtoportSpinner').show();
                },
                success: function (json) {
                    if (json.result) {
                        console.log(json.reqId)

                        if (json.value == 1) {
                            var msg = 'Login created successfully.';
                            window.location.replace('buyservice');
                        } else {
                            var msg = 'Service request created sucessfully.';
                            window.location = '/novamed/admin/requestViewDetails/' + json.reqId;
                            //window.location = '/admin/requestViewDetails/' + json.reqId;
                        }
                        $.toast({
                            heading: 'Alert',
                            text: msg,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });
                    } else {
                        $('#addToPortal').show();
                        $('#addtoportSpinner').hide();
                        $.toast({
                            heading: 'Alert',
                            text: json.message,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loader: false
                        });

                        // $('#shipping_detail').show();

                        // $.toast({
                        //     heading: 'Warning',
                        //     text: "Something went wrong.Please checkout orders",
                        //     position: 'top-right',
                        //     showHideTransition: 'slide',
                        //     icon: 'error',
                        //     loader: false
                        // });


                    }
                }
            })
            ;
        });

    </script>
    <script type="text/javascript">

        $('body').on('click', '.changeFrequency', function () {
            var value = $(this).val();
            var modelId = $(this).attr('data-id');
            var val = $(this).attr('data-val');
            var lastCalDate = $("#lastCal-"+modelId+"-"+val).val();
            console.log(lastCalDate);
            if (value == 1) {

                var month = 3;
            } else if (value == 2) {
                var month = 6;
            } else if (value == 3) {
                var month = 12;
            }
            if (value == 4) {
                $('#nextDue-' + modelId + "-" + val).val('');

                var popup = document.getElementById("myPopup-" + modelId + "-" + val);
                popup.classList.toggle("show");

                $("#myPopup-" + modelId + "-" + val).show();


            } else {


                // var date = new Date();
                // console.log(date)
                // date.setMonth(date.getMonth() + parseInt(month));
                //
                // // var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                //
                // // var nexDue = dateFormat(new Date(lastDay), 'mm/dd/yyyy');
                // // var insVal = dateFormat(new Date(lastDay), 'yyyy-mm-dd');
                //
                // $('#exactDate-' + modelId + "-" + val).val('');
                // var nexDue = $.datepicker.formatDate('mm/dd/yy', date);
                // console.log(nexDue)
                //$('#nextDue-' + modelId + "-" + val).val(nexDue);
                $("#myPopup-" + modelId + "-" + val).hide();
                $("#myPopup-" + modelId + "-" + val).removeClass('show');

                // //cal by date and next due date
                //
                // var lastCalDateVal = $('.lastCalDateVal_' + val).val();
                // var lastCalDate = new Date(lastCalDateVal);
                // var lastCalDatePlusFrequency = new Date(lastCalDate.setMonth(lastCalDate.getMonth() + month));
                // var lastCalDatePlusFrequencyFormat = new Date(lastCalDatePlusFrequency.getFullYear(), lastCalDatePlusFrequency.getMonth() + 1, 0);
                // var lastCalDatePlusFrequencyFormatVal = ("0" + (lastCalDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + lastCalDatePlusFrequencyFormat.getDate() + '/' + lastCalDatePlusFrequencyFormat.getFullYear();
                // // $('.calByDate_' + val).val(lastCalDatePlusFrequencyFormatVal);
                // $('#nextDue-'+ modelId + "-" + val).val(lastCalDatePlusFrequencyFormatVal);
                //
                //next due date

                // var nextDueDate = new Date($('.calByDate_' + val).val());
                // var nextDueDate = new Date($('#nextDue-'+ modelId + "-" + val).val());
                if(!lastCalDate)
                {
                    var nextDueDate = new Date();
                    var nextDueDatePlusFrequency = new Date(nextDueDate.setMonth(nextDueDate.getMonth() + month));
                    var nextDueDatePlusFrequencyFormat = new Date(nextDueDatePlusFrequency.getFullYear(), nextDueDatePlusFrequency.getMonth() + 1, 0);
                    var nextDueDatePlusFrequencyFormatVal = ("0" + (nextDueDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + nextDueDatePlusFrequencyFormat.getDate() + '/' + nextDueDatePlusFrequencyFormat.getFullYear();
                    // $('.nextDueDateUp_' + val).val(nextDueDatePlusFrequencyFormatVal);
                    $('#nextDue-'+ modelId + "-" + val).val(nextDueDatePlusFrequencyFormatVal);
                }

            }
        });

    </script>

    <script>
        $(".telephone").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <div>
        <button data-modal="colored-deletewarning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-deletewarning deletePopUp">Warning
        </button>
    </div>
    <div id="colored-deletewarning"
         class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                            class="icon s7-close"></i></button>
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
    <script>
        $('body').on('click', '#updateCustomerDetailClick', function () {

            var data = $('#updatedCustomerForm').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url('admin/customerDetailsSubmit')}}",
                dataType: "JSON",
                success: function (json) {
                    $('.close').trigger('click');
                    if (json.result) {
                        $('#changedCustomerName').text(json.data.customer_name);
                        $('#changedCustomeraddress1').text(json.data.address1);
                        $('#changedCustomerCity').text(json.data.city);
                        $('#changedCustomerTel').text(json.data.customer_telephone);
                        $('#changedCustomerEmail').text(json.data.customer_email);


                    }
                }
            })
            ;
        });

    </script>

    <script>
        $('body').on('click', '.propertyDataEdit', function () {
            $(this).hide();
            var id = $(this).attr('data-id');
            $('.edit_' + id).each(function () {
                var content = $(this).html();
                var name = $(this).attr('attr');
                $(this).html('<input type="text" attr=' + name + ' id="property_' + name + '_' + id + '" class="form-control inline-edit text_value_' + id + '" value=' + content + '>');
            });
            $('#saveproperty_' + id).show();
        });

        $('body').on('click', '.detailsEdit', function () {

            var customer_id = $(this).attr('customer-id');
            var curr_id = $(this).attr('curr-id');
            var attr = $(this).attr('data-attr');
            var data = 'curr_id=' + curr_id + '&customer_id=' + customer_id + '&attr=' + attr;

            if (attr == 'billing') {
                $('#spinBillingProperty').show();
                $('#editBillingProperty').hide();
            } else if (attr == 'shipping') {
                $('#spinShippingProperty').show();
                $('#editShippingProperty').hide();
            }
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url('admin/requestDetailsEdit')}}",
                dataType: "JSON",
                success: function (json) {
                    if (attr == 'billing') {
                        console.log(attr);
                        $('#spinBillingProperty').hide();
                        $('#editBillingProperty').show();
                    } else if (attr == 'shipping') {
                        $('#spinShippingProperty').hide();
                        $('#editShippingProperty').show();
                    }
                    if (json.result) {
                        $('#propertyTitle').text(json.title);
                        $('.propertyHtml').html(json.data);
                        $('#updateType').val(json.attr);
                        $('.modalpopUpProperty').trigger('click');

                    }
                }
            })
            ;
        });


        $('body').on('click', '.propertyDataSave', function () {
            var id = $(this).attr('data-attr');
            var name = $('#property_name_' + id).val();
            var phone = $('#property_phone_' + id).val();
            var email = $('#property_email_' + id).val();
            var address1 = $('#property_address1_' + id).val();
            var address2 = $('#property_address2_' + id).val();
            var city = $('#property_city_' + id).val();
            var state = $('#property_state_' + id).val();
            var zipcode = $('#property_zip_' + id).val();
            var keyattr = $('#updateType').val();

            var datastring = {
                id: id, name: name, phone: phone, email: email, address1: address1, address2: address2,
                city: city, state: state, zipcode: zipcode, keyattr: keyattr,
                "_token": "{!! csrf_token() !!}"
            };
            $('#saveproperty_' + id).hide();
            $('#spinner_' + id).show();
            $.ajax({
                type: 'post',
                url: "{{url('admin/saveajaxcustomerproperty')}}",
                data: datastring,
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result) {
                            $('.edit_' + id).each(function () {
                                var attr = $(this).attr('attr');
                                var content = $('#property_' + attr + '_' + id).val();
                                $(this).html(content);
                            });

                            $('#spinner_' + id).hide();
                            $('#editpropertylist_' + id).show();
                            if (data.updated) {
                                $.toast({
                                    heading: 'Updated',
                                    text: data.message,
                                    //position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'success',

                                    loader: false
                                });
                            }

                        }

                    }
                }

            })
            ;

        });

        $('body').on('click', ".updateProperty", function () {

            $(this).hide();
            $('#spinLoader').show();
            var keyattr = $('#updateType').val();
            var id = $('input[name="property"]:checked').val();
            var workOrderId = $('#updateServiceRequestPropertyId').val();
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: {keyattr: keyattr, id: id, workOrderId: workOrderId},
                url: "{{url('admin/updateworkOrderProperty')}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        $('.close').trigger('click');
                        $('#spinLoader').hide();
                        $('.updateProperty').show();
                        if (json.keyattr == 'billing') {
                            $("#billingPropertyEdit").attr("curr-id", id);
                            $('#billing_name').text(json.data.name);
                            $('#billing_address1').text(json.data.address1);
                            $('#billing_city').text(json.data.city);
                            $('#billing_phone').text(json.data.phone);
                            $('#billing_email').text(json.data.email);
                        } else if (json.keyattr == 'shipping') {
                            $("#shippingPropertyEdit").attr("curr-id", id);
                            $('#shipping_name').text(json.data.name);
                            $('#shipping_address1').text(json.data.address1);
                            $('#shipping_city').text(json.data.city);
                            $('#shipping_phone').text(json.data.phone);
                            $('#shipping_email').text(json.data.email);
                        }
                        $.toast({
                            heading: 'Updated',
                            text: json.msg,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',

                            loader: false
                        });
                    }
                }
            })
            ;
        });

        $('body').on('click', '.cusomerDetailsEdit', function () {

            var id = $(this).attr('data-id');
            var attr = $(this).attr('data-attr');
            var data = '&id=' + id + '&attr=' + attr;

            if (attr == 'customer') {
                $('#spinCustomerProperty').show();
                $('#editCustomerProperty').hide();
            } else if (attr == 'shipping') {
                $('#spinShippingProperty').show();
                $('#editShippingProperty').hide();
            }
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url('admin/mainDetailsEdit')}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.attr == 'customer') {
                        $('#spinCustomerProperty').hide();
                        $('#editCustomerProperty').show();
                    } else if (attr == 'shipping') {
                        $('#spinShippingProperty').hide();
                        $('#editShippingProperty').show();
                    }
                    if (json.result) {
                        $('#detailsForm').html(json.data);
                        $('.modalpopUpCustomerProperty').trigger('click');

                    }
                }
            })
            ;
        });


    </script>

    <a style="display:none;" data-toggle="modal" data-target="#propertyList"
       class="modalpopUpProperty" data-icon="&#xe0be;" data-keyboard="false"
       data-backdrop="static"></a>


    <div id="propertyList" data-toggle="modal" tabindex="-1" role="dialog"
         class="modal fade modal-colored-header">
        <div class="modal-dialog customerpopup">
            <div class="modal-content cuustomercontent">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close md-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title"><span id="propertyTitle"></span></h3>
                </div>

                <div class="table-responsive modal-body form propertyHtml">


                </div>
                <input type="hidden" id="updateType">
                <input type="hidden" value="{!! $getServiceDetails->id !!}" id="updateServiceRequestPropertyId">
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary right updateProperty">Update</a>
                    <span style="display: none" id="spinLoader"><i
                                class="fa fa-spinner fa-spin inside-ico"></i></span>
                </div>
            </div>
        </div>
    </div>


    <a style="display:none;" data-toggle="modal" data-target="#customerDetail"
       class="modalpopUpCustomerProperty" data-icon="&#xe0be;" data-keyboard="false"
       data-backdrop="static"></a>

    <div id="customerDetail" data-toggle="modal" tabindex="-1" role="dialog"
         class="modal fade modal-colored-header">
        <div class="modal-dialog customerpopup">
            <div class="modal-content cuustomercontent">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close md-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title"><span id="">Customer Detail</span></h3>
                </div>

                <div class="modal-body" id="detailsForm">

                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary right"
                       id="updateCustomerDetailClick">Update</a>
                    <span style="display: none" id="spinLoader"><i
                                class="fa fa-spinner fa-spin inside-ico"></i></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.exactdatepicker')
                .datepicker({
                    format: 'mm/dd/yyyy',
                    orientation: "bottom",
                    startDate: "today",
                })
                .on('changeDate', function (e) {
                    var pickupdate = $(this).val();
                    console.log(pickupdate);
                    var modelId = $(this).attr('data-id');
                    var val = $(this).attr('data-val');
                    var attr = $(this).attr('attr');
                    $('#' + attr).val(pickupdate);
                    $(this).datepicker('hide');
                    var lastCalDate = $('#lastCal-' + modelId + '-' + val).val();
                    if (lastCalDate == '') {
                        var lastCalDate = $('#LastCaldate-' + modelId + "-" + val).val();
                    }
                    var startDay = new Date(pickupdate);
                    var endDay = new Date(lastCalDate);
                    var millisecondsPerDay = 1000 * 60 * 60 * 24;

                    var millisBetween = startDay.getTime() - endDay.getTime();
                    var days = millisBetween / millisecondsPerDay;

                    // Round down.
                    var total_days = (Math.floor(days));
                    $('#intervalperiod-' + modelId + '-' + val).val(total_days);

                });


            $('.datepickerselect')
                .datepicker({
                    format: 'mm/dd/yyyy',
                    orientation: "top",
                    startDate: 'today',
                    maxDate: new Date(),
                })
                .on('changeDate', function (e) {
                    $(this).datepicker('hide');
                });
            $(".lastdatepicker").datepicker({
                endDate: "today",
                altFormat: "mm/dd/yyyy",
                dateFormat: 'mm/dd/yyyy'
            }).on('changeDate', function (e) {
                var unique_id = $(this).attr('data-unique');
                var id = $(this).attr('data-id');
                var frequency_id = $('#frequencyOption_' + id).val();
                // var lastCal = $(this).val();
                // var lastCal = $(this).getMonth();
                var lastCal = new Date();
                console.log(lastCal);
                if (frequency_id != 4) {
                    // var addMonth = '';
                    // if (frequency_id == 1) {
                    //     addMonth = 3;
                    // } else if (frequency_id == 2) {
                    //     addMonth = 6;
                    // } else {
                    //     addMonth = 12;
                    // }
                    addMonth = 0;
                    //Last Cal date
                    var lastCalDate = new Date(lastCal);
                    var lastCalDatePlusFrequency = new Date(lastCalDate.setMonth(lastCalDate.getMonth() + addMonth));
                    var lastCalDatePlusFrequencyFormat = new Date(lastCalDatePlusFrequency.getFullYear(), lastCalDatePlusFrequency.getMonth() + 1, 0);
                    var lastCalDatePlusFrequencyFormatVal = ("0" + (lastCalDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + lastCalDatePlusFrequencyFormat.getDate() + '/' + lastCalDatePlusFrequencyFormat.getFullYear();
                    var newDate = new Date(lastCalDatePlusFrequencyFormatVal);
                    // $('.calByDate_' + unique_id).val(lastCalDatePlusFrequencyFormatVal);
                    $('#nextDue-' + id + "-" + unique_id).val(lastCalDatePlusFrequencyFormatVal);

                    //Next Due date

                    var nextDueDate = new Date($('#nextDue-' + id + "-" + unique_id).val());
                    var nextDueDatePlusFrequency = new Date(nextDueDate.setMonth(nextDueDate.getMonth() + addMonth));
                    var nextDueDatePlusFrequencyFormat = new Date(nextDueDatePlusFrequency.getFullYear(), nextDueDatePlusFrequency.getMonth() + 1, 0);
                    var nextDueDatePlusFrequencyFormatVal = ("0" + (nextDueDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + nextDueDatePlusFrequencyFormat.getDate() + '/' + nextDueDatePlusFrequencyFormat.getFullYear();
                    // $('.nextDueDateUp_' + unique_id).val(nextDueDatePlusFrequencyFormatVal);
                    $('#nextDueDateUp-' + id + "-" + unique_id).val(nextDueDatePlusFrequencyFormatVal);

                }
                $(this).datepicker('hide');
            });
        });
    </script>

@stop
