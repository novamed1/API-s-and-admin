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
                <a href="{{url('admin/adddevice')}}" class="btn btn-space btn-primary">Add Standard Equipment Details</a>
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
                                                <th>Standard Equipment</th>
                                                <th>Manufacturer Serial Number</th>
                                                <th>Calibration Frequency</th>
                                                <th>Last Date</th>
                                                <th>Next Date</th>
                                                <th>Edit</th>
                                                <th>Assign</th>
                                                <th>Delete</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Standard Equipment</th>
                                                <th>Manufacturer Serial Number</th>
                                                <th>Calibration Frequency</th>
                                                <th>Last Date</th>
                                                <th>Next Date</th>
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

        <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title">Change Technician</h3>
                    </div>

                    <form id="addTech" method="post" data-parsley-validate="" novalidate="">
                        <input type="hidden" name="deviceId" id="deviceId" value="">
                        <div class="modal-body form" style="padding-top: 10px;">
                        </div>

                    </form>

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
                if (index != 5 && index != 6 && index != 7 && index !=3 && index !=4) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }else if(index == 3){
                    var title = $(this).text();
                    $(this).html('<input type="text" class="datepicker" placeholder="Search ' + title + '" />');
                }else if(index == 4){
                    var title = $(this).text();
                    $(this).html('<input type="text" class="datepicker" placeholder="Search ' + title + '" />');
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

        <script>
            $('body').on('click', '#changeTechnician', function () {


                var deviceId = $(this).attr('data-attr');

                $('#deviceId').val(deviceId);

                $.ajax({
                    type: "GET",
                    data: {
                        deviceId: deviceId,
                    },
                    url: "{{url("admin/checkTechnicianforDevice")}}",
                    dataType: "JSON",
                    success: function (json) {

                        if(json.result == true){
                            // console.log('fiii')
                            $('#addTech').find('.modal-body').html(json.formData);
                            $('#form-bp1').modal('show');
                        }else{
                            $.toast({
                                heading: 'Warning',
                                text: "No technicians are left to assign this device",
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'error',
                                loader: false
                            });
                        }


                    }
                });

                $('#deviceId').val(deviceId);
            });

        </script>

        <script>
            $('body').on('click', '#submitTech', function () {

                var technicians = $('input[name="tecchnicians"]:checked').map(function () {
                    return $(this).val();
                }).get();

                var deviceId = $('#deviceId').val();


                //if (technicians.length != 0) {

                // hide_animation();

                $.ajax({
                    type: "GET",
                    data: {
                        technicians: technicians,
                        deviceId: deviceId,
                    },
                    url: "{{url("admin/assignTechnicianforModel")}}",
                    dataType: "JSON",
                    success: function (json) {
//                        $('input[name="tecchnicians"]').each(function () {
//                            this.checked = false;
//                        });
                        $('#form-bp1').modal('hide');
                        if (json.result) {
                            $.toast({
                                heading: 'Success',
                                text: "Technician has been assigned to the device",
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'success',

                                loader: false
                            });

                        } else {

                        }
                    }
                });
                // }

            });

        </script>

@stop
