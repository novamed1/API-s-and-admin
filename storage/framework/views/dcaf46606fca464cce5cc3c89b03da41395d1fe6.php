
<?php $__env->startSection('content'); ?>

    <div class="am-content">
        <div class="page-head">

            <h2>Users</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">User Management</a></li>
                <li><a href="#">Users List</a></li>

                <!--                    <li class="active"></li>-->

            </ol>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/adduser')); ?>" class="btn btn-space btn-primary">Create User</a>
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
                                                <th>First Name</th>
                                                <th>Email Id</th>
                                                <th>Role</th>
                                                <th>Action</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Email Id</th>
                                                <th>Role</th>
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


        <script>
            $('#listTable tfoot th').each(function (index) {
                if (index != 3) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }


            });

            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "<?php echo e(url('admin/userlistdata')); ?>",
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>