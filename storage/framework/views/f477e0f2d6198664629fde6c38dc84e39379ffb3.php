
<?php $__env->startSection('content'); ?>

    <style>
        .error {
            color: red;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Standard Equipment Details</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master SetUp</li>
                <li>Standard Equipment Details</li>

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
                                <h3>Add Standard Equipment Details</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['id'])): ?>
                                    <?php echo Form::model($input, array('url' => 'admin/editcustomertype', $input['id'], 'files' => true)); ?>

                                <?php else: ?>

                                    <?php echo Form::open(array('url' => 'admin/adddevice', 'class' => 'form','method'=>'post')); ?>

                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Standard Equipment</label>


                                                <?php echo Form::select("devicemodelId",$devicemodel,$input['devicemodelId'],array('class'=>'form-control','id'=>'devicemodel','required'=>'required')); ?>


                                            </div>
                                            <div class="form-group sensitivityform"  style="<?php echo e($input['devicemodelId'] =="1" ? '':'display:none'); ?>">

                                                <label>Sensitivity</label>


                                                <?php echo Form::select("sensitivity",$sensitivity,$input['sensitivity'],array('class'=>'form-control','id'=>'sensitivity','required'=>'required')); ?>


                                            </div>
                                            <div class="form-group">
                                                <label>Manufacturer Serial Number</label>

                                                <?php echo Form::text('serial_no',$input['serial_no'], array( 'placeholder' => 'Enter the Serial number','class'=>'form-control','id'=>'description','required'=>"required")); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">


                                            <div class="form-group unitsForm" style="<?php echo e($input['devicemodelId'] =="1" ? '':'display:none'); ?>">

                                                <label>Units</label>


                                                <?php echo Form::select("unit",$unit,$input['unit'],array('class'=>'form-control','id'=>'unit')); ?>


                                            </div>
                                            <div class="form-group">

                                                <label>Calibration Frequency</label>


                                                <?php echo Form::select("frequencyId",$frequency,$input['frequencyId'],array('class'=>'form-control','id'=>'frequency','required'=>'required')); ?>


                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Last Date</label>

                                                <?php echo Form::text('last_cal_date',$input['last_cal_date'], array( 'placeholder' => 'Enter the Last date','class'=>'form-control datepicker','id'=>'lastdate','required'=>"required")); ?>

                                            </div>

                                            <div class="form-group col-md-4">

                                                <label>Next Date</label>


                                                <?php echo Form::text("nextduedate",$input['nextduedate'],array('placeholder' => 'Enter the next due date','class'=>'form-control datepicker','id'=>'nextdate','required'=>'required')); ?>


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
                                                <label for="check2" class="activebottom">is active</label>
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
                                    <a href="<?php echo e(url('admin/devicelist')); ?>" class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

    <script>

        $('body').on('change', '#devicemodel', function (event) {

            var deviceModelId = $(this).val();
            console.log(deviceModelId);

            if(deviceModelId ==1){
                $('.sensitivityform').fadeIn();
                $('.unitsForm').fadeIn();
            }
            else
            {
                $('.sensitivityform').fadeOut();
                $('.unitsForm').fadeOut();
            }
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>