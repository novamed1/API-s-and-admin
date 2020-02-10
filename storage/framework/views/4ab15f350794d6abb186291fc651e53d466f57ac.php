<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Asset#</th>
    <th>Serial#</th>
    <th>Instrument</th>
    <th>Location</th>
    <th>Pref Contact</th>
    <th>Plan</th>
    
    <th>Status</th>
    </thead>
    <tbody>
    <?php if($items): ?>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <tr id="<?php echo e($row['request_item_id']); ?>" class="product-list index">


        <td class="hidden-phone"><?php echo e($row['assetNo']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['serialNo']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['instrument']); ?>


        </td>
         <td class="hidden-phone"><?php echo e($row['location']); ?>


        </td>
        <td class="hidden-phone"><?php echo e($row['prefContact']); ?>


        </td>
        <td class="hidden-phone">
            <span class="plan_text_<?php echo e($row['request_item_id']); ?>"> <?php echo e($row['plan']); ?></span>
            <?php if($row['status'] != "1"): ?>
            <a href="javascript:void(0)" data-request-item-id="<?php echo e($row['request_item_id']); ?>" plan-id="<?php echo e($row['planId']); ?>" style="float: right;" class="planEdit plan_edit_<?php echo e($row['request_item_id']); ?>"><i class="fa fa-pencil"></i></a>
                <i class="fa fa-spinner fa-spin inside-ico plan_spinner_<?php echo e($row['request_item_id']); ?>" style="display: none;float: right;"></i>

                <?php echo Form::select("plan_select_".$row['request_item_id'],$service_plans,$row['planId'],array('data-request-item-id'=>$row['request_item_id'],'style'=>"display:none",'class'=>'plan_change plan_change_'.$row['request_item_id'])); ?>


                <a href="javascript:void(0)" style="display: none;float: right;color: #EF6262" data-request-item-id="<?php echo e($row['request_item_id']); ?>" plan-id="<?php echo e($row['planId']); ?>" style="float: right;" class="planClose plan_close_<?php echo e($row['request_item_id']); ?>"><i class="fa fa-close"></i></a>
            <?php endif; ?>
        </td>
        

        


        <?php if($row['status'] == "1"): ?>
            <td class="hidden-phone"><span class="label label-success">assigned</span>

        </td>
        <?php else: ?>
        <td class="hidden-phone"><span class="label label-danger">unassigned</span>

        </td>
         <?php endif; ?>

    </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

    </tbody>

</table>

