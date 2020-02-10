<div class="modal-body service-details-modal" style="padding: 0px!important;">
    
    <table class="table table-striped" id="tblGrid">
        <thead id="tblHead">
        <tr>
            <th>Volume</th>
            <th>Channel</th>
            <th>Operation</th>
            <th>Point</th>
            <th>Price($)</th>
        </tr>
        </thead>
        <tbody>
        <?php if($data): ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <tr>
                    <td><?php echo e($value['volume']); ?></td>
                    <td><?php echo e($value['channel']); ?></td>
                    <td><?php echo e($value['operation']); ?></td>
                    <td><?php echo e($value['point']); ?></td>
                    <td>$<span style="margin-left:2px;"></span><?php echo e($value['price']); ?></td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

        </tbody>
    </table>

</div>

<style type="text/css">

    .service-details-modal p.mod-service-img {
        position: absolute;
        padding: 0;
    }

    .service-details-modal h5 {

        font-size: 18px;
        margin-bottom: 5px;
    }

    .service-details-modal p {
        margin-bottom: 10px;
    }

</style>