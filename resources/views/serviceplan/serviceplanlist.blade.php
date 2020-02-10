@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Service Plan</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Master Setup</a></li>
                <li><a href="#">Service Plan</a></li>

                <!--                    <li class="active"></li>-->

            </ol>

            <div class="text-right div-rul">
                <a href="{{url('admin/serviceplan')}}" class="btn btn-space btn-primary">Add Service Plan</a>
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
                                                <th>Plan Name</th>
                                                <th>Product Type</th>
                                                <th>As Found</th>
                                                <th>As Calibrated</th>
                                                <th>Pricing Criteria</th>
                                                <th>Edit</th>
                                                <th>Delete</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Plan Name</th>
                                                <th>Product Type</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
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
        <div id="form-bp1" tabindex="-1" role="dialog" class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">
                <form id="rejected" action="" method="get">

                    <div class="modal-content" id="result">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><i
                                        class="icon s7-close"></i></button>
                            <h3 class="modal-title">Pricing Criteria</h3>
                        </div>

                        <div class="modal-body">

                        </div>

                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger md-close" id="closemodel">
                                Cancel
                            </button>

                        </div>
                    </div>

                </form>
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
            $('#listTable tfoot th').each(function (index) {
                console.log(index)
                if (index != 2 && index != 3 && index != 4 && index != 5 && index != 6) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                else if(index == 2 ) {
                    $(this).html('{!!Form::select("asfounds",$asFound,'',array('class'=>''))!!}');
                }else if(index == 3){
                    $(this).html('{!!Form::select("ascalibrates",$asCalibrate,'',array('class'=>''))!!}');

                }

            });

            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/servicelistdata')}}",
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

        <script src="{{asset('js/jquery.js')}}"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-confirm.css')}}">
        <script src="{{asset('js/jquery-confirm.js')}}"></script>
        <script type="text/javascript">


            $('body').on('click', '#view', function () {
                var Id = $(this).attr('data-id');
                console.log(Id)
                $.ajax({
                    type: "GET",
                    url: '{{url("admin/viewServicePlan")}}',
                    data: {
                        "_token": "{!! csrf_token() !!}",
                        Id: Id
                    },
                    dataType: "json",
                    success: function (data) {
                        $('#result').find('.modal-body').html(data.formData);
                        $('.popUp').trigger('click');
//
                    }
                });
            });</script>

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




@stop
