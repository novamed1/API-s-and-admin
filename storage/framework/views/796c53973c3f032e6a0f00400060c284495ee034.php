<div class="main-content">
    <form action="#" id="updatedPaymentForm"
          class="form-horizontal group-border-dashed" method="post"
          data-parsley-validate>


        <input type="hidden" name="id" value="<?php echo e($details['details']['customer_id']); ?>">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">

                <div class="panel panel-default">
                    <div class="error">
                    </div>


                    <div class="panel-body">
                        

                        

                        <h5 class="heading" style="margin-bottom: 22px;">Mode Of Payment</h5>

                        <div class="col-lg-3">


                            <?php if($details['paymethods']): ?>
                                <?php $__currentLoopData = $details['paymethods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paykey=>$payval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>


                                    <?php if($payval->id == $details['details']['pay_method']): ?>

                                        <?php ($chk1 = 'checked=checked'); ?>
                                        

                                    <?php else: ?>
                                        <?php ($chk1 = '0'); ?>
                                        

                                    <?php endif; ?>

                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="radio" name="payMethod"
                                                   class="<?php echo e($payval->name); ?>"
                                                   id="pay-<?php echo e($payval->id); ?>"
                                                   value="<?php echo e($payval->id); ?>" <?php echo e($chk1); ?>>
                                            <label for="pay-<?php echo e($payval->id); ?>"><?php echo e($payval->name); ?></label>
                                        </div>
                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>
                        </div>


                        <div class="form-group" style="margin-top: 134px;">
                            <label style="font-size: 15px">Payment Terms:</label>
                            <?php echo Form::textarea('paymentTerms',$details['details']['pay_terms'], array('class'=>'form-control','id'=>'paymentTerms','cols'=>30,'rows'=>'5','required'=>"")); ?>


                        </div>


                        
                        


                    </div>


                </div>


            </div>
    </form>
</div>