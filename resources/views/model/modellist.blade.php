@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Instrument Models</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Master Setup</a></li>
                <li><a href="#">Instrument Models </a></li>

            </ol>
            @if($postvalue)
                @if($posttestplanid)
                    <?php $msg = 'Model has been updated'; ?>
                @else
                    <?php $msg = 'Model has been created'; ?>
                @endif
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                      class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span>{{ $msg }}
                </div>
            @endif

            <div class="text-right div-rul">
                <a href="{{url('admin/model')}}" class="btn btn-space btn-primary">Create Instrument Model</a>
                <a href="{{url('admin/instrumentModelUploadPage')}}" class="btn btn-space btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Upload Instrument Model</a>

            </div>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="col-sm-18">
                            <div class="widget widget-fullwidth widget-small">
                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>

                                <div class="view-all-service-req">


                                    <div class="service-req-tbl" id="first-datatable-output">
                                        <table class="table table-bordere table-striped display" id="listTable">
                                            <thead>
                                            <tr>

                                                <th style="width: 5%;">Manufacturer</th>
                                                <th style="width: 5%;">Brand</th>
                                                <th style="width: 25%;">Instrument</th>
                                                <th style="width: 10%;">Operation</th>
                                                <th style="width: 10%;">Channel Type</th>
                                                <th style="width: 10%;">Volume Type</th>
                                                <th style="width: 10%;">Volume From</th>
                                                <th style="width: 10%;">Volume To</th>
                                                <th style="width: 5%;">Units</th>
                                                <th style="width: 5%;">Channels</th>
                                                <th style="width: 10%;">Model Sale Price</th>
                                                <th style="width: 10%;">SKU</th>
                                                <th style="text-align: center;width: 10%;">Edit</th>
                                                <th style="text-align: center;width: 10%;">Delete</th>
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

                                                <th style="width: 5%;">Manufacturer</th>
                                                <th style="width: 5%;">Brand</th>
                                                <th style="width: 25%;">Model Name</th>
                                                <th style="width: 10%;">Operation</th>
                                                <th style="width: 10%;">Channel Type</th>
                                                <th style="width: 10%;">Volume Type</th>
                                                <th style="width: 10%;">Volume From</th>
                                                <th style="width: 10%;">Volume To</th>
                                                <th style="width: 5%;">Units</th>
                                                <th style="width: 5%;">Channels</th>
                                                <th style="width: 10%;">Model Sale Price</th>
                                                <th style="text-align: center;width: 10%;"></th>
                                                <th style="text-align: center;width: 10%;"></th>


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
            if (index != 0 && index != 12 && index != 13) {
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            }
            else if (index == 0) {
                $(this).html('{!!Form::select("manufacturer",$manufacturer,'',array('class'=>''))!!}');
            } else {

            }

        });

        $('#listTable').DataTable({
            "bServerSide": true,
            "sAjaxSource": "{{url('admin/modellistdata')}}",
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

                    $('select', this.footer()).on('change', function () {
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
