<?php $__env->startSection('content'); ?>

    <div class="am-content">
        <div class="page-head">
            <h2>View Report</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Calibration</a></li>
                <li><a href="#">Quality Check</a></li>

            </ol>


            <?php if($data['workorder']->admin_review != ''): ?>
                <div class="text-right div-rul">

                    <a href="javascript:void(0);"
                       class="btn btn-space btn-success right"
                    ><i class="fa fa-check"></i>
                        Verified</a>
                </div>

            <?php endif; ?>


        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <iframe id="ifrmaID" src="<?php echo e($data['path']); ?>"
                            style="width:100%;height: 600px;" frameborder="0"></iframe>
                </div>

            </div>

            <div class="panel panel-default"
                 style='text-align: center;position: relative;top: 10px;'>
                <div class="panel-body">
                    <div class="text-center download">


                        <?php if($data['workorder']->admin_review != ''): ?>
                            <a href="javascript:void(0);"
                               class="btn btn-space btn-success right "><i class="fa fa-check"></i>
                                Verified</a>

                        <?php else: ?>



                            <a href="javascript:void(0);"
                               class="btn btn-space btn-primary right md-trigger"

                               data-modal="verify-alert"><i class="fa fa-check"></i>
                                Verify</a>

                        <?php endif; ?>



                        

                    </div>

                </div>

            </div>

        </div>

        <div id="verify-alert" class="modal-container modal-full-color modal-effect-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Alert</h3>
                </div>
                <div class="modal-body text-alert">
                    <div class="text-center">
                        <div class="i-circle success"><i class="icon s7-check"></i></div>
                        <h4>Qc Review</h4>
                        <p>Do you want to review this report?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary btn-shade1 modal-close">No
                    </button>
                    <a href="<?php echo e(url('admin/qcReview/'.$data['workOrderId'])); ?>" data-dismiss="modal"
                       class="btn btn-primary btn-shade1 consolidateQc" data-id="<?php echo e($data['workOrderId']); ?>">Yes</a>
                </div>
            </div>
        </div>


        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo e(asset('css/jquery-confirm.css')); ?>">
        <script src="<?php echo e(asset('js/jquery-confirm.js')); ?>"></script>


        
        
        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #ef6262 !important;
                color: white !important;
            }
        </style>


        <!--     <script>
                $('body').on('click', '#changeTechnician', function () {

                    $('.modalpopUp').trigger('click');
                    $('#modal').modal('toggle');

                });


            </script> -->

        <!--  <script>
         $('body').on('click', '#cancelAlertPopup', function () {
             console.log('hi')

             // $('.AlertPopup').hide();
              $('.modal').modal('dismiss');
             $('.modal2').modal('dismiss');

         });


     </script> -->
        <script>

            $('body').on('click', '.consolidateQc', function () {
                var workOrderId = $(this).attr('data-id');


            });


        </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>