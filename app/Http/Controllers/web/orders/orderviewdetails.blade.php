@extends('layout.header')
@section('content')

    <style>
        .right
        {
            float: right;
        }
    </style>

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
                                        <div class="">
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


                                    <div class="">
                                        <div class="col-sm-12">

                                            <div class="widget widget-fullwidth widget-small">

                                                <div class="flash-message">
                                                    @include('notification/notification')
                                                </div>


                                                <div class="sms-table-list" id="pageResult">

                                                    <div class="table-responsive noSwipe">

                                                        <table class="table table-striped table-fw-widget table-hover">

                                                            <thead>
                                                            <tr>


                                                                <th>Name</th>
                                                                <th>Type</th>
                                                                <th>Quantity</th>
                                                                <th>Total Price (<i class="fa fa-dollar"
                                                                                    aria-hidden="true"></i>)
                                                                </th>


                                                            </tr>
                                                            </thead>
                                                            @if($data)
                                                                @foreach($data as $row)
                                                                    <tbody>

                                                                    <tr>


                                                                        <td>{{$row->name}}</td>
                                                                        <td>{{$row->type}}</td>
                                                                        <td>{{$row->quantity}}</td>
                                                                        <td><i class="fa fa-dollar"
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
                                            <div class="panel panel-default"
                                                 style='text-align: center;position: relative;top: 10px;'>
                                                <div class="panel-body">
                                                    <div class="text-center">
                                                        @if($orderdetails->invoice_generation==1)
                                                            <a target="_blank" href="{{url('admin/orderinvoice/'.$orderdetails->id.'')}}" class="btn btn-space btn-primary right"><i class="fa fa-eye"></i>  View Invoice</a>
                                                        @else
                                                            <a href="javascript:void(0);" class="btn btn-space btn-primary right invoiceGeneration" id="{{$orderdetails->id}}" data-toggle="modal" data-target="#form-bp1"><i class="fa fa-file"></i> Generate Invoice</a>
                                                            {{--<a href="javascript:void(0);" class="btn btn-space btn-primary right"><i class="fa fa-file"></i> Generate Invoice</a>--}}
                                                        @endif
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

    <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
         class="modal fade modal-colored-header">
        <div class="modal-dialog custom-width">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close md-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title">Comments for invoice</h3>
                </div>

                <form id="addTech" action="{{url('admin/generateorderinvoice/'.$orderdetails->id)}}" method="get" data-parsley-validate="" novalidate="">

                    <div class="modal-body form">


                        <div class="form-group"><br></br>
                            <label>Comments</label>

                            {{ Form::textarea('comments', null, ['class' => 'form-control','id'=>'comments']) }}

                        </div>
                        <input type="hidden" name="id" id="order_id" value="{{$orderdetails->id}}">

                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal"
                                    class="btn btn-default md-close">
                                Cancel
                            </button>

                            <button type="submit"  id=""
                                    class="btn btn-primary">
                                Proceed
                            </button>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>


    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        $("form").submit(function(e){
            e.preventDefault();
            var comments = $('#comments').val();
            if(comments=='')
            {
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
            }
            else
            {
                $("form").submit();
            }


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
