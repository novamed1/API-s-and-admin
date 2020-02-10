<?php $__env->startSection('content'); ?>

    <div class="am-content gridLayout">
        <div class="page-head">

            <h2>Customer's Instrument List</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Instrument Management</a></li>
                <li><a href="#">Customer's Instrument List </a></li>

            </ol>


            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/addview')); ?>" class="btn btn-space btn-primary">Create Instrument</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row">

                <div class="panel panel-default">


                    <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-5" style="float: right;margin-bottom: -15px;">
                                <label>*Choose Customer</label>
                                <div class="form-group">

                                    <?php echo Form::select("due_status",$customer,'',array('class'=>'customerList form-control','attr'=>'due_status')); ?>

                                </div>


                            </div>


                        </div>

                    </div>


                </div>
            </div>


            <div class="view-all-service-req">

                <div class="flash-message">
                    <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>


                <div class="service-req-tbl" id="first-datatable-output">
                    <table class="table" id="listTable">
                        <thead>
                        <tr>
                            <th style="width: 3%">S.No</th>
                            <th style="width: 9%">Asset Number</th>
                            <th style="width: 9%">Serial Number</th>
                            <th style="width: 20%">Model Name</th>
                            <th style="width: 11%">Location</th>
                            <th style="width: 9%">Pref Contact</th>
                            <th style="width: 9%">Pref Tel</th>
                            <th style="width: 12%">Pref Email</th>
                            <th style="width: 3%">Edit</th>
                            <th style="text-align: center;width: 5%">Delete</th>
                        </tr>

                        </thead>


                        
                        

                        
                        
                        
                        
                        
                        

                        
                        

                        <tfoot>
                        <tr>

                            <th></th>
                            <th>Asset Number</th>
                            <th>Serial Number</th>
                            <th>Model Name</th>
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
            "sAjaxSource": "<?php echo e(url('admin/viewlistdata')); ?>",
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
            var new_url = "<?php echo e(url('admin/viewlistdata')); ?>/" + filter_value;
            listTable.ajax.url(new_url).load();
        });


    </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>