@extends('layout.header')
@section('content')

    <style>
        .right {
            float: right;
        }

        .customerpopup {
            width: 80% !important;
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
            <h2>Order Details (Number :<span></span> {{$order->order_number}}) Total Amount : <i class="fa fa-dollar"
                                                                                                 aria-hidden="true"></i>
                @if($order->grand_total)
                {{$order->grand_total}}
                @else
                {{$totalAmount}}
                @endif
            </h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Order & Payment Management</a></li>
                <li><a href="#">Order Requests</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="{{url('admin/orderrequests')}}" class="btn btn-space btn-primary"><-- Go Back</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                <div class="panel panel-default keywordsearchpanel">

                </div>
            </div>


            <div class="row">
                {{--<div class="panel panel-default">--}}

                {{--<div class="panel-body">--}}
                <div class="col-sm-12">
                    <div class="widget widget-fullwidth widget-small">

                        <div class="flash-message">
                            @include('notification/notification')
                        </div>

                        <form role="form" id="workOrderForm" data-parsley-validate>
                            <input type="hidden" value="{{$order->id}}" name="orderId" id="orderId"/>

                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    @if($customer)
                                        <div class="">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details
                                                        <span class="right" id="editCustomerProperty"><a
                                                                    href="javascript:void(0)" id="CustomerPropertyEdit"
                                                                    data-id="{{$customer->id}}"
                                                                    data-attr="customer" class="cusomerDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinCustomerProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName">{{$customer->customer_name}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1">{{$customer->address1}}</span>
                                                            <br><span
                                                                    id="changedCustomerCity">{{$customer->city}}</span>
                                                            <br><span
                                                                    id="changedCustomerState">{{$customer->state}}</span>
                                                            <br><span
                                                                    id="changedCustomerZipcode">{{$customer->zip_code}}</span>
                                                            <br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel">{{$customer->customer_telephone}}</span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail">{{$customer->customer_email}}</span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details
                                                        <span class="right" id="editBillingProperty"><a
                                                                    href="javascript:void(0)" id="billingPropertyEdit"
                                                                    curr-id="{{$billing->id}}"
                                                                    customer-id="{{$billing->customer_id}}"
                                                                    data-attr="billing" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinBillingProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="billing_name">{{$billing->billing_contact}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="billing_address1">{{$billing->address1}}</span>
                                                            <br><span
                                                                    id="billing_city">{{$billing->city}}</span>
                                                            <br><span
                                                                    id="billing_city">{{$billing->state}}</span>
                                                            <br><span
                                                                    id="billing_city">{{$billing->zip_code}}</span>
                                                            <br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="billing_phone">{{$billing->phone}}</span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="billing_email">{{$billing->email}}</span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details<span class="right"
                                                                                                     id="editShippingProperty"><a
                                                                    href="javascript:void(0)" id="shippingPropertyEdit"
                                                                    curr-id="{{$shipping->id}}"
                                                                    customer-id="{{$shipping->customer_id}}"
                                                                    data-attr="shipping" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinShippingProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="shipping_name">{{$shipping->customer_name}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="shipping_address1">{{$shipping->address1}}</span>
                                                            <br><span
                                                                    id="shipping_city">{{$shipping->city}}</span>
                                                            <br><span
                                                                    id="shipping_city">{{$shipping->state}}</span>
                                                            <br><span
                                                                    id="shipping_city">{{$shipping->zip_code}}</span>
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><span
                                                                    id="shipping_phone">{{$shipping->phone_num}}</span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="shipping_email">{{$shipping->email}}</span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Contact Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName">{{$customer->customer_name}}</span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1">{{$customer->address1}}</span>
                                                            <br><span
                                                                    id="changedCustomerCity">{{$customer->city}}</span>
                                                            <br><span
                                                                    id="changedCustomerState">{{$customer->state}}</span>
                                                            <br><span
                                                                    id="changedCustomerZipcode">{{$customer->zip_code}}</span>
                                                            <br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel">{{$customer->customer_telephone}}</span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail">{{$customer->customer_email}}</span></a>
                                                        </address>
                                                    </div>
                                                    {{--<div class="panel-body">--}}
                                                        {{--<address>--}}
                                                            {{--<strong>{{$contact->contact_name}}--}}
                                                                {{--<br>--}}
                                                            {{--</strong>{{$contact->address1}}--}}
                                                            {{--<br>{{$contact->city}}--}}
                                                            {{--<br>{{$contact->state}}--}}
                                                            {{--<br>{{$contact->zip_code}}--}}
                                                            {{--<br>Contact: {{$contact->phone_no}}--}}
                                                        {{--</address>--}}
                                                        {{--<address><strong>Email Id <br></strong><a--}}
                                                                    {{--mailto:#>{{$contact->email_id}}</a>--}}
                                                        {{--</address>--}}
                                                    {{--</div>--}}
                                                </div>
                                            </div>
                                        </div>

                                    @endif

                                    <div class="">
                                        <div class="col-sm-12">
                                            <div class="widget widget-fullwidth widget-small">
                                                <div class="sms-table-list" id="pageResult">
                                                    <div class="table-responsive noSwipe">
                                                        <table class="table table-fw-widget table-hover"
                                                               style="width:100%">

                                                            <thead>
                                                            <tr>
                                                                <th width="13%">Line Item</th>
                                                                <th width="13%">Asset #</th>
                                                                <th width="13%">Serial #</th>
                                                                <th width="31%">Instrument</th>
                                                                <th></th>
                                                                <th width="10%">Price/Unit </th>
                                                                <th width="10%">Amount</th>


                                                            </tr>
                                                            </thead>


                                                            <tbody class="no-border-x ">
                                                            @if(count($orderItems))
                                                                <?php $i = 1; ?>

                                                                @foreach($orderItems as $key=>$row)
{{--                                                                    @foreach($row as $value)--}}
                                                                        {{--                                                            @foreach($orderItems as $value)--}}

                                                                        <tr>
                                                                            <td >{{$i}}</td>
                                                                            <td >{{$row['asset_no']}}</td>
                                                                            <td >{{$row['serial_no']}}</td>
                                                                            <td >{{$row['model_description']}}</td>
                                                                            <td></td>
                                                                            <td >
                                                                                $ {{number_format($row['order_item_amt'],2)}}</td>
                                                                            <td >
                                                                                $ {{number_format($row['order_item_amt'],2)}}</td>


                                                                        </tr>

                                                                    {{--@endforeach--}}
                                                                {{--@endforeach--}}
                                                                @if(isset($row['checklistName']))
                                                                    @if(($row['checklistName']))
                                                                        <tr>
                                                                            <td style="font-weight:bold">
                                                                                Maintenance:
                                                                            </td>
                                                                            <td colspan="5"
                                                                            >
                                                                                {{$row['checklistName']}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if($row['partdetails'])
                                                                        <tr style="border-bottom: solid 0px #ddd;background: #eceaea;">
                                                                            <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"></td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                SKU#
                                                                            </td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                Spare Mode
                                                                            </td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                Description
                                                                            </td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                Quantity
                                                                            </td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                Price
                                                                            </td>
                                                                            <td
                                                                                    style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                Amount
                                                                            </td>
                                                                        </tr>
                                                                        @foreach($row['partdetails'] as $sparekey)
                                                                            <tr>
                                                                                <td>
                                                                                    {{$sparekey['serialNumber']}}
                                                                                </td>
                                                                                <td>
                                                                                    {{$sparekey['SKU']}}
                                                                                </td>
                                                                                <td>
                                                                                    {{$sparekey['spareMode']}}
                                                                                </td>
                                                                                <td>
                                                                                    {{$sparekey['partName']}}
                                                                                </td>
                                                                                <td>
                                                                                    {{$sparekey['totalQuantity']}}
                                                                                </td>
                                                                                <td>
                                                                                    $ {{number_format($sparekey['partPrice'],2)}}
                                                                                </td>
                                                                                <td>
                                                                                    $ {{number_format($sparekey['totalAmount'],2)}}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach

                                                                    @endif


                                                                @endif
<?php $i++; ?>
                                                                @endforeach
                                                            @endif

                                                            {{--@if($order->grand_total)--}}
                                                            {{--<tr>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td>Sub Total</td>--}}
                                                            {{--<td><span> <i--}}
                                                            {{--class="fa fa-dollar"--}}
                                                            {{--aria-hidden="true"></i> {{$totalAmount}}</span>--}}
                                                            {{--</td>--}}

                                                            {{--</tr>--}}
                                                            {{--<tr>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td>Shipping Cost</td>--}}
                                                            {{--<td><span> <i--}}
                                                            {{--class="fa fa-dollar"--}}
                                                            {{--aria-hidden="true"></i>--}}
                                                            {{--@if($order->shipping_price != '')--}}
                                                            {{--{{$order->shipping_price}}--}}
                                                            {{--@else--}}
                                                            {{--0.00--}}
                                                            {{--@endif</span>--}}
                                                            {{--</td>--}}

                                                            {{--</tr>--}}
                                                            {{--<tr>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td></td>--}}
                                                            {{--<td>Service Tax</td>--}}
                                                            {{--<td><span> <i--}}
                                                            {{--class="fa fa-dollar"--}}
                                                            {{--aria-hidden="true"></i>--}}
                                                            {{--@if($order->sales_tax_price != '')--}}
                                                            {{--{{$order->sales_tax_price}}--}}
                                                            {{--@else--}}
                                                            {{--0.00--}}
                                                            {{--@endif--}}
                                                            {{--</span>--}}
                                                            {{--</td>--}}

                                                            {{--</tr>--}}
                                                            <tr>
                                                                <td colspan="5" rowspan="4"
                                                                    style="font-size:12px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;">
                                                                    {{--<p style="font-size: 11px;color:#666;">Comments</p>--}}
                                                                    {{--<p style="font-size: 11px;color:#999;">{{$comments}}</p>--}}
                                                                </td>
                                                                <td colspan="1">
                                                                    Sub Total
                                                                </td>
                                                                <td >
                                                                    $ {{number_format($totalAmount,2)}}
                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                   >
                                                                    Discount(-)
                                                                </td>
                                                                <td >
                                                                    @if($discount)
                                                                        $ {{number_format($discount,2)}}
                                                                    @else
                                                                        $ 0.00
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                    >
                                                                    Shipping &amp; Handling
                                                                </td>
                                                                <td >
                                                                    @if($shipping_price)
                                                                        $ {{number_format($shipping_price,2)}}
                                                                    @else
                                                                        $ 0.00
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                    >
                                                                    Total
                                                                </td>
                                                                <td >
                                                                    @if($grand_total)
                                                                        $ {{number_format($grand_total,2)}}
                                                                    @else
                                                                        $ {{number_format($totalAmount,2)}}
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            </tbody>


                                                        </table>

                                                    </div>
                                                </div>


                                                <div class="panel panel-default"
                                                     style='text-align: center;position: relative;top: 10px;'>
                                                    <div class="panel-body">
                                                        <div class="text-center download">
                                                            @if($orderDetails->po_document)
                                                                <a href="{{url('admin/downloadCustomerPO/'.$order->id)}}"
                                                                   class="dload">Download <br> Customer's PO</a>
                                                                {{--<a download="{{$orderDetails->po_document}}"--}}
                                                                {{--href="{{'http://' . $_SERVER['SERVER_NAME'] . '/public/purchaseorder/customerdocuments/'. $orderDetails->po_document}}"--}}
                                                                {{--class="dload">--}}
                                                                {{--Download <br> Customer's PO</a>--}}
                                                            @endif

                                                            @if($order->invoice_generation==1)
                                                                <a target="_blank"
                                                                   href="{{url('admin/openinvoice/'.$order->id.'')}}"
                                                                   class="btn btn-space btn-primary right invoicebtn"><i
                                                                            class="fa fa-eye"></i> View Invoice</a>
                                                            @else
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-space btn-primary right invoiceGeneration invoicebtn"
                                                                   id="{{$order->id}}" data-toggle="modal"
                                                                   data-target="#form-bp1"><i class="fa fa-file"></i>
                                                                    Generate Invoice</a>
                                                                {{--<a href="javascript:void(0);" class="btn btn-space btn-primary right"><i class="fa fa-file"></i> Generate Invoice</a>--}}
                                                            @endif
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </form>
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

        <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">

                <div class="modal-content">
                    <div class="modal-header"
                         style="background-color: #003162;color: #ffffff; padding: 20px 15px;">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        {{--<h3 class="modal-title" style="text-align: center; ">Total Instruments--}}
                        {{--- {{count($orderItems)}}</h3> --}}
                        <h3 class="modal-title" style="text-align: center; ">
                            Comments for invoice</h3>
                    </div>
                    {{--<div class="modal-header">--}}
                    {{--<button type="button" data-dismiss="modal" aria-hidden="true"--}}
                    {{--class="close md-close"><i class="icon s7-close"></i></button>--}}
                    {{--<h3 class="modal-title">Comments for invoice</h3>--}}
                    {{--</div>--}}

                    <form id="addTech" action="{{url('admin/generateinvoice/'.$order->id)}}" method="get"
                          data-parsley-validate="" novalidate="">

                        {{--<div class="modal-body form">--}}
                        <div class="modal-body form">


                            <div class="form-group"><br></br>
                                <label>Comments</label>

                                {{ Form::textarea('comments', null, ['class' => 'form-control','id'=>'comments']) }}

                            </div>
                            <input type="hidden" name="id" id="order_id" value="{{$order->id}}">

                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal"
                                        class="btn btn-default md-close">
                                    Cancel
                                </button>

                                <button type="submit" id=""
                                        class="btn btn-primary">
                                    Proceed
                                </button>
                            </div>

                        </div>
                        {{--<div class="modal-body form" style="padding: 20px 15px;">--}}
                        {{--<div class="row">--}}
                        {{--<table align="right">--}}
                        {{--<tr>--}}
                        {{--<td>Sub Total :</td>--}}
                        {{--<td style="width: 170px;padding-bottom:10px;padding-right: 20px"><input--}}
                        {{--type="text" name="sub-total" id="sub-total"--}}
                        {{--style="text-align: right" class="form-control"--}}
                        {{--value="{{$totalAmount}}" readonly></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Shipping Charge :</td>--}}
                        {{--<td style="width: 170px;padding-bottom:10px;padding-right: 20px"><input--}}
                        {{--type="text"--}}
                        {{--name="shipping_cost" style="text-align: right"--}}
                        {{--id="shipping_cost"--}}
                        {{--class="form-control"></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Sales Tax :</td>--}}
                        {{--<td style="width: 170px;padding-bottom:10px;padding-right: 20px"><input--}}
                        {{--type="text" name="service_tax"--}}
                        {{--id="service_tax" style="text-align: right"--}}
                        {{--class="form-control"></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Total :</td>--}}
                        {{--<td style="width: 170px;padding-bottom:10px;padding-right: 20px"><input--}}
                        {{--type="text" name="total" id="total"--}}
                        {{--style="text-align: right;font-size: medium" class="form-control"--}}
                        {{--readonly></td>--}}
                        {{--</tr>--}}
                        {{--</table>--}}
                        {{--</div>--}}

                        {{--<div class="modal-footer">--}}
                        {{--<a href="javascript:void(0)" class="btn btn-primary" data-dismiss="modal" id="addToPortal">Add To Portal</a>--}}

                        {{--</div>--}}
                        {{--<input type="hidden" name="id" id="order_id" value="{{$order->id}}">--}}

                        {{--<div class="modal-footer">--}}
                        {{--<button type="button" data-dismiss="modal"--}}
                        {{--class="btn btn-default md-close">--}}
                        {{--Cancel--}}
                        {{--</button>--}}

                        {{--<button type="submit" id=""--}}
                        {{--class="btn btn-primary">--}}
                        {{--Proceed--}}
                        {{--</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group"><br></br>--}}
                        {{--<label>Comments</label>--}}

                        {{--{{ Form::textarea('comments', null, ['class' => 'form-control','id'=>'comments']) }}--}}

                        {{--</div>--}}


                        {{--</div>--}}

                    </form>

                </div>
            </div>
        </div>

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
                    <input type="hidden" value="{!! $order->id !!}" id="updateServiceRequestPropertyId">
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
            <div class="modal-dialog">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="">Customer Detail</span></h3>
                    </div>

                    <div class="modal-body" id="detailsForm">

                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-primary right" id="updateCustomerDetailClick">Update</a>
                        <span style="display: none" id="spinLoader"><i
                                    class="fa fa-spinner fa-spin inside-ico"></i></span>
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
        <script src="{{asset('js/jquery.js')}}"></script>
        <script>
            $("form").submit(function (e) {
                e.preventDefault();
                var comments = $('#comments').val();
                if (comments == '') {
                    $.toast({
                        heading: 'Warning',
                        text: 'Comments should not be empty',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        icon: 'error',
                        bgColor: '#ef6262',

                        loader: false
                    });
                    return false;
                } else {
                    $("form").submit();
                }
            });
            $('#shipping_cost').bind('keyup paste', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                var shipping_cost = $(this).val();
                var sub_total = $('#sub-total').val();
                var service_tax = $('#service_tax').val();
                if (service_tax) {
                    var totalcost = parseInt(sub_total) + parseInt(shipping_cost) + parseInt(service_tax);
                } else {
                    var totalcost = parseInt(sub_total) + parseInt(shipping_cost);
                }
                $('#total').val(totalcost);
            });
            $('#service_tax').bind('keyup paste', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                var service_tax = $(this).val();
                console.log(service_tax);
                var sub_total = $('#sub-total').val();
                var shipping_cost = $('#shipping_cost').val();
                if (shipping_cost) {
                    var totalcost = parseInt(sub_total) + parseInt(service_tax) + parseInt(shipping_cost);
                } else {
                    var totalcost = parseInt(sub_total) + parseInt(service_tax);

                }
                // console.log(totalcost);
                $('#total').val(totalcost);
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
                    url: "{{url("admin/requestDetailsEdit")}}",
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
                });
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
                    url: "{{url("admin/saveajaxcustomerproperty")}}",
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

                });

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
                    url: "{{url("admin/updateworkOrderProperty")}}",
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
                });
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
                    url: "{{url("admin/mainDetailsEdit")}}",
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
                });
            });
        </script>
        <script>
            $('body').on('click', '#updateCustomerDetailClick', function () {

                var data = $('#updatedCustomerForm').serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: data,
                    url: "{{url("admin/customerDetailsSubmit")}}",
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
                });
            });

        </script>
        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #ef6262 !important;
                color: white !important;
            }


        </style>
        <div>
            <button data-modal="colored-warning" style="display:none;"
                    class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
            </button>
        </div>





@stop
