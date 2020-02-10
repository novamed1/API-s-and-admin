@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Customers Specification List</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Management</a></li>
                <li><a href="#">Customers Specification list</a></li>

                <!--                    <li class="active"></li>-->

            </ol>
            @if($postvalue)
                @if($posttestplanid)
                    <?php $msg = 'Customer has been updated'; ?>
                @else
                    <?php $msg = 'Customer has been created'; ?>
                @endif
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                      class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span>{{ $msg }}
                </div>
            @endif

            {{--<div class="text-right div-rul">--}}
                {{--<a href="{{url('admin/addcustomer')}}" class="btn btn-space btn-primary">Create Customer</a>--}}
            {{--</div>--}}
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
                                                <th>Name</th>
                                                <th>Customer ID</th>
                                                <th>Customer type</th>
                                                <th>Email</th>
                                                <th>Telephone</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Cal Spec</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Customer ID</th>
                                                <th>Customer type</th>
                                                <th>Email</th>
                                                <th>Telephone</th>
                                                <th>State</th>
                                                <th>City</th>
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
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-confirm.css')}}">
        <script src="{{asset('js/jquery-confirm.js')}}"></script>

        <script>

            $(function ($) {
                $('body').on("click", '.delete', function () {
                    var deleteUrl = $(this).attr('data-src');
                    console.log(deleteUrl)
                    $.confirm({
                        title: "Delete confirmation",
                        text: "Do you want to delete this record ?",
                        confirm: function () {
                            window.location = deleteUrl
                        },
                        cancel: function () {
                        },
                        confirmButton: "Yes",
                        cancelButton: "No"
                    });
                });
            });</script>


        <script>
            $('#listTable tfoot th').each(function (index) {
                if (index != 7 ) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }


            });

            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/customerspecifictionlistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                "processing": true,
                initComplete: function () {
                    var api = this.api();

                    // Apply the search
                    api.columns().every(function () {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });


                    });
                }

            });


        </script>
        {{--<a style="display:none;" data-toggle="modal" data-target="#full-info"--}}
        {{--class="popUp" data-icon="&#xe0be;" id="addService" data-keyboard="false"--}}
        {{--data-backdrop="static"></a>--}}
        <a data-modal="full-info" class="btn btn-space btn-info md-trigger popUp" style="display: none;"></a>
        <div id="full-info" class="modal-container modal-colored-header modal-effect-1">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title">Customer Specification Form</h3>
                </div>
                <div id="pageAppend">
                    <div class="modal-body mainbody">

                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary  modal-close">
                        OK
                    </a>
                </div>
            </div>
        </div>
        <script>

            $('body').on("click", '.viewCustomerdetails', function () {
                var customerId = $(this).attr('data-id');
                console.log(customerId)
                $.ajax({
                    type: 'get',
                    url: "{{url("admin/getCustomerInfo")}}",
                    data: {
                        "_token": "{!! csrf_token() !!}",
                        customerId: customerId,
                    },
                    dataType: "json",
                    success: function (res) {

                        if (res.result == true) {

                            $('#pageAppend').find('.mainbody').html(res.formData);
                            $('.popUp').trigger('click');
                            // $('#full-info').data-modal('toggle');


                        }
                    }
                });
            });


        </script>

@stop
