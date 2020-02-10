

<table class="table table-condensed table-striped table-fw-widget" id="" style="width: 100%">

    <thead>
    <tr>
        <th></th>
        <th>S no</th>
        <th>Pdf Date</th>
        <th>Total Items</th>

        <th></th>

    </tr>
    </thead>

    <tbody>
    <?php if(count($details)): ?>
        <?php $i=1; ?>
        <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <tr>
                <td>
                    <a class="acc-ico accordion-toggle subList subList<?=$row['id']?>" data-toggle = "collapse" data-parent = "#accordion" data-target = "#collapse<?=$row['id']?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>
                </td>
                <td><?php echo e($i); ?></td>
                <td><?php echo e($row['created_date']); ?></td>
                <td><?php echo e(count($row['reports_items'])); ?></td>
                
                        
                            
                                
                                    
                                

                            
                        

                    

                <td><a href="javascript:void(0);" data-attr="<?php echo e($row['id']); ?>" class="sendMailToCustomer"><i class="fa fa-envelope"></i> </a> </td>

    <tbody id="collapse<?=$row['id']?>" class = "panel-collapse collapse inside-tr" style="background: #e0e0e0;">
    <tr class="table table-condensed table-striped table-fw-widget" style="font-size: 12px;">
        <th></th>
        <th>Model</th>
        <th>Asset</th>
        <th>Serial</th>

    </tr>
    <?php if($row['reports_items']): ?>
        <?php $__currentLoopData = $row['reports_items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1=>$row1): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <tr style="font-size: 10px;">
                <td></td>
                <td><?php echo e($row['reports_items'][$key1]->model_description); ?></td>
                <td><?php echo e($row['reports_items'][$key1]->asset_no); ?></td>
                <td><?php echo e($row['reports_items'][$key1]->serial_no); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>
    </tbody>
            </tr>
            <?php $i++; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    <?php endif; ?>
    </tbody>


</table>