<?php $__env->startSection('content'); ?>

    <style>
        #dt_basic tr th{
            font-size: 13px;
        }
        #dt_basic tr td{
            font-size: 12px;
        }
        .inside-ico
        {
            font-size: 20px;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>ISO Limits</h2>

            <ol class="breadcrumb">

                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Master Setup</a></li>
                <li><a href="#">ISO Limits</a></li>

            </ol>
            <?php if($postvalue): ?>
                <?php if($postisoid): ?>
                    <?php $msg = 'ISO Specification has been updated'; ?>
                <?php else: ?>
                    <?php $msg = 'ISO Specification has been created'; ?>
                <?php endif; ?>
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                      class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span><?php echo e($msg); ?>

                </div>
            <?php endif; ?>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/isospecification')); ?>" class="btn btn-space btn-primary">Create ISO Limits</a>
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
                                <div class="col-md-9 col-sm-9" id="Result" style='display:none;text-align: center;'>

                                    <span>No result found</span>
                                </div>
                                    <div class="view-all-service-req">

                                        <div class="service-req-tbl" id="first-datatable-output">
                                            <table class="table table-bordere table-striped display" id="listTable">

                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Channel</th>
                                                    <th>Operation</th>
                                                    <th>Volume</th>
                                                    <th>Values</th>
                                                    <th>Action</th>


                                                </tr>
                                                </thead>

                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Channel</th>
                                                    <th>Operation</th>
                                                    <th>Volume</th>
                                                    <th>Values</th>



                                                </tr>
                                                </tfoot>




                                            </table>

                                        </div>
                                    </div>
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
        function format (d) {
            console.log(d);
            return d;
        }


        $('#listTable tfoot th').each(function(index) {
            if(index!=0)
            {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            }

        });

        var table = $('#listTable').DataTable( {
            "bServerSide": true,
            "sAjaxSource": "<?php echo e(url('admin/isospeceficationlistdata')); ?>",
            "sServerMethod": "POST",
            "iDisplayLength":10,
            "searching": true,
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "aaData":           0,
                    "defaultContent": ''
                },
                { "aaData": "1" },
                { "aaData": "2" },
                { "aaData": "3" },
                { "aaData": "4" },
                { "aaData": "5" }
            ],
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
                });
            }

        } );

        $('#listTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var id =  $(this).find('span').attr('data-id');

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                var datastring = {id:id,"_token": "<?php echo csrf_token(); ?>"};
                $.ajax({
                    type: 'post',
                    url: "<?php echo e(url("admin/isosublists")); ?>",
                    data: datastring,
                    dataType: "json",
                    success: function (json) {
                        if (json) {
                            if (json.result) {
                                row.child( format(json.data) ).show();
                                tr.addClass('shown');
                            }

                        }
                    }
                });

            }
        } );



    </script>

    <script>
        $('body').on('click', '.toleranceEdit', function () {
            $(this).hide();
            var id = $(this).attr('data-attr');
            $('.edit_'+id).each(function(){
                var content = $(this).html();
                var name = $(this).attr('attr');
                $(this).html('<input type="text" attr='+name+' id="tol_'+name+'_'+id+'" class="form-control text_value_'+id+'" value='+content+'>');
            });

            $('#savetol_'+id).show();
        });

        $('body').on('click', '.toleranceSave', function () {
            var id = $(this).attr('data-attr');
            var target = $('#tol_target_'+id).val();
            var accuracy = $('#tol_accuracy_'+id).val();
            var precision = $('#tol_precision_'+id).val();
            var accuracy_ul = $('#tol_accuracy_ul_'+id).val();
            var precesion_ul = $('#tol_precesion_ul_'+id).val();
            var datastring = {target:target,accuracy:accuracy,precision:precision,accuracy_ul:accuracy_ul,precesion_ul:precesion_ul,id:id,"_token": "<?php echo csrf_token(); ?>"};
           $('#savetol_'+id).hide();
           $('#spinner_'+id).show();
            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/saveajaxtolerance")); ?>",
                data: datastring,
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result) {
                            $('.edit_'+id).each(function(){
                                var attr = $(this).attr('attr');
                                var content = $('#tol_'+attr+'_'+id).val();
                                $(this).html(content);
                            });

                            $('#spinner_'+id).hide();
                            $('#edittol_'+id).show();
                            if(data.updated)
                            {
                                $.toast({
                                    heading: 'Updated',
                                    text: data.message,
                                    //position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'success',

                                    loader: false
                                });
                            }

                        }

                    }
                }

            });

        });

        $('body').on('click', '.fa-plus-circle', function () {

           var thisattr = $(this).attr('data-attr');
            $(this).removeClass('fa-plus-circle');
            $(this).addClass('fa-minus-circle');

            $('.fa-minus-circle').each(function(){
                if(thisattr == $(this).attr('data-attr'))
                {
                    $(this).removeClass('fa-plus-circle');
                    $(this).addClass('fa-minus-circle');
                }
                else
                {
                    $(this).addClass('fa-plus-circle');
                    $(this).removeClass('fa-minus-circle');
                }

            });

        });

        $('body').on('click', '.fa-minus-circle', function () {
            $(this).removeClass('fa-minus-circle');
            $(this).addClass('fa-plus-circle');

        });

        $( document ).ready(function() {
            var $myGroup = $('#tagcollapse');
            $myGroup.on('show.bs.collapse','.collapse', function() {
                $myGroup.find('.collapse.in').collapse('hide');
            });
        });



    </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>