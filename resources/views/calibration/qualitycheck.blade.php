@extends('layout.header')
@section('content')

    <style>
        .btn {
            display: initial;
            padding: 2px 10px;
        }
    </style>

    <style>
        .div-rul a {
            margin-top: -40px;

        }

        #dt_basic tr th {
            font-size: 13px;
        }

        #dt_basic tr td {
            font-size: 11px;
        }

        @media (min-width: 800px) {
            .modal-dialog {
                width: 800px;
                /*            height:150%;
                */
                margin: 30px auto;
            }
        }

        .model-width {
            max-width: 800px;
        }

        .modal-colored-header .modal-content {
            background-color: #fff;
        }
        .not-active {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: black;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>QC Check</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Calibration</a></li>
                <li><a href="#">QC Check</a></li>

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
                                                <th></th>
                                                <th>Customer</th>
                                                <th>Request#</th>
                                                <th>Work Order#</th>
                                                <th>Total Instruments</th>
                                                <th>Plan</th>
                                                <th>Maintanence To</th>
                                                <th>Calibrated To</th>
                                                <th>Status</th>
                                                <th>Report</th>
                                                <th>View</th>
                                                <th>Send Email</th>


                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>Customer</th>
                                                <th>Request#</th>
                                                <th>Work Order#</th>
                                                <th>Total Instruments</th>
                                                <th>Plan</th>
                                                <th>Maintanence To</th>
                                                <th>Calibrated To</th>
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
        <a style="display:none;" data-toggle="modal" data-target="#customerDetail"
           class="modalpopUpCustomerProperty" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>

        <div id="customerDetail" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="">WorkOrder Items</span></h3>
                    </div>

                    <div id="detailsForm">
                    </div>
                </div>
            </div>
        </div>

        <a style="display:none;" data-toggle="modal" data-target="#reportUpload"
           class="modalpopUpreportUpload" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>

        <div id="reportUpload" tabindex="-1" role="dialog" class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title">Upload Report</h3>
                    </div>
                    <div class="modal-body form">
                        <div class="form-group">
                            <label>Calibration Report</label>
                            <input type="file" class="form-control" id="calibrationReportFile">
                            <span style="color:red;" id="caliReportErrorAll"></span>
                            <span>(File should be in pdf format)</span>
                        </div>

                        <p>
                            <input type="checkbox" name="complete" id="calibrationCompleteValue" value="1">  <label for="calibrationCompleteValue">Complete this calibration</label>
                        </p>
                    </div>
                    <input type="hidden" id="calibrationReportWorkorderId">
                    <div class="modal-footer">
                        <i class="fa fa-spinner fa-spin" aria-hidden="true" id="calibrationReportSubmitLoader" style="display:none;"></i>
                        <button type="button" data-dismiss="modal" class="btn btn-default md-close">Cancel</button>
                        <button type="button" data-dismiss="modal" class="btn btn-primary md-close" id="calibrationReportSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>


        <a style="display:none;" data-toggle="modal" data-target="#sendMailFormPopup"
           class="sendMailPopup" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>


        <div id="sendMailFormPopup" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog">
                <div class="modal-content model-width">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="">Generated PDF Reports</span></h3>
                    </div>

                    <div class="" id="sendMailForm">


                    </div>

                    <div class="modal-footer">

                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close">Close
                        </button>

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


            $('.entypo-export').click(function (e) {
                e.stopPropagation();
                var $this = $(this).parent().find('div');
                console.log($this)
                $(".openHoverAction-class div").not($this).removeClass('active');
                $this.toggleClass('active');
            });

            $(document).on("click", function (e) {
                if ($(e.target).is(".openHoverAction-class,.show-moreOptions,.entypo-export") === false) {
                    $(".show-moreOptions").removeClass("active");
                }
            });

            $(document).ready(function () {
                var $myGroup = $('#tagcollapse');
                $myGroup.on('show.bs.collapse', '.collapse', function () {
                    $myGroup.find('.collapse.in').collapse('hide');
                });
            });


        </script>


        <style type="text/css">
            .share-button {
                height: 20px;
                width: auto;
                position: relative;
            }

            .share-button label {
                cursor: pointer;
            }

            .share-button label {
                cursor: pointer;
            }

            .share-button .social {
                display: none;
            }

            .share-button ul {
                background: #ccc;
                color: #fff;
                width: auto;
                left: 0;
                list-style: outside none none;
                margin: 0 auto;
                padding: 0 8px;
                right: 0;
                height: auto;
                opacity: 0.9;
                border-radius: 15px;
                top: -20px;
            }

            .share-button ul li {
                box-sizing: content-box;
                cursor: pointer;
                height: 22px;
                margin: 0;
                padding: 8px 0 14px;
                text-align: center;
                transition: all 0.3s ease 0s;
                width: auto;
                z-index: 2;
            }

            .share-button .for-five.active {
                left: 10px;
                top: -11px;
            }

            .share-button .social.active {
                display: table;
                opacity: 1;
                transform: scale(1) translateY(-70px);
                transition: all 0.4s ease 0s;
                position: absolute;
                bottom: 0px;
                right: 75%;
                top: 100%;
            }

            .share-button ul::after {
                border-color: transparent transparent transparent #ccc;
                border-style: solid;
                border-width: 12px 0 14px 27px;
                content: "";
                display: block;
                height: 0;
                margin: 0 auto;
                position: absolute;
                right: -17px;
                top: 40%;
                width: 0;
                z-index: -1;
            }
        </style>

        <script>

            function format(d) {
                console.log(d);
                return d;
            }


            $('#listTable tfoot th').each(function (index) {
                if (index != 0 && index != 8 && index != 9 && index != 10 && index != 11) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                if (index == 1) {
                    $(this).html('{!!Form::select("customer",$customer,'',array('class'=>''))!!}');
                }

            });

            var table = $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/qualitychecklistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "aaData": 0,
                        "defaultContent": ''
                    },
                    {"aaData": "1"},
                    {"aaData": "2"},
                    {"aaData": "3"},
                    {"aaData": "4"},
                    {"aaData": "5"},
                    {"aaData": "6"},
                    {"aaData": "7"},
                    {"aaData": "8"},
                    {"aaData": "9"},
                    {"aaData": "10"},
                    {"aaData": "11"}
                ],
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

            $('#listTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = $(this).find('span').attr('data-id');

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    var datastring = {id: id, "_token": "{!! csrf_token() !!}"};
                    $.ajax({
                        type: 'post',
                        url: "{{url("admin/qcsublists")}}",
                        data: datastring,
                        dataType: "json",
                        success: function (json) {
                            if (json) {
                                if (json.result) {
                                    row.child(format(json.data)).show();
                                    tr.addClass('shown');
                                }

                            }
                        }
                    });
                }
            });


            // $('body').on('click', '.indreview', function () {
            $('body').on('click', '.entypo-export', function () {

                var id = $(this).attr('data-id');

                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: {workOrderId: id},
                    url: "{{url("admin/workOrderItems")}}",
                    dataType: "JSON",
                    success: function (json) {

                        $('#detailsForm').html(json.data);
                        $('.modalpopUpCustomerProperty').trigger('click');

                    }
                });
            });

            $('body').on('click', '.upload-report', function () {

                var id = $(this).attr('data-id');

                //$('#detailsForm').html(json.data);
                $('#calibrationReportWorkorderId').val(id);
                $('.modalpopUpreportUpload').trigger('click');

            });

            $('body').on('click', '.singleCaliReport', function () {

                var id = $(this).attr('data-id');

                //$('#detailsForm').html(json.data);
                $('#calibrationReportWorkorderItemId').val(id);
                $('.modalSinglepopUpreportUpload').trigger('click');

            });

            $('body').on('click', '#calibrationSingleReportSubmit', function () {

                var id = $('#calibrationReportWorkorderItemId').val();
                //$(this).addClass('not-active');

                var file_data = $('#calibrationSingleReportFile').prop('files')[0];
                var file_type_name = /[^/]*$/.exec(file_data.type)[0];
                if(file_type_name!='pdf')
                {

                    $('#caliReportError').text('You can upload only pdf formats');
                    return false;
                }
                else
                {
                    $('#caliReportError').text('');
                }
                $('#calibrationSingleReportSubmitLoader').show();
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('workorderItemId', id);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    cache       : false,
                    contentType : false,
                    processData : false,
                    data: form_data,
                    url: "{{url("admin/uploadCaliDocument")}}",
                    dataType: "JSON",
                    success: function (data) {

                        $('#calibrationSingleReportFile').val('');
                        $('#calibrationReportWorkorderItemId').val('');
                        $('#calibrationSingleReportSubmitLoader').hide();
                        $('.close').trigger('click');
                        $('#changedReview'+id).html(data.reviewIco);
                        $.toast({
                            heading: 'Report Uploaded',
                            text: data.message,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',

                            loader: false
                        });

                    }
                });

            });

            $('body').on('click', '#calibrationReportSubmit', function () {

                var id = $('#calibrationReportWorkorderId').val();
                var complete = 0;
                if($("#calibrationCompleteValue").prop('checked') == true){
                    complete =1;
                }
                else

                {
                    complete = 0;
                }
                //$(this).addClass('not-active');

                var file_data = $('#calibrationReportFile').prop('files')[0];
                var file_type_name = /[^/]*$/.exec(file_data.type)[0];
                if(file_type_name!='pdf')
                {

                    $('#caliReportErrorAll').text('You can upload only pdf formats');
                    return false;
                }
                else
                {
                    $('#caliReportErrorAll').text('');
                }
                $('#calibrationReportSubmitLoader').show();
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('workorderId', id);
                form_data.append('complete', complete);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    cache       : false,
                    contentType : false,
                    processData : false,
                    data: form_data,
                    url: "{{url("admin/uploadCaliDocumentAll")}}",
                    dataType: "JSON",
                    success: function (data) {

                        $('#calibrationReportFile').val('');
                        $('#calibrationReportWorkorderId').val('');
                        $('#calibrationReportSubmitLoader').hide();
                        $('.close').trigger('click');
                        $('#changedReviewAll'+id).html(data.reviewIco);
                        $.toast({
                            heading: 'Report Uploaded',
                            text: data.message,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',

                            loader: false
                        });

                    }
                });

            });


            $('body').on('click', '.sendMail', function () {
                var id = $(this).attr('data-id');
                console.log(id)

                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: {workOrderId: id},
                    url: "{{url("admin/getWorkOrderItemsforsendMail")}}",
                    dataType: "JSON",
                    success: function (json) {


                        $('#sendMailForm').html(json.data);
                        $('.sendMailPopup').trigger('click');

                    }
                });
            });

            $('body').on('click', '.sendMailToCustomer', function () {

                var id = $(this).attr('data-attr');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: {consolidate_id: id},
                    url: "{{url("admin/sendReportToCustomer")}}",
                    dataType: "JSON",
                    success: function (json) {
                        $('.close').trigger('click');
                        $.toast({
                            heading: 'Success',
                            text: 'Report has been sent',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });

                    }
                });
            });

        </script>
        <script>
            $('body').on('click', '#updateCustomerDetailClick', function () {
                $('#updateCustomerDetailClick').hide();
                $('.pdfSpinner').show();
                workOrderItemIDs = [];
                $("input:checkbox[name=workorderitems]:checked").each(function () {
                    workOrderItemIDs.push($(this).val());
                });


                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: {workOrderItemIDs: workOrderItemIDs, work_order_id: $('#work_order_id_value').val()},
                    url: "{{url("admin/chosenQualityCheck")}}",
                    dataType: "JSON",
                    success: function (json) {
                        $('.close').trigger('click');
                        $('.pdfSpinner').hide();
                        $('#updateCustomerDetailClick').show();
                        if (json) {

                            if (json.result = true) {
                                $.toast({
                                    heading: 'Success',
                                    text: json.msg,
                                    position: 'top-right',
                                    showHideTransition: 'slide',
                                    icon: 'success',
                                    loader: false
                                });
                            } else {
                                $.toast({
                                    heading: 'Warning',
                                    text: json.msg,
                                    position: 'top-right',
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    loader: false
                                });
                            }
                        }
                    }
                });
            });

        </script>

        <script>
            $('body').on('click', '.fa-plus-circle', function () {

                var thisattr = $(this).attr('data-attr');
                $(this).removeClass('fa-plus-circle');
                $(this).addClass('fa-minus-circle');

                $('.fa-minus-circle').each(function () {
                    if (thisattr == $(this).attr('data-attr')) {
                        $(this).removeClass('fa-plus-circle');
                        $(this).addClass('fa-minus-circle');
                    }
                    else {
                        $(this).addClass('fa-plus-circle');
                        $(this).removeClass('fa-minus-circle');
                    }

                });

            });

            $('body').on('click', '.fa-minus-circle', function () {
                $(this).removeClass('fa-minus-circle');
                $(this).addClass('fa-plus-circle');

            });

            $(document).ready(function () {
                var $myGroup = $('#tagcollapse');
                $myGroup.on('show.bs.collapse', '.collapse', function () {
                    $myGroup.find('.collapse.in').collapse('hide');
                });
            });

            //upload calibration report

            $('body').on('click', '#calibrationReportSubmit', function () {

                var id = $(this).attr('data-attr');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    data: {consolidate_id: id},
                    url: "{{url("admin/sendReportToCustomer")}}",
                    dataType: "JSON",
                    success: function (json) {
                        $('.close').trigger('click');
                        $.toast({
                            heading: 'Success',
                            text: 'Report has been sent',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });

                    }
                });
            });
        </script>


@stop
