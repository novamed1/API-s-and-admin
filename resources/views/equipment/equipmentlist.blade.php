@extends('layout.header')
@section('content')

    <div class="am-content gridLayout">
        <div class="page-head">

            <h2>Customer's Instrument List</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Instrument Management</a></li>
                <li><a href="#">Customer's Instrument List </a></li>

            </ol>


            <div class="text-right div-rul">
                <a href="{{url('admin/addview')}}" class="btn btn-space btn-primary">Create Instrument</a>
                <a href="{{url('admin/uploadPage')}}" class="btn btn-space btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Upload Instruments with Model description</a>
                <a href="{{url('admin/uploadPageSeperate')}}" class="btn btn-space btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Upload Instruments without Model description</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row">

                <div class="panel panel-default">


                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-5" style="float: right;margin-bottom: -15px;">
                                <label>*Choose Customer</label>
                                <div class="form-group">

                                    {!!Form::select("due_status",$customer,'',array('class'=>'customerList form-control','attr'=>'due_status'))!!}
                                </div>


                            </div>


                        </div>

                    </div>


                </div>
            </div>


            <div class="view-all-service-req">

                <div class="flash-message">
                    @include('notification/notification')
                </div>


                <div class="service-req-tbl" id="first-datatable-output">
                    <table class="table" id="listTable">
                        <thead>
                        <tr>
                            <th style="width: 3%">S.No</th>
                            <th style="width: 9%">Asset#</th>
                            <th style="width: 9%">Serial#</th>
                            <th style="width: 20%">Instrument</th>
                            <th style="width: 11%">Location</th>
                            <th style="width: 9%">Pref Contact</th>
                            <th style="width: 9%">Pref Tel</th>
                            <th style="width: 12%">Pref Email</th>
                            <th style="width: 3%">Edit</th>
                            <th style="text-align: center;width: 5%">Delete</th>
                        </tr>

                        </thead>


                        {{--<tfoot>--}}
                        {{--<tr class="fltr-row">--}}

                        {{--<th><input type="text" placeholder="Model Name" name="model_name"/></th>--}}
                        {{--<th><input type="text" placeholder="Channel" name="channel"/></th>--}}
                        {{--<th><input type="text" placeholder="Operation" name="operation"/></th>--}}
                        {{--<th><input type="text" placeholder="Manufacturer" name="manufacturer"/></th>--}}
                        {{--<th><input type="text" placeholder="Price" name="price"/></th>--}}
                        {{--<th></th>--}}

                        {{--</tr>--}}
                        {{--</tfoot>--}}

                        <tfoot>
                        <tr>

                            <th></th>
                            <th>Asset#</th>
                            <th>Serial#</th>
                            <th>Instrument</th>
                            <th>Location</th>
                            <th>Pref Contact</th>
                            <th>Pref Tel</th>
                            <th>Pref Email</th>
                            <th></th>
                            <th style="text-align: center;"></th>


                        </tr>

                        </tfoot>


                    </table>

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
            var title = $(this).text();
            if (index != 8 && index != 9 && index != 0) {
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            }

        });

        var listTable = $('#listTable').DataTable({
            "bServerSide": true,
            "sAjaxSource": "{{url('admin/viewlistdata')}}",
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
        $('.customerList').on('change', function () {
            var filter_value = $(this).val();
            var new_url = "{{url('admin/viewlistdata')}}/" + filter_value;
            listTable.ajax.url(new_url).load();
        });


    </script>



@stop
