<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Description</th>
    <th>μl</th>
    <th>Accuracy %</th>
    <th>Accuracy μl</th>
    <th>Precision %</th>
    <th>Precision μl</th>
    <th></th>
    </thead>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tol): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <?php if($tol->target_value): ?>
        <tr>
            <td><?php echo e($tol->description); ?></td>
            <td class="edit_<?php echo e($tol->id); ?>" attr="target"><?php echo e($tol->target_value); ?></td>
            <td class="edit_<?php echo e($tol->id); ?>" attr="accuracy"><?php echo e($tol->accuracy); ?></td>
            <td class="edit_<?php echo e($tol->id); ?>" attr="accuracy_ul"><?php echo e($tol->accuracy_ul); ?></td>
            <td class="edit_<?php echo e($tol->id); ?>" attr="precision"><?php echo e($tol->precision); ?></td>
            <td class="edit_<?php echo e($tol->id); ?>" attr="precesion_ul"><?php echo e($tol->precesion_ul); ?></td>
            <td>
                <a href="javascript:void(0);" class="toleranceEdit" id="edittol_<?php echo e($tol->id); ?>" data-attr="<?php echo e($tol->id); ?>"><i class="fa fa-pencil"></i></a>
                <a href="javascript:void(0);" class="toleranceSave" id="savetol_<?php echo e($tol->id); ?>" style="display:none;color: #3CB371;" data-attr="<?php echo e($tol->id); ?>"><i class="fa fa-check inside-ico"></i></a>
                <i class="fa fa-spinner fa-spin inside-ico" id="spinner_<?php echo e($tol->id); ?>" style="display:none;"></i>
            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</table>