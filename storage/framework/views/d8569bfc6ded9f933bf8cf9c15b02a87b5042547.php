<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Model Name</th>
    <th>Pref Contact</th>
    <th>Asset Number</th>
    <th>Serial Number</th>
    <th>Reviewed Technician</th>
    <th>Review PDF</th>
    </thead>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tol): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <tr>
            <td><?php echo e($tol['instrumentModel']); ?></td>
            <td><?php echo e($tol['preferredContact']); ?></td>
            <td><?php echo e($tol['assetNumber']); ?></td>
            <td><?php echo e($tol['serialNumber']); ?></td>
            <td><?php echo e($tol['reviewdTechnician']); ?></td>

            <td>

                <?php if($tol['report']): ?>


                    <a href="<?php echo e(url('admin/qcItemReview/'.$tol['workOrderItemId'])); ?>"
                       class=""
                       id="viewDetails"> <i
                                class="fa fa-search review"></i></a>


                <?php endif; ?>

            </td>

        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</table>