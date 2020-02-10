
<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-slider.css')); ?>">
    <style>

        .wizard-link {
            width: 16.666666667%;
            /*width: 25%;*/
        }

        .labelheading {
            font-weight: 600;
        }

        .panel-body {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 2px;
            padding: 20px;
            position: relative;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-top: 1px solid #eee;
        }

        .panel-body h5, .panel-body h4 {
            font-weight: 600;
            font-size: 19px;
            /* background-color: #62cd31; */
            /* color: #62cb31; */
            color: #f58634;
        }

        .cancel {
            padding: 9px;
            width: 8%;
        }

        .customerdesign {
            margin-top: 20px;
        }

        .customerSpan {
            display: inline-block;
            min-width: 149px;
            font-weight: bold;

        }

        .customerTextbox {
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
            width: 50%;
        }

        .wizardDesign {
            position: relative;
            bottom: 33px;
            width: 99%;
            margin-left: 10px;
        }

        .card {
            overflow: hidden;
        }
        .green {
            background-color: #4CAF50 !important;
        }
        .card {
            border-radius: 3px;
        }
        .green {
            background-color: #4CAF50 !important;
        }
        .card {
            position: relative;
            margin: 0.5rem 0 1rem 0;
            background-color: #fff;
            transition: box-shadow .25s;
            border-radius: 2px;
        }
        #card-alert .card-content {
            padding: 10px 20px;
        }
        .card .card-content {
            padding: 24px;
            border-radius: 0 0 2px 2px;
        }
        .white-text {
            color: #FFFFFF !important;
        /*/ color: #000000 !important; /*/
        }
        .white-text {
            color: #FFFFFF !important;
        }
        .white-text {
            color: #FFFFFF !important;
        }
    </style>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA0SLOL7qb7Ch3gVhP0yBCVYCp1_lVwF60"></script>
    <div class="am-content">
        <div class="page-head">
            <h2>Customers</h2>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Customer Management</a></li>
                <li class="active">Customer Update</li>
            </ol>
        </div>
        

        <div class="panel-body">
            <!--                    <p>
                                    The jQuery Validation Plugin provides drop-in validation for your existing forms, while making all kinds of customizations to fit your application really easy.
                                </p>-->

            


            <form id="userForm" method="post" data-parsley-validate>
                <?php if(isset($input['id'])): ?>
                    <?php echo Form::model($input, array('url' => 'admin/editcustomer', $input['id'], 'files' => true)); ?>


                <?php else: ?>

                    <?php echo Form::open(['url'=>'admin/addcustomer', 'files' => true,'id'=>'userForm']); ?>

                <?php endif; ?>

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <div class="col-lg-12" style="">

                    <div class="col-lg-6" style="border-right: 1px solid #eee;;" id="customerForm">

                        <h5 class="heading" style="margin-bottom: 22px;">Customer Details</h5>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-18">
                                <div class="form-group">
                                    <label>Customer Type</label>
                                    <?php echo Form::select("customerCompanyType",$customerType,$input['customerCompanyType'],array('class'=>'form-control','id'=>'customerType','required'=>"")); ?>

                                </div>
                                <div class="form-group">
                                    <label>Address 1</label>

                                    <?php echo Form::text('customerCompanyAddress1',$input['customerCompanyAddress1'], array('placeholder' => 'Enter the address1','class'=>'form-control txtAddress1','id'=>'customerCompanyAddress1','required'=>"required",'data-geocomplete'=>'street address')); ?>

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">

                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <?php echo Form::text('customerCompanyName',$input['customerCompanyName'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Address 2</label>

                                    <?php echo Form::text('customerCompanyAddress2',$input['customerCompanyAddress2'], array('placeholder' => 'Enter the address2','class'=>'form-control','id'=>'customerCompanyAddress2')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>State</label>

                                    <?php echo Form::text('customerCompanyState',$input['customerCompanyState'], array('placeholder' => 'Enter the state','class'=>'form-control','id'=>'customerCompanyState','required'=>"",'attr'=>'1','data-geocomplete'=>'state')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>City</label>

                                    <?php echo Form::text('customerCompanyCity',$input['customerCompanyCity'], array('placeholder' => 'Enter the city','class'=>'form-control','id'=>'customerCompanyCity','data-geocomplete'=>'city')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Zip code</label>

                                    <?php echo Form::text('customerCompanyZipCode',$input['customerCompanyZipCode'], array('placeholder' => 'Enter the zip code','class'=>'form-control','id'=>'customerCompanyZipCode','data-geocomplete'=>'zipcode')); ?>

                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Tel#</label>

                                    <?php echo Form::text('customerCompanyTelNo',$input['customerCompanyTelNo'], array('placeholder' => 'Enter the telephone','class'=>'form-control','id'=>'customerCompanyTelNo')); ?>

                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Main Tel#</label>

                                    <?php echo Form::text('customerCompanyMainTelNo',$input['customerCompanyMainTelNo'], array('placeholder' => 'Enter the telephone number','class'=>'form-control','id'=>'description')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Main Fax#</label>

                                    <?php echo Form::text('customerCompanyMainFaxNo',$input['customerCompanyMainFaxNo'], array('placeholder' => 'Enter the company fax number','class'=>'form-control','id'=>'customerCompanyMainFaxNo')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Website</label>

                                    <?php echo Form::text('website',$input['website'], array('placeholder' => 'Enter the website','class'=>'form-control','id'=>'website')); ?>

                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Primary Contact</h5>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Primary Contact</label>

                                    <?php echo Form::text('customerCompanyPrimaryContact',$input['customerCompanyPrimaryContact'], array('placeholder' => 'Enter the primary contact','class'=>'form-control','id'=>'customerCompanyType','required'=>"")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Contact Title</label>

                                    <?php echo Form::text('customerCompanyTitle',$input['customerCompanyTitle'], array('placeholder' => 'Enter the title','class'=>'form-control','id'=>'description','required'=>"")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Contact Email</label>

                                    <?php echo Form::text('customerCompanyEmail',$input['customerCompanyEmail'], array('placeholder' => 'Enter the company email','class'=>'form-control','id'=>'customerCompanyEmail','required'=>"")); ?>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Preferred Contact</h5>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Preferred Contact</label>

                                    <?php echo Form::text('customerContactName',$input['customerContactName'], array('placeholder' => 'Enter the Preferred Contact','class'=>'form-control','id'=>'customerContactName','required'=>"")); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Preferred Contact Title</label>

                                    <?php echo Form::text('customerContactTitlePrefContact',$input['customerContactTitlePrefContact'], array('placeholder' => 'Enter the Preferred Contact Title','class'=>'form-control','id'=>'description','required'=>"")); ?>

                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Email</label>

                                    <?php echo Form::text('customerContactEmail',$input['customerContactName'], array('placeholder' => 'Enter the email','class'=>'form-control','id'=>'description','required'=>"")); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Tel#</label>

                                    <?php echo Form::text('customerContactTelNo',$input['customerContactTelNo'], array('placeholder' => 'Enter the customer contact number','class'=>'form-control','id'=>'description')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="departmentshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Department</label>

                                    <?php echo Form::text('customerDepartment',$input['customerDepartment'], array('placeholder' => 'Enter the department','class'=>'form-control','id'=>'customerDepartment')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="buildingshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Building name</label>

                                    <?php echo Form::text('customerContactBuildingName',$input['customerContactBuildingName'], array('placeholder' => 'Enter the building name','class'=>'form-control','id'=>'description')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="roomshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Room no</label>

                                    <?php echo Form::text('customerContactRoomNo',$input['customerContactRoomNo'], array('placeholder' => 'Enter the room number','class'=>'form-control','id'=>'description')); ?>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <hr/>


                <div class="col-lg-12" style="margin-bottom: 30px;">


                    <div class="col-lg-6" style="border-right: 1px solid #eee;;" id="billingForm">
                        <h5 style="margin-bottom: 22px;">Billing Address</h5>
                        <?php if($input['billingSame'] == '1'): ?>

                            <?php ($chk1 = 'checked=checked'); ?>
                            

                        <?php else: ?>
                            <?php ($chk1 = '0'); ?>
                            

                        <?php endif; ?>
                        <div class="am-checkbox">
                            <input id="check1" type="checkbox" class="needsclick billingSame"
                                   data-attr="billing" name="billingSame" value="1" <?php echo e($chk1); ?>>
                            <label for="check1"> Same as Company</label>
                        </div>
                        <br>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Name</label>

                                    <?php echo Form::text('customerBillingContactName',$input['customerBillingContactName'], array('placeholder' => 'Enter the Name','class'=>'form-control','id'=>'customerBillingContactName','required'=>"")); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Email</label>

                                    <?php echo Form::text('customerBillingEmail',$input['customerBillingEmail'], array('placeholder' => 'Enter the Email','class'=>'form-control','id'=>'customerBillingEmail','required'=>"")); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Address1</label>

                                    <?php echo Form::text('customerBillingAddress1',$input['customerBillingAddress1'], array('placeholder' => 'Enter the Address1','class'=>'form-control billingAddress1','id'=>'customerBillingAddress1','required'=>"",'autocomplete'=>"on",'data-geocomplete'=>'street address')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Address 2</label>

                                    <?php echo Form::text('customerBillingAddress2',$input['customerBillingAddress2'], array('placeholder' => 'Enter the Address2','class'=>'form-control','id'=>'customerBillingAddress2')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>State</label>

                                    <?php echo Form::text('customerBillingState',$input['customerBillingState'], array('placeholder' => 'Enter the State','class'=>'form-control state','id'=>'customerBillingState','required'=>"",'attr'=>'3','data-geocomplete'=>'state')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>City</label>

                                    <?php echo Form::text('customerBillingCity',$input['customerBillingCity'], array('placeholder' => 'Enter the City','class'=>'form-control','id'=>'customerBillingCity','data-geocomplete'=>'city')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Zip code</label>

                                    <?php echo Form::text('customerBillingZipCode',$input['customerBillingZipCode'], array('placeholder' => 'Enter the Zip Code','class'=>'form-control','id'=>'customerBillingZipCode','required'=>"",'data-geocomplete'=>'zipcode')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Tel#</label>

                                    <?php echo Form::text('customerBillingTelNo',$input['customerBillingTelNo'], array('placeholder' => 'Enter the Tel#','class'=>'form-control','id'=>'customerBillingTelNo')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Fax#</label>

                                    <?php echo Form::text('customerBillingFaxNo',$input['customerBillingFaxNo'], array('placeholder' => 'Enter the Fax#','class'=>'form-control','id'=>'customerBillingFaxNo')); ?>

                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-6" id="shippingForm">

                        <h5 style="margin-bottom: 22px;">Shipping Address</h5>
                        <?php if($input['shippingSame'] == '1'): ?>

                            <?php ($chk = 'checked=checked'); ?>
                            

                        <?php else: ?>
                            <?php ($chk = '0'); ?>
                            

                        <?php endif; ?>
                        <div class="am-checkbox">
                            <input id="check2" type="checkbox" name="shippingSame" value="1"
                                   class="needsclick shippingSame"
                                   data-attr="shipping"<?php echo e($chk); ?>>
                            <label for="check2"> Same as Company</label>
                        </div>
                        <br>

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Name</label>

                                    <?php echo Form::text('customerShippingName',$input['customerShippingName'], array('placeholder' => 'Enter the Name','class'=>'form-control','id'=>'customerShippingName','required'=>"")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Email</label>

                                    <?php echo Form::text('customerShippingEmail',$input['customerShippingEmail'], array('placeholder' => 'Enter the Email','class'=>'form-control','id'=>'customerShippingEmail','required'=>"")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Address1</label>

                                    <?php echo Form::text('customerShippingAddress1',$input['customerShippingAddress1'], array('placeholder' => 'Enter the Address1','class'=>'form-control shippingAddress','id'=>'customerShippingAddress1','required'=>"",'data-geocomplete'=>"street address")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Address 2</label>

                                    <?php echo Form::text('customerShippingAddress2',$input['customerShippingAddress2'], array('placeholder' => 'Enter the Address2','class'=>'form-control','id'=>'customerShippingAddress2')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>State</label>

                                    <?php echo Form::text('customerShippingState',$input['customerShippingState'], array('placeholder' => 'Enter the State','class'=>'form-control state','id'=>'customerShippingState','required'=>"",'attr'=>'2','data-geocomplete'=>"state")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>City</label>

                                    <?php echo Form::text('customerShippingCity',$input['customerShippingCity'], array('placeholder' => 'Enter the City','class'=>'form-control','id'=>'customerShippingCity','data-geocomplete'=>"city")); ?>

                                </div>
                            </div>
                        </div>

                        

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Zip code</label>

                                    <?php echo Form::text('customerShippingZipCode',$input['customerShippingZipCode'], array('placeholder' => 'Enter the Zip Code','class'=>'form-control','id'=>'customerShippingZipCode','required'=>"",'data-geocomplete'=>"zipcode")); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Tel#</label>

                                    <?php echo Form::text('customerShippingTelNo',$input['customerShippingTelNo'], array('placeholder' => 'Enter the Tel#','class'=>'form-control','id'=>'customerShippingTelNo')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Fax#</label>

                                    <?php echo Form::text('customerShippingFaxNo',$input['customerShippingFaxNo'], array('placeholder' => 'Enter the Fax#','class'=>'form-control','id'=>'customerShippingFaxNo')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="shippingMailCode" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Mail Code#</label>

                                    <?php echo Form::text('shippingMailCode',$input['shippingMailCode'], array('placeholder' => 'Enter the Mail Code','class'=>'form-control','id'=>'shippingMailCode')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" id="shippingdeptshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Department</label>

                                    <?php echo Form::text('customerShippingDepartment',$input['customerShippingDepartment'], array('placeholder' => 'Enter the Department','class'=>'form-control','id'=>'customerShippingDept')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="shippingbuildingshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Building name</label>

                                    <?php echo Form::text('customerShippingDepartmentBuilding',$input['customerShippingDepartmentBuilding'], array('placeholder' => 'Enter the Building Name','class'=>'form-control','id'=>'customerShippingBuilding')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" id="shippingroomshow" style="<?php echo e((($input['customerCompanyType'] =="2" && $input['id'] !=''))? '':'display:none'); ?>">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Room no</label>

                                    <?php echo Form::text('customerShippingRoom',$input['customerShippingRoom'], array('placeholder' => 'Enter the Room Number','class'=>'form-control','id'=>'customerShippingRoom')); ?>

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <hr/>


                <br/>
                <div style="margin-left: 44%;">
                    <button class="btn btn-sm btn-primary m-t-n-xs"
                            id="customerSetupSubmission"
                    ><strong>Submit</strong></button>
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
        <form action="<?php echo e(url("admin/customerlists")); ?>" method="post" id="formSubmission">
            <input type="text" value="1" name="postvalue">
            <input type="text" value="<?php echo $input['id']; ?>" name="customerSetUpId">
            <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
            <input type="submit" id="submitForm">
        </form>
    </div>
    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery.geocomplete.js')); ?>"></script>
    <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });

    </script>



    <script>

        $(".txtAddress1").geocomplete({
            fields: "#customerForm"
        });

        $(".billingAddress1").geocomplete({
            fields: "#billingForm"
        });

        $(".shippingAddress").geocomplete({
            fields: "#shippingForm"
        });

        $( document ).ready(function() {
            <?php if(count($errors)>0): ?>

            $.toast({
                heading: 'Warning',
                text: "<?php echo e($errors[0]); ?>",
                position: 'top-right',
                showHideTransition: 'slide',
                icon: 'error',
                loader: false
            });

            <?php endif; ?>
        });


        $('body').on('click', '.billingSame', function () {

            var attr = $(this).attr('data-attr');
            var contact = $('#customerContactName').val();
            console.log(contact);
            var telNo = $('#customerCompanyTelNo').val();
            var address2 = $('#customerCompanyAddress2').val();
            var zipcode = $('#customerCompanyZipCode').val();
            var address1 = $('#customerCompanyAddress1').val();
            var state = $('#customerCompanyState').val();
            var email = $('#customerCompanyEmail').val();
            var city = $('#customerCompanyCity').val();
            var fax = $('#customerCompanyMainFaxNo').val();

            if ($(this).prop('checked') == true) {

                $('#customerBillingContactName').val(contact);
                $('#customerBillingCity').val(city);
                $('#customerBillingEmail').val(email);
                $('#customerBillingState').val(state);
                $('#customerBillingAddress1').val(address1);
                $('#customerBillingAddress2').val(address2);
                $('#customerBillingTelNo').val(telNo);
                $('#customerBillingFaxNo').val(fax);
                $('#customerBillingZipCode').val(zipcode);
            }
            else {
                $('#customerBillingContactName').val('');
                $('#customerBillingCity').val('');
                $('#customerBillingEmail').val('');
                $('#customerBillingState').val('');
                $('#customerBillingAddress1').val('');
                $('#customerBillingAddress2').val('');
                $('#customerBillingTelNo').val('');
                $('#customerBillingFaxNo').val('');
                $('#customerBillingZipCode').val('');
            }

        });

        $('body').on('click', '.shippingSame', function () {

            var attr = $(this).attr('data-attr');
            var contact = $('#customerContactName').val();
            console.log(contact);
            var telNo = $('#customerCompanyTelNo').val();
            var address2 = $('#customerCompanyAddress2').val();
            var zipcode = $('#customerCompanyZipCode').val();
            var address1 = $('#customerCompanyAddress1').val();
            var state = $('#customerCompanyState').val();
            var email = $('#customerCompanyEmail').val();
            var city = $('#customerCompanyCity').val();
            var fax = $('#customerCompanyMainFaxNo').val();

            if ($(this).prop('checked') == true) {

                $('#customerShippingName').val(contact);
                $('#customerShippingCity').val(city);
                $('#customerShippingEmail').val(email);
                $('#customerShippingState').val(state);
                $('#customerShippingAddress1').val(address1);
                $('#customerShippingAddress2').val(address2);
                $('#customerShippingTelNo').val(telNo);
                $('#customerShippingFaxNo').val(fax);
                $('#customerShippingZipCode').val(zipcode);
            }
            else {
                $('#customerShippingName').val('');
                $('#customerShippingCity').val('');
                $('#customerShippingEmail').val('');
                $('#customerShippingState').val('');
                $('#customerShippingAddress1').val('');
                $('#customerShippingZipCode').val('');
                $('#customerShippingAddress2').val('');
                $('#customerShippingTelNo').val('');
                $('#customerShippingFaxNo').val('');
            }

        });

        $('body').on('click', '#customerDetailsSubmission', function () {
            show_animation();

            var data = $('#customerDetailsForm, #contactDetailsForm, #customerBillingForm, #customerShippingForm').serialize();

            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                data: data,
                url: "<?php echo e(url("admin/addcustomer")); ?>",
                dataType: "JSON",
                success: function (json) {
                    hide_animation();
                    if (json.result) {
                        $('#submitForm').trigger('click');
                        //window.location = "<?php echo e(url("admin/customerlists")); ?>";
                    }
                }
            });
        });


        $('body').on('change', '#customerType', function () {


            var type = $(this).val();

            if (type == 2) {
                $('#departmentshow').show();
                $('#roomshow').show();
                $('#buildingshow').show();
                $('#shippingbuildingshow').show();
                $('#shippingroomshow').show();
                $('#shippingdeptshow').show();
                $('#shippingMailCode').show();
            } else {
                $('#departmentshow').hide();
                $('#roomshow').hide();
                $('#buildingshow').hide();
                $('#shippingbuildingshow').hide();
                $('#shippingroomshow').hide();
                $('#shippingdeptshow').hide();
                $('#shippingMailCode').hide();
            }


        });


         $('body').on('change', '.state', function (event) {
            
             var state = $(this).val();
             var attr = $(this).attr('attr');
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
                        if(attr==1)
                        {
                         $('#customerCompanyCity').html(data.city);   
                        }
                        else if(attr==2)
                        {
                        $('#customerShippingCity').html(data.city);  
                        }
                        else if(attr==3)
                        {
                         $('#customerBillingCity').html(data.city);  

                        }
                        
                    }


                }
            });


          });


    </script>












    <script type="text/html" id="tolelarance">

        <tr id="<%= Id %>" class="talerance-list index">


        <td><%=description %>
            <input type="hidden" name="orderlimits[<%=Id%>][description]" id="foods-<%= Id %>" value='<%=description%>'/>

        </td>

        <td><%= target_value %>
            <input type="hidden" name="orderlimits[<%=Id%>][target_value]" id="foods-<%= Id %>" value='<%=target_value%>'/>
        </td>
        <td><%= accuracy %>
            <input type="hidden" name="orderlimits[<%=Id%>][accuracy]" id="foods-<%= Id %>" value='<%=accuracy%>'/>
        </td>


        <td> <%= precision %>
            <input type="hidden" name="orderlimits[<%=Id%>][precision]" id="foods-<%= Id %>" value='<%=precision%>'/>
        </td>
    </tr>


</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>