
<?php $__env->startSection('content'); ?>

   <style>
   .error {
           color: red;
        }
        .div-active{
        margin-top: 38px;
    }
</style>
    <div class="am-content">
        <div class="page-head">

            <h2>Brand Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Shipping</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                     <div class="error">
                     <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                    <form role="form" id="testPlanForm" method="post" data-parsley-validate="" novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Shipping</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['id'])): ?>
                            <?php echo Form::model($input, array('url' => 'admin/editshipping', $input['id'], 'files' => true)); ?>

                            <?php else: ?>

                            <?php echo Form::open(array('url' => 'admin/addshipping', 'class' => 'form','method'=>'post')); ?>

                            <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Shipping Name</label>
                                                <?php echo Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Shipping Name','class'=>'form-control','id'=>'name','required'=>"required")); ?>


                                            </div>
                                            <div class="form-group">
                                            <label class="col-sm-6 control-label">Shipping Charge</label>
                                                <?php echo Form::text('shipping_charge',$input['shipping_charge'], array('placeholder' => 'Enter the Shipping Charge','data-parsley-type'=>"number",'class'=>'form-control','required'=>"",'id'=>'shipping_charge','onkeypress'=>'return isNumberKey(event,this)')); ?>



                                        </div>
                                           
                                            
                                        </div>
                                    </div>
                                 
                                    <div class="form-group">
                                        <div class="col-sm-offset-7">
                                            <div class="am-checkbox">
                                               <?php if($input['is_active'] == 1): ?>
                                                    <?php ($chk = 'checked=checked'); ?>

                                                <?php else: ?>
                                                    <?php ($chk = '0'); ?>

                                                <?php endif; ?>
                                               
                                                <input id="check2" type="checkbox" name="is_active"
                                                       class="needsclick" <?php echo e($chk); ?>>
                                                <label for="check2" class="activebottom div-active">is active</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    

                                </div>

                                    
                                </div>

                            </div>
                        <div class="panel panel-default" style='text-align: center;'>

                                    <div class="panel-body">

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                            <a href="<?php echo e(url('admin/shippinglist')); ?>" class="btn btn-space btn-default">Cancel</a>
                                        </div>

                                    </div>

                                </div>
                         </form>
                        </div>
                   
                </div>
            </div>
        </div>
<script src="<?php echo e(asset('js/datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery.validate.js')); ?>"></script>
<script>
    // var date = new Date();
    function isNumberKey(evt, obj) {

        var charCode = (evt.which) ? evt.which : event.keyCode
        var value = obj.value;
        var dotcontains = value.indexOf(".") != -1;
        if (dotcontains)
            if (charCode == 46) return false;
        if (charCode == 46) return true;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }


    $('.datepicker')
        .datepicker({

            todayHighlight: true,
            orientation: "bottom",
            format: 'd-m-yyyy',
            //startDate: date
        })
        .on('changeDate', function (e) {
            $(this).datepicker('hide');
        });
</script>
<?php $__env->stopSection(); ?>
   

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>