@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Standard list</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Master Setup</a></li>
                <li><a href="#">Standard list</a></li>

                <!--                    <li class="active"></li>-->

            </ol>

            <div class="text-right div-rul">
                <a href="{{url('admin/adddevice')}}" class="btn btn-space btn-primary">Add Standard device</a>
            </div>
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
                                                <th>Serial Number</th>
                                                <th>Description</th>
                                                <th>Device Model</th>
                                                <th>Edit</th>
                                                <th>Delete</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Serial Number</th>
                                                <th>Description</th>
                                                <th>Device Model</th>
                                                <th></th>
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
                if (index != 3 && index != 4) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }


            });

            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/devicelistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
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

@stop
