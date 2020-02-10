<?php $__env->startSection('content'); ?>
    <style>

        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }

          .alert-danger
        {
            color:red;
        }
        

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Test Point Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Test Point</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">

                    <div class="error">
                        <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>


                     <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                   <!--  <form role="form" id="myform" method="post"  data-parsley-validate=""
                          novalidate=""> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Test Point</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['id']) && $input['id']!=''): ?>
                                <?php echo Form::model($input, array('url' => 'admin/updatetestpoint'."/".$input['id'],'method' => 'PUT')); ?>

                                 <?php else: ?>

                                    <?php echo Form::open(array('url' => 'admin/savetestpoint', 'class' => 'form','method'=>'post')); ?>

                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Test Point</label>
                                                <?php echo Form::text('name',$input['name'], array( 'placeholder' => '','class'=>'form-control','id'=>'name','required'=>"required")); ?>


                                            </div>

                                           
                                           
                                        </div>
                                        

                                    </div>
                                </div>


                            </div>
                            <div class="panel panel-default" style='text-align: center;'>

                                <div class="panel-body">

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                        <a href="<?php echo e(url('admin/testpointlist')); ?>" class="btn btn-space btn-default">Cancel</a>
                                    </div>

                                </div>

                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo e(asset('js/jquery.js')); ?>"></script>

  

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>