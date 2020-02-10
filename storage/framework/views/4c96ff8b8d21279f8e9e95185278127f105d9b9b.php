<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <th>Asset#</th>
    <th>Serial#</th>
    <th>Instrument</th>
    <th>Location</th>
    <th>Pref Contact</th>
    <th>Reviewed Technician</th>
    <th>Review PDF</th>
    </thead>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tol): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <tr>
            <td><?php echo e($tol['assetNumber']); ?></td>
            <td><?php echo e($tol['serialNumber']); ?></td>
            <td><?php echo e($tol['instrumentModel']); ?></td>
            <td><?php echo e($tol['location']); ?></td>
            <td><?php echo e($tol['preferredContact']); ?></td>
            <td><?php echo e($tol['reviewdTechnician']); ?></td>

            <td id="changedReview<?php echo e($tol['workOrderItemId']); ?>">

                <?php if($tol['report']): ?>


                    <a href="<?php echo e(url('admin/qcItemReview/'.$tol['workOrderItemId'])); ?>"
                       class=""
                       id="viewDetails"> <i
                                class="fa fa-search review"></i></a>
                    <?php else: ?>

                    Report not generated

                <?php endif; ?>

            </td>

        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</table>

<a style="display:none;" data-toggle="modal" data-target="#singlereportUpload"
   class="modalSinglepopUpreportUpload" data-icon="&#xe0be;" data-keyboard="false"
   data-backdrop="static"></a>

<div id="singlereportUpload" tabindex="-1" role="dialog" class="modal fade modal-colored-header">
    <div class="modal-dialog custom-width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Upload Report</h3>
            </div>
            <div class="modal-body form">
                <div class="form-group">
                    <label>Calibration Report</label>
                    <input type="file" class="form-control" id="calibrationSingleReportFile">
                    <span style="color:red;" id="caliReportError"></span>
                    <span>(File should be in pdf format)</span>
                </div>

                
                    
                
            </div>
            <input type="hidden" id="calibrationReportWorkorderItemId">
            <div class="modal-footer">
                <i class="fa fa-spinner fa-spin" aria-hidden="true" id="calibrationSingleReportSubmitLoader" style="display: none"></i>
                <button type="button" data-dismiss="modal" class="btn btn-default md-close">Cancel</button>
                <button type="button"  class="btn btn-primary" id="calibrationSingleReportSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>