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
            <h2>Service Plan</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Inventory Management</a></li>
                <li class="active">Service Plan</li>
            </ol>
        </div>
        <div class="main-content">
            <form role="form" id="profileForm" enctype="multipart/form-data" method="post" data-parsley-validate=""
                  novalidate="">

                <input type="hidden" name="id" value="<?php echo e($input['id']); ?>">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="error">
                            <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 style="font-weight:600;">Edit Profile</h3>
                            </div>

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Name</label>
                                                <?php echo Form::text('name',$input['name'], array( 'class'=>'form-control','id'=>'name','required'=>"",'placeholder'=>'Please enter the name')); ?>


                                            </div>

                                        </div>
                                    </div> <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>First Name</label>
                                                <?php echo Form::text('first_name',$input['first_name'], array( 'class'=>'form-control','id'=>'first_name','required'=>"",'placeholder'=>'Please enter the name')); ?>


                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Last Name</label>

                                                <?php echo Form::text('last_name',$input['last_name'], array('class'=>'form-control','id'=>'last_name','placeholder'=>'Please enter last name')); ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Email</label>

                                                <?php echo e(Form::text('email',$input['email'],array('class'=>'form-control','required'=>"",))); ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Telephone</label>

                                                <?php echo e(Form::text('telephone',$input['telephone'],array('class'=>'form-control telephone','required'=>"",))); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Address 1</label>

                                                <?php echo e(Form::text('address1',$input['address1'],array('class'=>'form-control','required'=>"",))); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Address 2</label>

                                                <?php echo e(Form::text('address2',$input['address1'],array('class'=>'form-control','required'=>"",))); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>City</label>

                                                <?php echo e(Form::text('city',$input['city'],array('class'=>'form-control'))); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>State</label>

                                                <?php echo e(Form::text('state',$input['state'],array('class'=>'form-control'))); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Zip Code</label>

                                                <?php echo e(Form::text('zipcode',$input['zipcode'],array('class'=>'form-control'))); ?>

                                            </div>

                                        </div>
                                    </div>


                                </div>

                                <div class="row">

                                <div class="col-sm-6">
                                    
                                    <?php if($input['signature']): ?>
                                        <div class="form-group" id="signatureshow">
                                            <label for="categoryDescription" class="input"></label>
                                            <div class="col-sm-4 txt-img">
                                                <label class="input imageDesign">Upload Signature</label>
                                                <a class="thumbnail" href="javascript:void(0);">
                                                    <button type="button" class="close"
                                                            data-id="<?php echo e($input['id']); ?>" id="signatureclose">×
                                                    </button>

                                                    <img class="form-control"
                                                         src="<?php echo e(asset('users/signature/default/'.$input['signature'])); ?>">
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($input['signature']): ?>

                                        <div class="signatureupload" id="signatureupload" style="display:none">
                                            <label class="input imageDesign">Signature</label>
                                            

                                            <input type="file" name="signature" id="signature" style="padding: 8px;background-color: #ccc;width: 100%;"
                                                   class="form-control textTransform">
                                            
                                        </div>
                                    <?php else: ?>
                                        <div class="" id="signatureupload">
                                            <label class="input imageDesign">Signature</label>

                                            <input type="file" name="signature" id="signature" style="padding: 8px;background-color: #ccc;width: 100%;"
                                                   class="form-control textTransform">



                                        </div>
                                    <?php endif; ?>
                                    </div>
                                    
                                <div class="col-sm-6">
                                <?php if($input['photo']): ?>
                                        <div class="form-group" id="imageshow">
                                            <label for="categoryDescription" class="input"></label>
                                            <div class="col-sm-4 txt-img">
                                                <label class="input imageDesign">Profile Photo</label>
                                                <a class="thumbnail" href="javascript:void(0);">
                                                    <button type="button" class="close"
                                                            data-id="<?php echo e($input['id']); ?>" id="image">×
                                                    </button>

                                                    <img class="form-control"
                                                         src="<?php echo e(asset('users/default/'.$input['photo'])); ?>">
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($input['photo']): ?>
                                        <div class="imageupload" id="imageupload" style="display:none">
                                            <label class="input imageDesign">Upload Profile Photo</label>
                                            <input type="file" name="profilephoto" id="profilephoto" style="padding: 8px;background-color: #ccc;width: 100%;"
                                                   class="form-control textTransform">


                                        </div>
                                    <?php else: ?>
                                        <div class="" id="imageupload">

                                            <label class="input imageDesign">Upload Profile Photo</label>
                                            <input type="file" name="profilephoto" id="profilephoto" style="padding: 8px;background-color: #ccc;width: 100%;"
                                                   class="form-control textTransform">


                                        </div>
                                    <?php endif; ?>
                                    
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="panel panel-default" style='text-align: center;'>

                        <div class="panel-body">

                            <div class="text-center">
                                <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                <a href="<?php echo e(url('admin/productlist')); ?>" class="btn btn-space btn-default">Cancel</a>
                            </div>

                        </div>

                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>
    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

    <script>
        $(".telephone").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>


        $('body').on('click', '#signatureclose', function (event) {
            event.preventDefault()

            var signature = '';
            console.log(signature)
            var Id = $(this).attr('data-id');
            console.log(Id)

//            console.log(Id)
            $('#signatureshow').hide();
            $('#signatureupload').show();
            $.ajax({
                type: 'get',
                url: "<?php echo e(url("admin/updatesignature")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    signature: signature,
                    Id: Id,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }


                }
            });
        });</script>

    <script>


        $('body').on('click', '#image', function (event) {
            event.preventDefault()

            var photo = '';
            console.log(photo)
            var Id = $(this).attr('data-id');
            console.log(Id)

//            console.log(Id)
            $('#imageshow').hide();
            $('#imageupload').show();
            $.ajax({
                type: 'get',
                url: "<?php echo e(url("admin/updatephoto")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    photo: photo,
                    Id: Id,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }


                }
            });
        });</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>