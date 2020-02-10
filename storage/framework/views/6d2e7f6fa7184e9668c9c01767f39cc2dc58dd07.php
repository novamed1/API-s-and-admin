<table class="table table-bordere" id="">
    <thead>
    <th width="5%"></th>
    <th width="10%">Name</th>
    <th width="10%">Telephone</th>
    <th width="10%">Email</th>
    <th width="20%">Address1</th>
    <th width="20%">Address2</th>
    <th width="10%">City</th>
    <th width="10%">State</th>
    <th width="5%">Zipcode</th>
    <th></th>
    </thead>
    <tbody>
    <?php if($data['customer_properties']): ?>
        <?php $__currentLoopData = $data['customer_properties']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

            <?php if($data['curr_id']==$row['id']): ?>
                <?php $checked='checked' ?>
                <?php else: ?>
                <?php $checked=''; ?>
                <?php endif; ?>
    <tr id="<?php echo e($row['id']); ?>" class="datavalues">
        <td class="">
            <div class="am-checkbox">
                <input type="checkbox" <?php echo e($checked); ?> name="property" id="valuefor<?php echo e($row['id']); ?>" value="<?php echo e($row['id']); ?>">
                <label for="valuefor<?php echo e($row['id']); ?>"></label>
            </div>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="name"><?php echo e($row['name']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="phone"><?php echo e($row['phone']); ?>

        </td>
         <td class="edit_<?php echo e($row['id']); ?>" attr="email"><?php echo e($row['email']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="address1"><?php echo e($row['address1']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="address2"><?php echo e($row['address2']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="city"><?php echo e($row['city']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="state"><?php echo e($row['state']); ?>

        </td>
        <td class="edit_<?php echo e($row['id']); ?>" attr="zip"><?php echo e($row['zip']); ?>

       </td>

        <td class="">

            <a href="javascript:void(0);" data-id="<?php echo e($row['id']); ?>" id="editpropertylist_<?php echo e($row['id']); ?>" class="propertyDataEdit"><i class="fa fa-pencil"></i> </a>
            <a href="javascript:void(0);" class="propertyDataSave" id="saveproperty_<?php echo e($row['id']); ?>" style="display:none;color: #3CB371;" data-attr="<?php echo e($row['id']); ?>"><i class="fa fa-check inside-ico"></i></a>
            <i class="fa fa-spinner fa-spin inside-ico" id="spinner_<?php echo e($row['id']); ?>" style="display:none;"></i>
        </td>
    </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

    </tbody>

</table>



