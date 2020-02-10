<?php if(Session::has('message')): ?>
    <div role="alert" class="alert alert-success alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="s7-close"></span></button><span class="icon s7-check"></span><?php echo e(Session::get('message')); ?>

    </div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
    <p class="alert <?php echo e(Session::get('alert-class', 'alert-danger')); ?>"><?php echo e(Session::get('error')); ?></p></div>
<?php endif; ?>
<?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <div><?php echo e($error); ?></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


