<?php $__env->startSection('content'); ?>

    <style>
        .btn {
            display: initial;
            padding: 2px 10px;
        }
    </style>
    <link href="<?php echo e(asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">

    <div class="am-content">
        <div class="page-head">
            <div class="row">
                <div class="col-md-4">
                    <h2>Due List</h2>
                </div>
                <div class="col-md-6 daterange" style="display: none;">
                    <h2>Due list from <span style="font-weight: 500" id="fromdue">01-Aug-2018</span> to <span
                                style="font-weight: 500" id="todue">01-Aug-2018</span></h2>
                </div>
            </div>

            
            <ol class="breadcrumb">

                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Instrument Management</a></li>
                <li><a href="#">Due List</a></li>

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
                                    <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                </div>


                                <div class="view-all-service-req">

                                    <div class="service-req-tbl" id="first-datatable-output">
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Asset#</th>
                                                <th>Serial#</th>
                                                <th>Instrument</th>
                                                <th>Location</th>
                                                <th>Pref Contact</th>
                                                <th>Pref Tel</th>
                                                <th>Last cal</th>
                                                <th>Next Due</th>
                                                <th>Due status</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Asset#</th>
                                                <th>Serial#</th>
                                                <th>Instrument</th>
                                                <th>Location</th>
                                                <th>Pref Contact</th>
                                                <th>Pref Tel</th>
                                                <th>Last cal</th>
                                                <th>Next Due</th>
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
        <div id="form-primary" class="modal-container modal-colored-header custom-width modal-effect-9">
            <div class="modal-content" style="padding: 0px!important;">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Choose date range</h3>
                </div>
                <div class="modal-body form">
                    <div class="form-group">
                        <label>Choose date range</label>
                        <div class="form-group" id="value">
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" value="<?php echo e(isset($start) ? $start:''); ?>"
                                       class="form-control datestyle"
                                       placeholder="Start Date" id="startdate" name="start"/>
                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                <input type="text" value="<?php echo e(isset($end) ? $end:''); ?>"
                                       class="form-control datestyle"
                                       placeholder="End Date" id="enddate" name="end"/>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger modal-close">Cancel</button>
                    <button type="button" id="proceed" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>


        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">
        <script src="<?php echo e(asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>"></script>

        <script src="<?php echo e(asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>"></script>


        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>
        <button data-modal="form-primary" style="display:none;" id="clickmodel"
                class="btn btn-space btn-primary md-trigger">Basic Form
        </button>

        <script>

            $(function ($) {
                $('#datepickerDateRange').daterangepicker();

            });
        </script>

        <script>
            var FromEndDate = new Date();
            $('#startdate')
                .datepicker({
                    autoclose: true,
                    endDate: FromEndDate,
                    todayHighlight: true,
                    format: 'dd-M-yyyy',
                });
            $('#enddate')
                .datepicker({
                    autoclose: true,
                    //endDate: FromEndDate,
                    todayHighlight: true,
                    format: 'dd-M-yyyy',
                });
        </script>
        <script>


            var table = $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "<?php echo e(url('admin/duelistdata')); ?>",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                "processing": true,
                initComplete: function () {
                    var dueStatus = $(".dueStatus").val();
                    console.log(dueStatus)
                    var api = this.api();

                    // Apply the search
                    api.columns().every(function () {
                        var that = this;
                        console.log(that)

                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });

                        $('select', this.footer()).on('change', function () {

                            var attr = $(this).attr('attr');
                            if (attr == 'due_status' && this.value == 3) {
                                $('.md-trigger').trigger('click');
                                $('body').on("click", '#proceed', function () {
                                    var startdate = $("#startdate").val();
                                    var enddate = $("#enddate").val();
                                    $('#fromdue').html(startdate)
                                    $('#todue').html(enddate)

                                    $('.daterange').show();

                                    var daterange = startdate + '/' + enddate;
                                    console.log(daterange)
                                    $('.modal-close').trigger('click');
                                    that
                                        .search(daterange)
                                        .draw();


                                })

                            }
                            else {
                                if (that.search() !== this.value) {
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                                $('.daterange').hide();
                            }

                        });


                    });
                }

            });


            $('#listTable tfoot th').each(function (index) {
                if (index != 9 && index != 0 && index != 7 && index != 8) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                if (index == 9) {
                    $(this).html('<?php echo Form::select("due_status",$due_status,'',array('class'=>'dueStatus','attr'=>'due_status')); ?>');
                }

                if (index == 0) {
                    $(this).html('<?php echo Form::select("customer",$customer,'',array('class'=>'','attr'=>'customer')); ?>');
                }
                if (index == 7) {
                    $(this).html('<input type="text" class="datepicker" placeholder="Search Last Date" />');
                }
                if (index == 8) {
                    $(this).html('<input type="text" class="datepicker" placeholder="Search Next Date" />');
                }

            });


        </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>