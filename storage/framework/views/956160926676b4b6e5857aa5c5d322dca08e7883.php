<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-slider.css')); ?>">
    <style>

        .location-list {
            border-top: 1px solid #FFFFFF !important;
        }

        .table > thead > tr > th {
            font-weight: bold;
        }

        .div-sec td {
            width: 18%;

        }

        .fullWidth {
            width: 100%;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Technician</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Technician Management</a></li>
                <li class="active">Add Technician</li>
            </ol>
        </div>
        <div class="main-content">
            <form action="#" id="technicianForm"
                  class="form-horizontal group-border-dashed" method="post"
                  data-parsley-validate>
                
                <?php if($input['id']): ?>
                    <?php echo Form::model($input, array('url' => 'admin/editTechnician/'. $input['id'])); ?>


                <?php else: ?>

                    <?php echo Form::open(['url'=>'admin/addtechnician']); ?>

                <?php endif; ?>
                <input type="hidden" name="id" value="<?php echo e($input['id']); ?>">
                <div class="row">
                    <div class="col-md-12">


                        <div class="panel panel-default">
                            <div class="error">
                                <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            </div>

                            <div class="panel-heading">
                                <h3 style="font-weight:600;">Technician Creation</h3>
                            </div>

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>First Name</label>
                                                <?php echo Form::text('firstName',$input['firstName'], array( 'class'=>'form-control','id'=>'firstName','required'=>"required",'placeholder'=>'Please Enter First Name')); ?>


                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Last Name</label>

                                                <?php echo Form::text('lastName',$input['lastName'], array('class'=>'form-control','id'=>'lastName','placeholder'=>'Please Enter Last Name')); ?>

                                            </div>

                                        </div>
                                    </div>

                                </div>


                                <div class="row">

                                    <?php if($input['id']): ?>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-10">
                                                <div class="form-group">
                                                    <label>Email</label>

                                                    <?php echo e(Form::text('email',$input['email'],array('class'=>'form-control','required'=>"required",'id'=>'email','placeholder'=>'Please Enter Email Id','readonly'))); ?>

                                                </div>

                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-10">
                                                <div class="form-group">
                                                    <label>Email</label>

                                                    <?php echo e(Form::text('email',$input['email'],array('class'=>'form-control','required'=>"required",'id'=>'email','placeholder'=>'Please Enter Email Id'))); ?>

                                                </div>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Phone Number</label>

                                                <?php echo e(Form::text('phoneNumber',$input['phoneNumber'],array('class'=>'form-control','required'=>'required','id'=>'phoneNumber','placeholder'=>'Please Enter Phone Number'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Address</label>

                                                <?php echo e(Form::text('address',$input['address'],array('class'=>'form-control','required'=>"required",'id'=>'address','placeholder'=>'Please Enter Address'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>City</label>

                                                <?php echo e(Form::text('city',$input['city'],array('class'=>'form-control','required'=>'required','id'=>'city','placeholder'=>'Please Enter City'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>State</label>

                                                <?php echo e(Form::text('state',$input['state'],array('class'=>'form-control','required'=>"required",'id'=>'state','placeholder'=>'Please Enter State'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Zip Code</label>

                                                <?php echo e(Form::text('zip_code',$input['zip_code'],array('class'=>'form-control','required'=>'required','id'=>'zipcode','placeholder'=>'Please Enter Zip Code'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Password</label>

                                                <?php echo e(Form::text('password',$input['password'],array('class'=>'form-control','required'=>"required",'id'=>'password','placeholder'=>'Please Enter Password'))); ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            
                                            

                                            
                                            

                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary" data-attr="#technicianForm">
                                        Submit
                                    </button>
                                    <a href="<?php echo e(url('admin/technicianlist')); ?>"
                                       class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>


                    </div>


                </div>
            </form>
        </div>

    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div style="display:none;">
        
        
        
        
        
        
        

        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery.validate.js')); ?>"></script>

        <script src="<?php echo e(asset('js/bootstrap-slider.js')); ?>"></script>


        

        

        
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        


        

        <style type="text/css">
            .error {
                color: red;
            }
        </style>




<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>