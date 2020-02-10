<?php $__env->startSection('content'); ?>

    <div class="am-content">
        <div class="page-head">

            <h2>Uploaded Instrument Model Summary</h2><h4>Total Instrument Model - <?php echo e(count($total)); ?>, Uploaded - <?php echo e(count($sData)); ?></h4>

               
                    
                                                                                                      
                    
                    
                

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
                                                <th>Model Name</th>
                                                <th>Manufacturer</th>
                                                <th>Brand</th>
                                                <th>Operation</th>
                                                <th>Channel Type</th>
                                                <th>Volume Type</th>
                                                <th>Volume From</th>
                                                <th>Volume To</th>
                                                <th>Price</th>

                                            </tr>
                                            </thead>
                                           <?php if(count($data)): ?>
                                               <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                   <tr>
                                                       <td><?php echo e($row['model_name']); ?></td>
                                                       <td><?php echo e($row['manufacturer']); ?></td>
                                                       <td><?php echo e($row['brand']); ?></td>
                                                       <td><?php echo e($row['operation']); ?></td>
                                                       <td><?php echo e($row['channel_type']); ?></td>
                                                       <td><?php echo e($row['volume_type']); ?></td>
                                                       <td><?php echo e($row['volume_from']); ?></td>
                                                       <td><?php echo e($row['volume_to']); ?></td>
                                                       <td><?php echo e($row['price']); ?></td>
                                                       <td style="color: red"><?php echo e($row['failure_reason']); ?></td>
                                                   </tr>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                               <?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>