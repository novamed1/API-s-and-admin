<?php $__env->startSection('content'); ?>
    <style>

        .error {
            color: red;
        }

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Service Plan Type Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Service Plan Type</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                    <form role="form" id="myform" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Service Plan Type</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['id'])): ?>
                                    <?php echo Form::model($input, array('url' => 'admin/editserviceplantype', $input['id'], 'files' => true)); ?>

                                <?php else: ?>

                                    <?php echo Form::open(array('url' => 'admin/addserviceplantype', 'class' => 'form','method'=>'post')); ?>

                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label class="col-sm-3 control-label">Name</label>


                                                <?php echo Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")); ?>


                                            </div>

                                        </div>
                                    </div>
                                </div>
                                    <div class="row">

                                    <div class="col-sm-12 col-xs-12">
                                        <div class="m-t-18">



                                            <?php echo Form::textarea('description',html_entity_decode($input['description']), array( 'placeholder' => 'Enter the description','class'=>'form-control','id'=>'description','required'=>"required")); ?>

                                                <?=app(JeroenNoten\LaravelCkEditor\CkEditor::class)->editor('description',['height' => 500]);?>
                                           </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">
                                            
                                                

                                                
                                            

                                            <label class="">#click checkbox to active this service plan type</label>
                                            <div class="am-checkbox">
                                                <?php if($input['is_active'] == 1): ?>
                                                    <?php ($chk = 'checked=checked'); ?>

                                                <?php else: ?>
                                                    <?php ($chk = '0'); ?>

                                                <?php endif; ?>

                                                <input id="check1" type="checkbox" name='is_active'
                                                       class="needsclick needsclick" value='1' <?php echo e($chk); ?> >
                                                <label for="check1">Active</label>
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
                                    <a href="<?php echo e(url('admin/customertypelist')); ?>"
                                       class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>