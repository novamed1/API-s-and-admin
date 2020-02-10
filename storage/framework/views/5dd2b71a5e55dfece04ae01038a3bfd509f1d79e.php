


<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th></th>
    <th>Model</th>
    <th>Asset Number</th>
    <th>Serial Number</th>

    </thead>
    <?php if($details['details']): ?>
        <?php $__currentLoopData = $details['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teckkey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <tr>
            <td><div class="am-checkbox">
                    <input id="check-<?php echo e($teckkey['workOrderItemId']); ?>" type="checkbox" class="messageCheckbox"
                           name="workorderitems"
                           data-attr="" value="<?php echo e($teckkey['workOrderItemId']); ?>"/>
                    <label for="check-<?php echo e($teckkey['workOrderItemId']); ?>"></label>
                </div></td>
            <td><?php echo e($teckkey['instrumentModel']); ?></td>
            <td><?php echo e($teckkey['asset_no']); ?></td>
            <td><?php echo e($teckkey['serial_no']); ?></td>
            </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>
</table>


<input type="hidden" value="<?php echo e($details['workOrderId']); ?>" id="work_order_id_value">
<div class="modal-footer">
    <?php if($details['details']): ?>
    <a href="javascript:void(0);" class="btn btn-primary right" id="updateCustomerDetailClick">OK,Generate
        PDF</a>
        <i class="fa fa-spinner fa-spin pdfSpinner" style="display: none"></i>
        <?php else: ?>
        <button type="button" data-dismiss="modal" aria-hidden="true"
                class="close md-close">Close</button>
    <?php endif; ?>
    <span style="display: none" id="spinLoader"><i
                class="fa fa-spinner fa-spin inside-ico"></i></span>
</div>