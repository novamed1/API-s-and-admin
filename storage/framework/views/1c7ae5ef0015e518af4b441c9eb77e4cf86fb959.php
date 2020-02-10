<?php $__env->startSection('content'); ?>

    <style>
        .btn
        {
            display: initial;
            padding: 2px 10px;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>Order Requests</h2>

            <ol class="breadcrumb">

                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Order & Payment Management</a></li>
                <li><a href="#">Order Requests</a></li>

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
                                                <th>Customer name</th>
                                                <th>Order number</th>
                                                <th>Request number</th>
                                                <th>Total items</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Invoice status</th>
                                                <th>Payment</th>
                                                <th></th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Customer name</th>
                                                <th>Order number</th>
                                                <th>Request number</th>
                                                <th>Total items</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Invoice status</th>
                                                <th>Payment</th>
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


        <a style="display:none;" data-toggle="modal" data-target="#form-bp1"
           class="modalpopUp" data-icon="&#xe0be;" id="paypop" data-keyboard="false"
           data-backdrop="static"></a>


        <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title">Payment</h3>
                    </div>

                    <form id="addPay" method="post" data-parsley-validate="" novalidate="">

                        <div class="modal-body form">


                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-18">
                                    <div class="form-group">
                                        <label>Payment mode</label>
                                        <?php
                                        $mode = array('1' => 'offline', '2' => 'online')
                                        ?>

                                        <?php echo Form::select("mode",$mode,'',array('class'=>'form-control','required'=>'required','id'=>'mode')); ?>


                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-18">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <?php echo Form::text('amount','', array( 'placeholder' => 'Enter the device number','class'=>'form-control','id'=>'payAmount','readonly')); ?>

                                    </div>
                                </div>
                            </div>
                            <div id="extraFields">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <?php echo Form::text('bankname','', array( 'placeholder' => 'Enter Bank Name','class'=>'form-control','id'=>'payBank')); ?>

                                            <span style="display: none"
                                                  class="label label-danger">Bank name is required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <?php echo Form::text('accountno','', array( 'placeholder' => 'Enter Account Number','class'=>'form-control','id'=>'payAccount')); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">
                                            <label>Check  Number</label>
                                            <?php echo Form::text('chequeno','', array( 'placeholder' => 'Enter Cheque Number','class'=>'form-control','id'=>'payCheque')); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-18">
                                        <div class="form-group">
                                            <label></label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="order_id" id="order_id">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal"
                                    class="btn btn-default md-close">
                                Cancel
                            </button>

                            <button type="button" id="submitpay"
                                    class="btn btn-primary">
                                Proceed
                            </button>
                            <div style="display: none;" id="loadSpinner"><i class="fa fa-spinner fa-spin"></i></div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
        <div id="colored-warning"
             class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Warning</h3>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                        <h4>Warning!</h4>
                        <p id="warningMsg"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
                </div>
            </div>
        </div>


        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/datatable.min.css')); ?>" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">




        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>


        <script>
            $('#listTable tfoot th').each(function(index) {
                if(index!=8 && index!=6 && index!=7)
                {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                if(index==6)
                {
                    $(this).html('<?php echo Form::select("invoice_status",$invoice_status,'',array('class'=>'')); ?>');
                }
                if(index==7)
                {
                    $(this).html('<?php echo Form::select("payment_status",$payment_status,'',array('class'=>'')); ?>');
                }


            });

            $('#listTable').DataTable( {
                "bServerSide": true,
                "sAjaxSource": "<?php echo e(url('admin/orderrequestslistdata')); ?>",
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

                        $('select', this.footer()).on('change', function() {
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
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url('admin/addPay')); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        hide_animation();
                        if (json.result) {

                            hide_animation();
                            $('.close').trigger('click');
                            $('#submitpay').show();
                            $('#loadSpinner').hide();
                            $('#pay' + order_id).empty();
                            $('#pay' + order_id).html('<span class="label label-success"><?php echo e("Paid"); ?></span>');
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>