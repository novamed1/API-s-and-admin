<div class="modal-body form" style="padding-top: 10px;">

    <div class="form-group"><br>
        <label>Technician List</label>


        <?php if($technician): ?>
            <?php $__currentLoopData = $technician; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teckkey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <?php if(in_array($teckkey->id, $alreadyTech)): ?>

                    <?php ($chk1 = 'checked=checked'); ?>

                <?php else: ?>
                    <?php ($chk1 = '0'); ?>

                <?php endif; ?>
                <div class="am-checkbox">
                    <input id="check-<?php echo e($teckkey->id); ?>" type="checkbox" name="tecchnicians"
                           data-attr="" value="<?php echo e($teckkey->id); ?>"/<?php echo e($chk1); ?>>
                    <label for="check-<?php echo e($teckkey->id); ?>"> <?php echo e($teckkey->first_name); ?></label>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

    </div>
    <?php if(count($technician)): ?>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal"
                data-target="#form-bp1"
                class="btn btn-default md-close">
            Cancel
        </button>

        <button type="button" id="submitTech" data-dismiss="modal"
                class="btn btn-primary">
            Proceed
        </button>
    </div>
        <?php endif; ?>

</div>