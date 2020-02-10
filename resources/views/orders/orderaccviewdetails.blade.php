@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Customer Order Details (Order Number :<span></span> {{$orderdetails->order_number}})</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Setup</a></li>
                <li><a href="#">Customer Orders</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="{{url('admin/customerorders')}}" class="btn btn-space btn-primary">Go Back</a>
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

                        <form role="form" id="customerOrderForm" data-parsley-validate>

                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    @if($customerinfo)
                                        <div class="panel panel-default">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong>{{$customerinfo['getCustomer']->customer_name}}
                                                                <br>
                                                            </strong>{{$customerinfo['getCustomer']->address1}}
                                                            <br>{{$customerinfo['getCustomer']->city}}<br>

                                                            <abbr title="Phone">Contact: </abbr>{{$customerinfo['getCustomer']->customer_telephone}}

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#>{{$customerinfo['getCustomer']->customer_email}}</a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong>{{$customerinfo['getCustomerBilling']->billing_contact}}
                                                                <br>
                                                            </strong>{{$customerinfo['getCustomerBilling']->address1}}
                                                            <br>{{$customerinfo['getCustomerBilling']->city}}<br>
                                                            <abbr title="Phone">Contact: </abbr>{{$customerinfo['getCustomerBilling']->phone}}
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#>{{$customerinfo['getCustomerBilling']->email}}</a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong>{{$customerinfo['getCustomer']->customer_name}}
                                                                <br>
                                                            </strong>{{$customerinfo['getCustomerShipping']->address1}}
                                                            <br>{{$customerinfo['getCustomerShipping']->city}}
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr>{{$customerinfo['getCustomerShipping']->phone_num}}
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#>{{$customerinfo['getCustomerShipping']->email}}</a>
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
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr>{{$customerinfo['getCustomerShipping']->phone_num}}
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#>{{$customerinfo['getCustomerShipping']->email}}</a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endif


                                    <div class="panel-body">
                                        <div class="col-sm-12">

                                            <div class="widget widget-fullwidth widget-small">

                                                {{--<div class="flash-message">--}}
                                                    {{--@include('notification/notification')--}}
                                                {{--</div>--}}


                                                <div class="sms-table-list" id="pageResult">

                                                    <div class="table-responsive noSwipe">

                                                        <table class="table table-striped table-fw-widget table-hover">

                                                            <thead>
                                                            <tr>


                                                                <th>Model Name</th>
                                                                <th>Quantity</th>
                                                                <th>Total Price (<i class="fa fa-inr"
                                                                                    aria-hidden="true"></i>)
                                                                </th>


                                                            </tr>
                                                            </thead>
                                                            @if($data)
                                                                @foreach($data as $row)
                                                                    <tbody>

                                                                    <tr>


                                                                        <td>{{$row->accessories_name}}</td>
                                                                        <td>{{$row->quantity}}</td>
                                                                        <td><i class="fa fa-inr"
                                                                               aria-hidden="true"></i> {{$row->total_price}}
                                                                        </td>


                                                                    </tr>

                                                                    </tbody>
                                                                @endforeach
                                                            @endif

                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">

                                                <div class="">
                                                    <div class="text-right">
                                                        {{$data->links()}}

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
    </div>




@stop
