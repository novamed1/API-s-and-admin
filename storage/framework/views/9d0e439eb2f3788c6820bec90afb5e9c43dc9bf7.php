
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

            <h2>Channel Point Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master Setup</li>
                <li>Add Channel Points</li>

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
                                <h3>Add Channel Points</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['id'])): ?>
                            <?php echo Form::model($input, array('url' => 'admin/editchannelpoints', $input['id'], 'files' => true)); ?>

                            <?php else: ?>

                            <?php echo Form::open(array('url' => 'admin/editchannelpoints', 'class' => 'form','method'=>'post')); ?>

                            <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                            <label class="col-sm-8">Choose Channel Number</label>

                                            <?php echo Form::select('channel_number',$ChooseChannel,$input['channel_number'], array('class'=>'form-control','id'=>'channel_id','required'=>"required")); ?>

                                        </div>
                                           
                                            
                                        </div>
                                    </div>
                                 
                                       <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Channel Point</label>

                                            <?php echo Form::text('channel_point',$input['channel_point'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")); ?>

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
                                            <a href="<?php echo e(url('admin/channelpointslist')); ?>" class="btn btn-space btn-default">Cancel</a>
                                        </div>

                                    </div>

                                </div>
                         </form>
                        </div>
                   
                </div>
            </div>
        </div>
<script src="<?php echo e(asset('js/datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>" type="text/javascript"></script>
<script>
    // var date = new Date();

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