<?php $__env->startSection('content'); ?>

    <div class="am-content">
        <div class="page-head">

            <h2>Uploaded Instrument Summary</h2><h4>Total Instruments - <?php echo e(count($total)); ?>, Uploaded - <?php echo e(count($sData)); ?>, Not Uploaded - <?php echo e(count($data)); ?></h4>

               
                    
                                                                                                      
                    
                    
                

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
                                        <?php if(count($data)): ?>
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>
                                                <th>Asset No</th>
                                                <th>Serial No</th>
                                                <th>Model</th>
                                                <th>Contact</th>
                                                <th>Location</th>
                                                <th>Service Plan</th>
                                                <th>Reason</th>

                                            </tr>
                                            </thead>

                                               <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                   <tr>
                                                       <td><?php echo e($row['asset_no']); ?></td>
                                                       <td><?php echo e($row['serial_no']); ?></td>
                                                       <td><?php echo e($row['model']); ?></td>
                                                       <td><?php echo e($row['contact']); ?></td>
                                                       <td><?php echo e($row['location']); ?></td>
                                                       <td><?php echo e($row['service_plan']); ?></td>
                                                       <td style="color: red"><?php echo e($row['failure_reason']); ?></td>
                                                   </tr>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


                                        </table>
                                            <?php else: ?>
                                            All Instruments are uploaded
                                        <?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>