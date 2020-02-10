<?php $__env->startSection('content'); ?>
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
        .ico-size
        {
            font-size: 14px;
        }

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Service Requests</h2>

            <ol class="breadcrumb">

                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Services & Workorders</a></li>
                <li><a href="#">Service Requests</a></li>


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
                                                <th></th>
                                                <th>Request Number</th>
                                                <th>Customer Name</th>
                                                <th>Customer Id</th>
                                                <th>Total Instruments</th>
                                                <th>Service Shipping Date</th>
                                                <th>Requested Date</th>
                                                <th>View Detail</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>Request Number</th>
                                                <th>Customer Name</th>
                                                <th>Customer Id</th>
                                                <th>Total Instruments/th>
                                                <th>Service Shipping Date</th>
                                                <th>Requested Date</th>
                                                <th></th>

                                            </tr>
                                            </tfoot>


                                        </table>

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


        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
        <!-- Editable: -->

        <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('js/bootstrap-editable.js')); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/datatable.min.css')); ?>" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">


        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>


        <script>
            $('#listTable tfoot th').each(function (index) {
                if (index != 0 && index != 7) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }


            });

            var table = $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "<?php echo e(url('admin/servicerequestlistdata')); ?>",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                "processing": true,
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
                    {"aaData": "7"}
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
                    var datastring = {id: id, "_token": "<?php echo csrf_token(); ?>"};
                    $.ajax({
                        type: 'post',
                        url: "<?php echo e(url("admin/servicerequestsublist")); ?>",
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


        </script>


        <script>
            function format(d) {
                console.log(d);
                return d;
            }

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

            // $( document ).ready(function() {
            //     var $myGroup = $('#tagcollapse');
            //     $myGroup.on('show.bs.collapse','.collapse', function() {
            //         $myGroup.find('.collapse.in').collapse('hide');
            //     });
            // });
        </script>

        <script>
                    $('body').on('click', '.planEdit', function () {
                        var request_item_id = $(this).attr('data-request-item-id');
                        var plan_id = $(this).attr('plan-id');
                        $('.plan_edit_'+request_item_id).hide();
                        $('.plan_text_'+request_item_id).hide();
                        $('.plan_change_'+request_item_id).show('slide');
                        $('.plan_close_'+request_item_id).show();
                        //$('.plan_spinner_'+request_item_id).show();

                });

                    $('body').on('click', '.planClose', function () {
                        var request_item_id = $(this).attr('data-request-item-id');
                        var plan_id = $(this).attr('plan-id');
                        $('.plan_text_'+request_item_id).show('slide');
                        $('.plan_edit_'+request_item_id).show();
                        $('.plan_change_'+request_item_id).hide();

                        $('.plan_close_'+request_item_id).hide();
                        //$('.plan_spinner_'+request_item_id).show();

                    });

                    $('body').on('change', '.plan_change', function () {
                        var request_item_id = $(this).attr('data-request-item-id');
                        var plan_id = $(this).val();
                        var plan = $(this).find("option:selected").text();
                        $('.plan_spinner_'+request_item_id).show();
                        $('.plan_close_'+request_item_id).hide();
                        $('.plan_edit_'+request_item_id).hide();
                        $.ajax({
                            type: 'post',
                            url: "<?php echo e(url("admin/requestItemsPlanEdit")); ?>",
                            data: {request_item_id:request_item_id,plan_id:plan_id,"_token": "<?php echo csrf_token(); ?>"},
                            dataType: "json",
                            success: function (json) {
                                if (json.result) {
                                    setTimeout(function(){
                                        $('.plan_change_'+request_item_id).hide();
                                        $('.plan_spinner_'+request_item_id).hide();
                                        $('.plan_edit_'+request_item_id).show();
                                        $('.plan_close_'+request_item_id).hide();
                                        $('.plan_text_'+request_item_id).text(plan);
                                        $('.plan_text_'+request_item_id).show('slide');
                                    }, 1000);
                                               }
                            }
                        });

                    });

        </script>

        <script type="text/html" id="workOrderItemDetails">
            <%
            _.each(data, function(referredbymembers , index) { %>
            <tr id="<%= referredbymembers['request_item_id'] %>" class="product-list index">


        <td class="hidden-phone"><%=referredbymembers['assetNumber']%>

        </td>
        <td class="hidden-phone"><%= referredbymembers['serialNumber'] %>

        </td>
         <td class="hidden-phone"><%= referredbymembers['modelName'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['location'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['contact'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['tel'] %>

        </td>

        <% if (referredbymembers['status'] == "completed") { %>
            <td class="hidden-phone"><span class="label label-success">completed</span>

        </td>
        <% } else { %>
        <td class="hidden-phone"><span class="label label-danger">pending</span>

        </td>
         <% }%>

    </tr>
    <%
    
    }); %>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>