@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Customer Requests</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Website Management</a></li>
                <li><a href="#">Customer Requests</a></li>

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
                                                <!-- <th></th> -->
                                                <th>Service Number</th>
                                                <th>Customer Name</th>
                                                <th>Total Instruments</th>
                                                <th>Requested Date</th>
                                                <th>View Detail</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <!-- <th></th> -->
                                                <th>Service Number</th>
                                                <th>Customer Name</th>
                                                <th>Total Instruments</th>
                                                <th>Requested Date</th>
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
                if(index!=4 && index !=3)
                {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                if (index == 3) {
                    $(this).html('<input type="text" class="datepicker" placeholder="Search Requested Date" />');
                }


            });

            $('#listTable').DataTable( {
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/buyservicelistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength":10,
                "searching": true,
                "processing": true,
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

@stop
