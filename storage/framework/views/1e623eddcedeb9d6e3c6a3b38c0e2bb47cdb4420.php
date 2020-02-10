
<?php $__env->startSection('content'); ?>
    <style>

        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }
        .required
        {
            color: red;
        }

    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA0SLOL7qb7Ch3gVhP0yBCVYCp1_lVwF60"></script>
    <div class="am-content">
        <div class="page-head">

            <h2>Manufacturer Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master Setup</li>
                <li>Add Manufacturer</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">

                    <div class="error">
                        <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>

                    <form role="form" id="myform" method="post" enctype="multipart/form-data" data-parsley-validate=""
                          novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Manufacturer</h3>
                            </div>

                            <div class="panel-body">
                                <?php if(isset($input['manufacturer_id'])): ?>
                                    <?php echo Form::model($input, array('url' => 'admin/editmanufacturer','class'=>'form', $input['manufacturer_id'], 'files' => true)); ?>

                                <?php else: ?>

                                    <?php echo Form::open(array('url' => 'admin/addmanufacturer', 'class' => 'form','method'=>'post')); ?>

                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Manufacturer Name <span class="required">*</span></label>
                                                <?php echo Form::text('name',$input['name'], array( 'placeholder' => 'Enter the name','class'=>'form-control','id'=>'name','required'=>"required")); ?>


                                            </div>

                                            <div class="form-group">
                                                <label>Address2</label>

                                                <?php echo Form::text('address2',$input['address2'], array( 'placeholder' => '','class'=>'form-control')); ?>

                                            </div>

                                            <div class="form-group">

                                                <label>City <span class="required">*</span></label>
                                                <?php echo Form::text('city',$input['city'], array( 'placeholder' => 'Enter the city','class'=>'form-control city','required'=>"required",'data-geocomplete'=>'city')); ?>


                                            </div>
                                            <div class="form-group">
                                                <label>Tel# <span class="required">*</span></label>

                                            <!--<?php echo Form::text('phoneNo',$input['phoneNo'], array( 'placeholder' => 'Enter the telephone number','data-parsley-type'=>"",'class'=>'form-control phone simple-field-data-mask-selectonfocus','id'=>'phone','required'=>"required",'data-mask'=>'(000) 000-0000')); ?>-->
                                                <input data-parsley-type="number" type="text" name='phoneNo'
                                                       placeholder="Enter only numbers" required=""
                                                       class="form-control simple-field-data-mask-selectonfocus" data-mask="(000) 000-0000" value=<?php echo e($input['phoneNo']); ?> >
                                            </div>

                                            <div class="form-group">
                                                <label>Website</label>

                                                <?php echo Form::text('website',$input['website'], array( 'placeholder' => 'Enter the website','class'=>'form-control')); ?>

                                            </div>



                                            <div class="form-group" style="    margin-left: -10px;">


                                                <input type="hidden" name="imagehidden" value=<?php echo e(isset($input['image']) ? $input['image'] : ''); ?>>
                                                <?php if($input['image']): ?>
                                                    <div class="form-group" id="imageshow">
                                                        <label for="categoryDescription" class="input"></label>
                                                        <div class="col-sm-2 txt-img">
                                                            <a class="thumbnail" href="javascript:void(0);">
                                                                <button type="button" class="close"
                                                                        data-id="<?php echo e($input['manufacturer_id']); ?>"
                                                                        id="image">×
                                                                </button>
                                                                <img class="form-control"
                                                                     src="<?php echo e(asset('images/manufacturer/extraLarge/'.$input['image'])); ?>"
                                                                     width="250" height="250">
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($input['image']): ?>
                                                    <div class="imageupload" id="imageupload" style="display:none">
                                                        <?php else: ?>
                                                            <div class="" id="imageupload">
                                                                <?php endif; ?>
                                                                <label class="input imageDesign"
                                                                       style="margin-left: 15px;">Upload
                                                                    Logo</label><br>

                                                                <div class="col-sm-6">
                                                                    <input type="file" name="image" value="<?php echo e($input['image']); ?>" class="form-control textTransform">
                                                                    
                                                                </div>
                                                            </div>
                                                    </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20">
                                                

                                                    
                                                    

                                                

                                                <div class="form-group">
                                                    <label>Address1</label>

                                                    <?php echo Form::text('address',$input['address'], array( 'placeholder' => '','class'=>'form-control txtAddress1','data-geocomplete'=>'street address')); ?>

                                                </div>
                                                <div class="form-group">

                                                    <label>State <span class="required">*</span></label>
                                                    <?php echo Form::text('state',$input['state'], array( 'placeholder' => 'Enter the state','class'=>'form-control state','required'=>"required",'data-geocomplete'=>'state')); ?>


                                                </div>

                                                <div class="form-group">

                                                    <label>Zip Code</label>
                                                    <?php echo Form::text('zipcode',$input['zipcode'], array( 'placeholder' => 'Enter the zip code','class'=>'form-control','data-geocomplete'=>'zipcode')); ?>


                                                </div>

                                                <div class="form-group">
                                                    <label>Fax# </label>

                                                    <?php echo Form::text('fax',$input['fax'], array( 'placeholder' => 'Enter the fax','data-parsley-type'=>"",'class'=>'form-control simple-field-data-mask-selectonfocus','data-mask'=>'(000) 000-0000')); ?>

                                                </div>

                                                <div class="form-group">

                                                    <label>Email <span class="required">*</span></label>
                                                    <?php echo Form::text('email',$input['email'], array( 'placeholder' => 'Enter the email','class'=>'form-control','data-parsley-type'=>"email",'id'=>'email','required'=>"required")); ?>


                                                </div>


                                                <div class="form-group">
                                                    <div class="row">
                                                        <section class="col-md-3">
                                                            <div class="am-checkbox">
                                                                <?php if($input['is_active'] == 1): ?>
                                                                    <?php ($chk = 'checked=checked'); ?>

                                                                <?php else: ?>
                                                                    <?php ($chk = '0'); ?>

                                                                <?php endif; ?>


                                                                <input id="check2" type="checkbox" name="is_active"
                                                                       class="needsclick" <?php echo e($chk); ?>>
                                                                <label for="check2" class="activebottom div-active">is
                                                                    active</label>
                                                            </div>
                                                        </section>
                                                    </div>
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
                                        <a href="<?php echo e(url('admin/manufacturerlist')); ?>" class="btn btn-space btn-default">Cancel</a>
                                    </div>

                                </div>

                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="http://code.jquery.com/qunit/qunit-1.11.0.js"></script>

    <script type="text/javascript" src="<?php echo e(asset('js/jquery.js')); ?>"></script>

    <script type="text/javascript" src="<?php echo e(asset('js/jquery.geocomplete.js')); ?>"></script>



    <script src="<?php echo e(asset('js/sinon-1.10.3.js')); ?>"></script>
    <script src="<?php echo e(asset('js/sinon-qunit-1.0.0.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.mask.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.mask.test.js')); ?>"></script>

    <script>




            $(".txtAddress1").geocomplete({
                fields: "#myform"
            });

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
        });


            $('body').on('change', '.state', function (event) {
            
             var state = $(this).val();
             $.ajax({
                type: 'POST',
                url: "<?php echo e(url("admin/getcity")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    state: state,
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        console.log(data.city);
                        $('.city').html(data.city);
                    }


                }
            });


          });


        </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>