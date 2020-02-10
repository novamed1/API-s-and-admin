<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Asset#</th>
    <th>Serial#</th>
    <th>Model</th>
    <th>Location</th>
    <th>Contact</th>
    <th>Tel#</th>
    <th>Calib status</th>
    <th></th>
    </thead>
    <tbody>
    <?php if($items): ?>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <tr id="<?php echo e($row['request_item_id']); ?>" class="product-list index sub_list_<?php echo e($row['id']); ?>">


        <td class="hidden-phone"><?php echo e($row['assetNumber']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['serialNumber']); ?>


        </td>
         <td class="hidden-phone"><?php echo e($row['modelName']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['location']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['contact']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['tel']); ?>


        </td>

        <?php if($row['status'] == "completed"): ?>
            <td class="hidden-phone"><span class="label label-success">completed</span>

        </td>
        <?php else: ?>
        <td class="hidden-phone"><span class="label label-danger">pending</span>

        </td>
            <td class="hidden-phone"><a href='javascript:void(0)' title="Remove from workorder"
                                        class='remove' item-id="<?php echo e($row['id']); ?>">
                    <i class='fa fa-close'
                       aria-hidden='true'></i></a>

            </td>
         <?php endif; ?>

    </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

    </tbody>

</table>