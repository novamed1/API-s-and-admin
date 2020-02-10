
<?php $__env->startSection('content'); ?>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e92a2a;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            /*left: 4px;*/
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: green;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .notVerify {
            color: red;
        }

        .Verify {
            color: green;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Customer List</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Management</a></li>
                <li><a href="#">Customers List</a></li>

                <!--                    <li class="active"></li>-->

            </ol>
            <?php if($postvalue): ?>
                <?php if($posttestplanid): ?>
                    <?php $msg = 'Customer has been updated'; ?>
                <?php else: ?>
                    <?php $msg = 'Customer has been created'; ?>
                <?php endif; ?>
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                      class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span><?php echo e($msg); ?>

                </div>
            <?php endif; ?>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/addcustomer')); ?>" class="btn btn-space btn-primary">Create Customer</a>
            </div>
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
                                                <th>Name</th>
                                                <th>Customer ID</th>
                                                <th>Customer type</th>
                                                <th>Email</th>
                                                <th>Verified by Customer</th>
                                                <th>Access to Portal</th>
                                                <th>Telephone</th>
                                                
                                                
                                                <th>State</th>
                                                <th>City</th>
                                                
                                                <th>View</th>
                                                <th>Edit</th>
                                                <th>Customer Setup</th>
                                                <th>Proxy Login</th>
                                                <th>Delete</th>


                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Customer ID</th>
                                                <th>Customer type</th>
                                                <th>Email</th>
                                                <th>Verified by Customer</th>
                                                <th>Access to Portal</th>
                                                <th>Telephone</th>
                                                
                                                

                                                <th>State</th>
                                                <th>City</th>
                                                
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


        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/datatable.min.css')); ?>" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">


        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo e(asset('css/jquery-confirm.css')); ?>">
        <script src="<?php echo e(asset('js/jquery-confirm.js')); ?>"></script>

        <script>

            $(function ($) {
                $('body').on('change', '#email_verify', function () {
                    var email_verify_id = $(this).attr('data-id');
                    $.confirm({
                        title: "Email Verification",
                        text: "Do you want to Verify this Email ID ?",
                        confirm: function () {
                            $.ajax({
                                type: 'get',
                                url: "<?php echo e(url("admin/emailverify")); ?>",
                                data: {
                                    "_token": "<?php echo csrf_token(); ?>",
                                    customerId: email_verify_id,
                                },
                                dataType: "json",
                                success: function (res) {
                                    console.log(res);
                                    if (res.result == true) {
                                        $('#verify_' + email_verify_id).removeClass('notVerify');
                                        $('#verify_' + email_verify_id).addClass('Verify');
                                        $('#switch_' + email_verify_id).hide();
                                    }
                                }
                            });
                        },
                        cancel: function () {

                          $('.email_verify').prop("checked", false);
                          // $("#myCheck"). prop("checked", false);
                        },
                        confirmButton: "Yes",
                        cancelButton: "No"
                    });
                });
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            });

            $(function ($) {
                $('body').on('change', '#access_portal', function () {
                    var email_verify_id = $(this).attr('data-id');
                    $.confirm({
                        title: "Customer Portal Confirmation",
                        text: "Do you want to give portal access to the customer ?",
                        confirm: function () {
                            $.ajax({
                                type: 'post',
                                url: "<?php echo e(url("admin/portalAccess")); ?>",
                                data: {
                                    "_token": "<?php echo csrf_token(); ?>",
                                    customerId: email_verify_id,
                                },
                                dataType: "json",
                                success: function (res) {
                                    console.log(res);
                                    if (res.result == true) {

                                        $('#accessportal_' + email_verify_id).html('Yes');
                                    }
                                }
                            });
                        },
                        cancel: function () {

                            $('.access_portal').prop("checked", false);
                            // $("#myCheck"). prop("checked", false);
                        },
                        confirmButton: "Yes",
                        cancelButton: "No"
                    });
                });
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            });

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
                if (index != 9 && index != 10 && index != 11 && index != 12 && index != 13 && index != 14) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }


            });

            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "<?php echo e(url('admin/customerlistdata')); ?>",
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
        
        
        
        <a data-modal="full-info" class="btn btn-space btn-info md-trigger popUp" style="display: none;"></a>
        <div id="full-info" class="modal-container modal-colored-header modal-effect-1">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Customer Details</h3>
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
                    url: "<?php echo e(url("admin/getCustomerInfo")); ?>",
                    data: {
                        "_token": "<?php echo csrf_token(); ?>",
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>