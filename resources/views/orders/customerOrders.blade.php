@extends('layout.header')
@section('content')

    <style>
        .btn
        {
            display: initial;
            padding: 2px 10px;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>Customer Orders</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Website Management</a></li>
                <li><a href="#">Customer Orders</a></li>

                <!--                    <li class="active"></li>-->

            </ol>

        </div>
        <div class="main-content">

            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>


                                <div class="view-all-service-req">

                                    <div class="service-req-tbl" id="first-datatable-output">
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Customer Name</th>
                                                {{--<th>Total Products</th>--}}
                                                <th>Total Price (<i class="fa fa-dollar" aria-hidden="true"></i>)</th>

                                                <th>Order Date</th>
                                                <th>View Details</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Customer Name</th>
                                                {{--<th>Total Products</th>--}}
                                                <th>Total Price (<i class="fa fa-dollar" aria-hidden="true"></i>)</th>

                                                <th>Order Date</th>
                                                <th></th>

                                            </tr>
                                            </tfoot>


                                        </table>

                                    </div>
                                    <div class="panel panel-default">

                                        <div class="panel-body">
                                            <div class="text-right" id="paging-first-datatable">

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





        <script src="{{asset('js/jquery.js')}}"></script>

        <link rel="stylesheet" type="text/css" href="{{asset('datatable/datatable.min.css')}}" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="{{asset('datatable/jquery.dataTables.min.css')}}" media="screen">




        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="{{asset('datatable/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.jquery.min.js')}}"></script>


        <script>
            $('#listTable tfoot th').each(function(index) {
                if(index!=4)
                {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }

            });

            $('#listTable').DataTable( {
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/customerorderslistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength":10,
                "searching": true,
                initComplete: function() {
                    var api = this.api();

                    // Apply the search
                    api.columns().every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                }

            } );



        </script>


        <script type="text/javascript">

            $('body').on('click', '.payClick', function () {
                var id = $(this).attr('data-attr');
                var invoice_generation = $(this).attr('data-invoice');
                if (invoice_generation != 0) {
                    var customer = $('#cus_' + id).text();
                    var order = $('#order_' + id).text();
                    var request = $('#request_' + id).text();
                    var totalitems = $('#totalitems_' + id).text();
                    var amount = $('#amt_' + id).text();
                    $('#payAmount').val(amount);
                    $('#order_id').val(id);
                    $('.modalpopUp').click();
                }
                else {
                    $.toast({
                        heading: 'Alert',
                        text: 'You will not pay without invoice generation',
                        //position: 'top-left',
                        showHideTransition: 'slide',
                        icon: 'error',

                        loader: false
                    });
                }


            });

            $('body').on('change', '#mode', function () {

                var value = $(this).val()
                if (value == 1) {
                    $('#extraFields').show();
                }
                else {
                    $('#extraFields').hide();
                }

            });

            $('body').on('click', '#submitpay', function () {

                var data = $('#addPay').serialize();
                var mode = $('#mode').val();
                var amount = $('#payAmount').val();
                var bank = $('#payBank').val();
                var account = $('#payAccount').val();
                var cheque = $('#payCheque').val();
                var dd = $('#payDD').val();
                var order_id = $('#order_id').val();
                if (mode == 1) {
                    if (bank == '' || account == '' || cheque == '') {
                        $('#warningMsg').text('Bank name,Account number and cheque number are required fields');
                        $('.colored-warning').trigger('click');
                        return false;
                    }
                }

                $('#submitpay').hide();
                $('#loadSpinner').show();

                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: data,
                    url: "{{url('admin/addPay')}}",
                    dataType: "JSON",
                    success: function (json) {
                        hide_animation();
                        if (json.result) {

                            hide_animation();
                            $('.close').trigger('click');
                            $('#submitpay').show();
                            $('#loadSpinner').hide();
                            $('#pay' + order_id).empty();
                            $('#pay' + order_id).html('<span class="label label-success">{{"Paid"}}</span>');
                            $.toast({
                                heading: 'Success',
                                text: 'Order has been paid',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loader: false
                            });
                        }
                    }
                });
            });

        </script>

@stop
