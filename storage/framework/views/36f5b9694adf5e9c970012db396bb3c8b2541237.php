
<?php $__env->startSection('content'); ?>

    <style>
        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }

        .customerdesign {
            margin-top: 20px;
        }

        .customerSpan {
            display: inline-block;
            min-width: 180px;
            font-weight: bold;

        }

        .customerText {
            height: 20px;
            width:300px;
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
        }
        .customerTexts {
            height: 20px;
            width:400px;
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
        }


        .cart-type {
            width: 98% !important;
        }

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Instrument Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Instrument Management</li>
                <li>Add Instrument</li>


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
                                <h3>Add Instrument </h3>
                            </div>


                            <div class="cart-type">
                                <legend>Customer Details</legend>
                                <div class="form-group" style="<?php echo e(($input['customerId'] =="")? '':'display:none'); ?>">
                                    <label>Customer:</label>
                                    <?php echo Form::select("customerId",$customer,$input['customerId'],array('class'=>'form-control','required'=>'required','id'=>'customerId')); ?>

                                </div>
                                <input type="hidden" name="Id" id="Id" value="<?php echo e($input['id']); ?>">


                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20 customerdesign">


                                                <p class="form_text1"><span class="customerSpan">Customer Name</span>:
                                                    <input type="text"
                                                           value="<?php echo e(isset($input['customerName']) ? $input['customerName'] :''); ?>"
                                                           class="myforminput customerText"
                                                           id="customerName" readonly
                                                           name="customerName"
                                                    /></p>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20 customerdesign">

                                                <p class="form_text1"><span class="customerSpan">Customer Type</span>:
                                                    <input type="text" readonly
                                                           id="customerType"
                                                           value="<?php echo e(isset($input['customerType']) ? $input['customerType'] :''); ?>"
                                                           name="customerType"
                                                           class="myforminput customerText"
                                                    /></p>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20 customerdesign">


                                                <p class="form_text1"><span class="customerSpan">Customer Email Id</span>:
                                                    <input
                                                            type="text" class="myforminput customerText"
                                                            value="<?php echo e(isset($input['customerMail']) ? $input['customerMail'] :''); ?>"
                                                            readonly
                                                            id="customerMail" name="customerMail"
                                                    /></p>
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20 customerdesign">



                                                <p class="form_text1"><span
                                                            class="customerSpan">Customer Telephone</span>:
                                                    <input
                                                            type="text" id="customerTelephone" readonly
                                                            value="<?php echo e(isset($input['customerTelephone']) ? $input['customerTelephone'] :''); ?>"
                                                            name="customerTelephone" class="myforminput customerText"
                                                            parsley-type="email"
                                                    /></p>
                                            </div>

                                        </div>

                                        <div class="col-sm-12 col-xs-12">
                                            <div class="m-t-20 customerdesign">
                                                <p class="form_text1"><span class="customerSpan">Customer Address</span>:
                                                    <input type="text"
                                                           value="<?php echo e(isset($input['customerAddress']) ? $input['customerAddress'] :''); ?>"
                                                           id="customerAddress" readonly
                                                           name="customerAddress"
                                                           class="myforminput customerTexts"
                                                    /></p>



                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>
                            <div class="cart-type">
                                <legend>General</legend>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                                <label>Model Name:</label>

                                                <?php echo Form::select("modelId",$modelname,$input['modelId'],array('class'=>'form-control','id'=>'modelId','required'=>'required')); ?>

                                            </div>
                                            <div class="form-group">
                                                <label>Asset#:</label>

                                                <?php echo Form::text('assetno',$input['assetno'], array('class'=>'form-control','id'=>'asset','placeholder' => 'Enter the asset','required'=>"required")); ?>

                                            </div>
                                            <div class="form-group">

                                                <label>Pref Contact:</label>


                                                <?php echo Form::select("pref_contact",$pref_contact,$input['pref_contact'],array('class'=>'form-control','placeholder' => 'Enter the Preferred Contact','id'=>'pref_contact','required'=>'required')); ?>


                                            </div>

                                            <div class="form-group">

                                                <label>Pref Email:</label>


                                                <?php echo Form::text("pref_email",$input['pref_email'],array('class'=>'form-control','placeholder' => 'Enter the emailId','id'=>'pref_email','required'=>'required')); ?>


                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">
                                            <div class="form-group">
                                                <label>Name:</label>

                                                <?php echo Form::text("name",$input['name'],array('class'=>'form-control','id'=>'name', 'placeholder' => 'Enter the name','required'=>'required')); ?>

                                            </div>
                                            <div class="form-group">
                                                <label>Serial Number:</label>

                                                <?php echo Form::text('serial_no',$input['serial_no'], array( 'placeholder' => 'Enter the serial number','class'=>'form-control','id'=>'serial_no','required'=>"required")); ?>

                                            </div>
                                            <div class="form-group">

                                                <label>Pref Telephone:</label>


                                                <?php echo Form::text('pref_tel',$input['pref_tel'], array('placeholder' => 'Enter the telephone number','data-parsley-type'=>"",'class'=>'form-control simple-field-data-mask-selectonfocus','id'=>'pref_tel','data-mask'=>'(000) 000-0000')); ?>


                                            </div>
                                            <div class="form-group">

                                                <label>Location:</label>

                                                <?php echo Form::text('location',$input['location'], array('class'=>'form-control','id'=>'location','placeholder' => 'Enter location','required'=>"required")); ?>



                                            </div>


                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="cart-type">
                                <legend>Calibration Details</legend>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Last Date:</label>

                                                <?php echo Form::text('lastDate',$input['lastDate'], array('class'=>'form-control lastdate', 'placeholder' => 'Please choose last date','id'=>'lastdate','required'=>"required")); ?>


                                            </div>
                                            

                                                

                                                

                                            
                                            <div class="form-group">
                                                <label>As Cal Status:</label>

                                                <?php echo Form::select("as_calibrate",$status,$input['as_calibrate'],array('class'=>'form-control','id'=>'as_calibrate','required'=>'required')); ?>

                                            </div>
                                            <div class="form-group">
                                                <label>Description:</label>

                                                <?php echo Form::text("description",$input['description'],array('class'=>'form-control','placeholder' => 'Enter the description','id'=>'description','required'=>'required')); ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">
                                            <div class="form-group">

                                                <label>Next Due Date:</label>

                                                <?php echo Form::text('nextDate',$input['nextDate'], array( 'class'=>'form-control datepicker calByDate','placeholder' => 'Next Due Date','id'=>'nextDate','required'=>"required",'readonly')); ?>


                                            </div>

                                            <div class="form-group">
                                                <label>As Found Status:</label>

                                                <?php echo Form::select("as_found",$status,$input['as_found'],array('class'=>'form-control','id'=>'asfound','required'=>'required')); ?>

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
                                                            <label style="margin-top: 30px;" for="check2"
                                                                   class="activebottom div-active">is active</label>
                                                        </div>
                                                    </section>
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    

                                                </div>


                                            </div>


                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="cart-type">
                                <legend>Frequency Details</legend>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">
                                                


                                                <?php if($input['freqDate'] == ''): ?>

                                                    <?php ($choosechk = 'checked=checked'); ?>

                                                <?php else: ?>
                                                    <?php ($choosechk = '0'); ?>

                                                <?php endif; ?>
                                                <div class="am-radio inline">
                                                    <input type="radio" name="calFrequency"
                                                           class="calFrequency"
                                                           id="default"
                                                           value="1"/<?php echo e($choosechk); ?>>
                                                    <label for="default">Default</label>
                                                </div>
                                                <?php if($input['freqDate'] != ''): ?>

                                                    <?php ($datechk = 'checked=checked'); ?>

                                                <?php else: ?>
                                                    <?php ($datechk = '0'); ?>

                                                <?php endif; ?>

                                                <div class="am-radio inline">
                                                    <input type="radio" name="calFrequency"
                                                           class="calFrequency"
                                                           id="chooseDate"
                                                           value="2"/<?php echo e($datechk); ?>>
                                                    <label for="chooseDate">Choose Date</label>
                                                </div>


                                            </div>
                                            <div class="form-group" id="defaultFreq"
                                                 style="<?php echo e((($input['frequency'] =="" && $input['id'] ==''))? '':'display:none'); ?>">

                                                <label>Choose Frequency:</label>


                                                <?php echo Form::select('frequency',$frequency,$input['frequency'], array( 'class'=>'form-control frequencyoption','id'=>'nextDate','required'=>"required")); ?>


                                            </div>
                                            <div class="form-group" id="exactDate"
                                                 style="<?php echo e((($input['freqDate'] !="" && $input['id'] !=''))? '':'display:none'); ?>">

                                                <label>Exact Date:</label>


                                                <?php echo Form::text('freqDate',$input['freqDate'], array( 'class'=>'form-control exactdate','placeholder' => 'Enter the exact date','id'=>'freqDate')); ?>


                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">
                                            <div class="form-group">

                                            </div>


                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="cart-type">
                                <legend>Plan Details</legend>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group" id="Serviceplanlist">
                                                <?php if(isset($input['planName']) &&$input['planName']): ?>
                                                    <?php if($servicePlanSelect): ?>
                                                        <?php $__currentLoopData = $servicePlanSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                            <?php if($input['planName']== $key): ?>

                                                                <?php ($planChk = 'checked=checked'); ?>

                                                            <?php else: ?>
                                                                <?php ($planChk = '0'); ?>

                                                            <?php endif; ?>

                                                            <div class="am-radio inline">
                                                                <input type="radio" name="planName"
                                                                       class="planName"
                                                                       data-id="<?php echo e($key); ?>"
                                                                       id="plan_<?php echo e($key); ?>"
                                                                       value="<?php echo e($key); ?>"/<?php echo e($planChk); ?>>
                                                                <label for="plan_<?php echo e($key); ?>"><?php echo e($row); ?></label>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                    <?php endif; ?>

                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="cart-type cart-type-items" style="<?php echo e(($input['id'])? '':'display:none'); ?>">
                                    <div class="row">
                                        <!--Responsive table-->
                                        <div class="col-sm-12">
                                            <div class="widget widget-fullwidth widget-small">
                                                <div class="widget-head">


                                                </div>
                                                <div class="title">Service Pricing Details</div>
                                            </div>


                                            <div class="table-responsive noSwipe">
                                                <table class="table table-fw-widget table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th width="">Operation</th>
                                                        <th width="">Channel</th>
                                                        <th width="">Price</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody class="no-border-x" id="itemsAppend">
                                                    <?php if($input['id']): ?>

                                                        <?php if($getServicePricing): ?>
                                                            <?php $__currentLoopData = $getServicePricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servicerow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>


                                                                <tr>

                                                                    <?php if($input['servicePricingId']==$servicerow['servicePriceId']): ?>

                                                                        <?php ($chk = 'checked=checked'); ?>

                                                                    <?php else: ?>
                                                                        <?php ($chk = '0'); ?>

                                                                    <?php endif; ?>

                                                                    <td>
                                                                        <div class="am-checkbox">
                                                                            <input id="radio_<?php echo e($servicerow['servicePriceId']); ?>"
                                                                                   value="<?php echo e($servicerow['servicePriceId']); ?>"
                                                                                   type="radio"
                                                                                   name="servicePricingId"
                                                                                   class="needsclick"/<?php echo e($chk); ?>>
                                                                            <label for="radio_<?php echo e($servicerow['servicePriceId']); ?>"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td><h4><?php echo e($servicerow['opertionValue']); ?></h4></td>
                                                                    <input type="hidden" name="operation" id="operation"
                                                                           class="operation"
                                                                           value="<?php echo e($servicerow['operation']); ?>">
                                                                    <td><h4><?php echo e($servicerow['channelNumber']); ?></h4></td>
                                                                    <input type="hidden" name="channelValue"
                                                                           id="channelValue"
                                                                           class="channelValue"
                                                                           value="<?php echo e($servicerow['channelValue']); ?>">
                                                                    <input type="hidden" name="finalplanName"
                                                                           id="finalplanName"
                                                                           class="finalplanName"
                                                                           value="<?php echo e($servicerow['planName']); ?>">
                                                                    <input type="hidden" name="channelNumber"
                                                                           id="channelNumber"
                                                                           class="channelNumber"
                                                                           value="<?php echo e($servicerow['channel']); ?>">

                                                                    <td><h4><?php echo e($servicerow['price']); ?></h4></td>
                                                                    <input type="hidden" name="price" id="price"
                                                                           class="price"
                                                                           value="<?php echo e($servicerow['price']); ?>">


                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>


                                                    
                                                    
                                                    


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        

                        

                        


                        <div class="panel panel-default" id="PanelBody" style="display:none;">

                            <div class="panel-body">

                                <div class="widget-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget">
                                                <div>


                                                    <table id='tbl-box'
                                                           class="table table-fw-widget">

                                                        <thead>
                                                        <?php if($input['customerId']): ?>

                                                            <tr id='equipmentBody'>
                                                                <th>Equipment Name</th>
                                                                <th>Serial Number</th>
                                                                <th class="">Plan</th>
                                                                <th class="">Asset</th>
                                                                <th>Model Name</th>
                                                                <th>Last Date</th>
                                                                <th>Next Due</th>
                                                                <th>As Found</th>
                                                                <th>As Calibrate</th>
                                                                <th>Location</th>
                                                                <th>Description</th>
                                                                <th>Email</th>
                                                                <th>Contact</th>

                                                                <th></th>
                                                            </tr>
                                                        <?php else: ?>
                                                            <tr id='equipmentBody' style="display: none;">
                                                                <th>Equipment Name</th>
                                                                <th>Serial Number</th>
                                                                <th class="">Plan</th>
                                                                <th class="">Asset</th>
                                                                <th>Model Name</th>
                                                                <th>Last Date</th>
                                                                <th>Next Due</th>
                                                                <th>As Found</th>
                                                                <th>As Calibrate</th>
                                                                <th>Location</th>
                                                                <th>Description</th>
                                                                <th>Email</th>
                                                                <th>Contact</th>

                                                                <th></th>
                                                            </tr>
                                                        <?php endif; ?>


                                                        </thead>


                                                        <tbody id="equipmentAppend">

                                                        <?php if($equipmentDetail): ?>
                                                            <?php ($j= $totalequipmentDetail); ?>
                                                            <?php ($i = 1); ?>
                                                            <?php $__currentLoopData = $equipmentDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipkey=> $equiprow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                <tr class="div-lits" class="equipment-list"
                                                                    id="<?php echo e($j); ?>">

                                                                    <td> <?php echo e($equiprow['equipmentName']); ?></td>

                                                                    <td> <?php echo e($equiprow['serialNo']); ?></td>
                                                                    <td> <?php echo e($equiprow['planName']); ?></td>
                                                                    <td> <?php echo e($equiprow['asset']); ?></td>
                                                                    <td> <?php echo e($equiprow['modelText']); ?></td>
                                                                    <td> <?php echo e($equiprow['lastDate']); ?></td>
                                                                    <td> <?php echo e($equiprow['nextDate']); ?></td>
                                                                    <td> <?php echo e($equiprow['asfoundText']); ?></td>
                                                                    <td> <?php echo e($equiprow['asCalibrateText']); ?></td>
                                                                    <td> <?php echo e($equiprow['location']); ?></td>
                                                                    <td> <?php echo e($equiprow['Description']); ?></td>
                                                                    <td> <?php echo e($equiprow['prefEmail']); ?></td>
                                                                    <td> <?php echo e($equiprow['prefContact']); ?></td>
                                                                    
                                                                    <td><a href="javascript:void(0)"

                                                                           onClick="remove_price(<?php echo e($equipkey); ?>)"
                                                                        ><i class="s7-close td-cross"
                                                                            aria-hidden="true"></i></a>
                                                                    </td>
                                                                </tr>


                                                                <?php echo Form::hidden("equipmentDetail[".$i."][equipmentName]",$equiprow['equipmentName'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][serialNo]",$equiprow['serialNo'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][asset]",$equiprow['asset'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][modelText]",$equiprow['modelText'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][modelValue]",$equiprow['modelValue'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][lastDate]",$equiprow['lastDate'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][nextDate]",$equiprow['nextDate'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][customerId]",$equiprow['customerId'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][isActive]",$equiprow['isActive'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][planName]",$equiprow['planName'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][planval]",$equiprow['planval'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][saveId]",$equiprow['saveId'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][asfoundText]",$equiprow['asfoundText'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][asfoundValue]",$equiprow['asfoundValue'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][asCalibrateText]",$equiprow['asCalibrateText'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][asCalibrateValue]",$equiprow['asCalibrateValue'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][location]",$equiprow['location'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][Description]",$equiprow['Description'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][prefEmail]",$equiprow['prefEmail'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>

                                                                <?php echo Form::hidden("equipmentDetail[".$i."][prefContact]",$equiprow['prefContact'],array('class'=>'form-control','id'=>'equip'.'-'.$i)); ?>


                                                                <?php ($i++); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


                                                        <?php else: ?>
                                                            <?php ($j=0); ?>
                                                        <?php endif; ?>


                                                        </tbody>

                                                    </table>
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
                                    <a href="javascript:void(0)" id="equipmentDetailAdd"
                                       class="btn btn-space btn-primary">Submit</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div>
            <button data-modal="colored-warning" style="display:none;"
                    class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
            </button>
        </div>
        <div style="display:none;">
            <form action="<?php echo e(url("admin/viewlist")); ?>" method="post" id="formSubmission">
                <input type="text" value="1" name="postvalue">
                <input type="text" value="<?php echo $input['id']; ?>" name="customerSetUpId">
                <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                <input type="submit" id="modelSubmit">
            </form>
        </div>
        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
        <script src="<?php echo e(asset('js/main.js')); ?>"></script>
        <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
        <script src="<?php echo e(asset('js/bootstrap-slider.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery.mask.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery.mask.test.js')); ?>"></script>

        <script src="<?php echo e(asset('js/datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>" type="text/javascript"></script>

        <script>

            var calFrequency = $('input[name="calFrequency"]:checked').val();
            if(calFrequency == 1){
                $('#defaultFreq').show();
            }

            $(document).ready(function () {
                $('.lastdate').datepicker({
                    endDate: "today",
                    altFormat: "mm/dd/yyyy",
                    dateFormat: 'mm/dd/yyyy'
                }).on('changeDate', function (e) {
                    var calFrequency = $('input[name="calFrequency"]:checked').val();
                    // var calFrequency = $('.calFrequency : checked').val();
                    console.log(calFrequency);
                    var frequency_id = $('.frequencyoption').val();
                    console.log(frequency_id);
                    var lastCal = $(this).val();
                    if (calFrequency == 1) {
                        var addMonth = '';
                        if (frequency_id == 1) {
                            addMonth = 3;
                        } else if (frequency_id == 2) {
                            addMonth = 6;
                        } else {
                            addMonth = 12;
                        }

                        //Last Cal date
                        var lastCalDate = new Date(lastCal);
                        var lastCalDatePlusFrequency = new Date(lastCalDate.setMonth(lastCalDate.getMonth() + addMonth));
                        var lastCalDatePlusFrequencyFormat = new Date(lastCalDatePlusFrequency.getFullYear(), lastCalDatePlusFrequency.getMonth() + 1, 0);
                        var lastCalDatePlusFrequencyFormatVal = ("0" + (lastCalDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + lastCalDatePlusFrequencyFormat.getDate() + '/' + lastCalDatePlusFrequencyFormat.getFullYear();
                        var newDate = new Date(lastCalDatePlusFrequencyFormatVal);
                        $('.calByDate').val(lastCalDatePlusFrequencyFormatVal);

                        //Next Due date

                        var nextDueDate = new Date($('.calByDate').val());
                        var nextDueDatePlusFrequency = new Date(nextDueDate.setMonth(nextDueDate.getMonth() + addMonth));
                        var nextDueDatePlusFrequencyFormat = new Date(nextDueDatePlusFrequency.getFullYear(), nextDueDatePlusFrequency.getMonth() + 1, 0);
                        var nextDueDatePlusFrequencyFormatVal = ("0" + (nextDueDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + nextDueDatePlusFrequencyFormat.getDate() + '/' + nextDueDatePlusFrequencyFormat.getFullYear();
                        $('.nextDueDateUp').val(nextDueDatePlusFrequencyFormatVal);

                    } else {
                        $('.calByDate').val('');
                        $('.nextDueDateUp').val('');
                    }
                    $(this).datepicker('hide');
                });
            });

            $('body').on('click', '.frequencyoption', function () {
                var value = $(this).val();
                var lastCal = $('.lastdate').val();
                if (lastCal) {
                    if (value == 1) {

                        var addMonth = 3;
                    } else if (value == 2) {
                        var addMonth = 6;
                    } else if (value == 3) {
                        var addMonth = 12;
                    }
                    //Last Cal date
                    var lastCalDate = new Date(lastCal);
                    var lastCalDatePlusFrequency = new Date(lastCalDate.setMonth(lastCalDate.getMonth() + addMonth));
                    var lastCalDatePlusFrequencyFormat = new Date(lastCalDatePlusFrequency.getFullYear(), lastCalDatePlusFrequency.getMonth() + 1, 0);
                    var lastCalDatePlusFrequencyFormatVal = ("0" + (lastCalDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + lastCalDatePlusFrequencyFormat.getDate() + '/' + lastCalDatePlusFrequencyFormat.getFullYear();
                    var newDate = new Date(lastCalDatePlusFrequencyFormatVal);
                    $('.calByDate').val(lastCalDatePlusFrequencyFormatVal);

                    //Next Due date

                    var nextDueDate = new Date($('.calByDate').val());
                    var nextDueDatePlusFrequency = new Date(nextDueDate.setMonth(nextDueDate.getMonth() + addMonth));
                    var nextDueDatePlusFrequencyFormat = new Date(nextDueDatePlusFrequency.getFullYear(), nextDueDatePlusFrequency.getMonth() + 1, 0);
                    var nextDueDatePlusFrequencyFormatVal = ("0" + (nextDueDatePlusFrequencyFormat.getMonth() + 1)).slice(-2) + '/' + nextDueDatePlusFrequencyFormat.getDate() + '/' + nextDueDatePlusFrequencyFormat.getFullYear();
                    $('.nextDueDateUp').val(nextDueDatePlusFrequencyFormatVal);

                }
            });

            $('body').on('click', '#default', function () {

                $('#defaultFreq').show();
                $('#exactDate').hide();
                $('freqDate').val('');
            });
            $('body').on('click', '#chooseDate', function () {

                $('#exactDate').show();
                $('#defaultFreq').hide();
                $('frequency').val('');

            });
        </script>
        <script>
            $(document).ready(function () {
                $('.exactdate').datepicker({
                    startDate: "today",
                    altFormat: "mm/dd/yyyy",
                    dateFormat: 'mm/dd/yyyy'
                }).on('changeDate', function (e) {
                    var exactdate = $('.exactdate').val();
                    $('#nextDate').val(exactdate);
                });
            });
            $('body').on('click', '#equipmentDetailAdd', function () {
                var data = $('#testPlanForm').serialize();
                $('input[name="planName"]:checked').each(function () {
                    planId = this.value;
                });
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/addcustomerEquipment")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        if (json.result == true) {
                            $('#modelSubmit').trigger('click');
                            //window.location = "<?php echo e(url("admin/viewlist")); ?>";
                        } else {
                            $.toast({
                                heading: 'Alert',
                                text: json.message,
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'error',
                                loader: false
                            });
                        }
                    }
                });
            });

            function remove_price(Id) {

                if (Id) {
                    $('#' + Id).fadeOut("slow");
                    setTimeout(function () {
                        $('#' + Id).remove();
                    }, 1000);
                }

            }
        </script>
        <script>
            $('body').on('change', '.planName', function () {
                var planId = $(this).attr('data-id');
                var modelId = $('#modelId').val();


                if (planId != '' && modelId != '') {

                    $.ajax({
                        type: 'get',
                        url: "<?php echo e(url("admin/showPricing")); ?>",
                        data: {
                            "_token": "<?php echo csrf_token(); ?>",
                            planId: planId,
                            modelId: modelId,
                        },
                        dataType: "json",
                        success: function (res) {

                            if (res.result == true) {
                                $('.cart-type-items').show();
                                var tableSourceData = jQuery("#scriptItems").html();
                                $('#itemsAppend').html(_.template(tableSourceData, {data: res.data}, {Id: index}));
                            }
                        }
                    });
                }


            });
            $('body').on('change', '#modelId', function () {
                $('input[name="planName"]:checked').each(function () {
                    planId = this.value;
                });
                var modelId = $('#modelId').val();

                if (planId != '' && modelId != '') {
                    $.ajax({
                        type: 'get',
                        url: "<?php echo e(url("admin/showPricing")); ?>",
                        data: {
                            "_token": "<?php echo csrf_token(); ?>",
                            planId: planId,
                            modelId: modelId,
                        },
                        dataType: "json",
                        success: function (res) {

                            if (res.result == true) {
                                $('.cart-type-items').show();
                                var tableSourceData = jQuery("#scriptItems").html();
                                $('#itemsAppend').html(_.template(tableSourceData, {data: res.data}, {Id: index}));
                            }
                        }
                    });
                }


            });

        </script>
        <script>
            var index = <?php echo $j + 1; ?>;

            $('body').on('click', '#addequipment', function () {

                var temp = index;

                index = parseInt(index) + 1;

                if ($("#name").val() && $("#serial_no").val() && $("#asset").val() && $("#modelId").val() && $('#lastdate').val() && $('#nextDate').val() && $('#asfound').val()
                    && $('#as_calibrate').val() && $('#location').val() && $('#description').val() && $('#pref_email').val()) {


                    var equipmentName = $("#name").val();
                    var serialNo = $("#serial_no").val();
                    var customerId = $("#customerId").val();
                    var asset = $("#asset").val();
                    if ($('#check2').is(":checked")) {
                        var isActive = 1;
                    } else {
                        var isActive = 0;
                    }


                    var modelText = $("#modelId option:selected").text();
                    var modelValue = $("#modelId").val();
                    var planName = $("#planName option:selected").text();
                    var planval = $("#planName").val();
                    var lastDate = $("#lastdate").val();


                    var nextDate = $("#nextDate").val();
                    var asfoundText = $("#asfound option:selected").text();
                    var asfoundValue = $("#asfound").val();
                    var asCalibrateText = $("#as_calibrate option:selected").text();
                    var asCalibrateValue = $("#as_calibrate").val();

                    var location = $("#location").val();
                    var Description = $("#description").val();
                    var prefEmail = $("#pref_email").val();
                    var prefContact = $("#pref_contact").val();
                    var saveId = $("#Id").val();


                    var equipmentDetails = jQuery("#equipmentunderscore").html();

                    Id = $(".equipment-list").length;
//                console.log(Id);
//                alert(Id)
                    Id++;


                    $('#equipmentAppend').append(_.template(equipmentDetails, {

                        equipmentName: equipmentName,
                        serialNo: serialNo,
                        index: temp,
                        asset: asset,
                        saveId: saveId,
                        modelText: modelText,
                        modelValue: modelValue,
                        lastDate: lastDate,
                        nextDate: nextDate,
                        customerId: customerId,
                        isActive: isActive,
                        planName: planName,
                        planval: planval,
                        asfoundText: asfoundText,
                        asfoundValue: asfoundValue,
                        asCalibrateText: asCalibrateText,
                        asCalibrateValue: asCalibrateValue,
                        location: location,
                        Description: Description,
                        prefEmail: prefEmail,
                        prefContact: prefContact
                    }));


                    $('#equipmentBody').show();
                    $('#PanelBody').show();
                } else {

                    $('.popUp').trigger('click');

                    $('#toleranceBody').hide();
                }

            });
        </script>
        <script>


            $('body').on('change', '#customerId', function (event) {
                event.preventDefault()

                var customerId = $("#customerId").val();
                console.log(customerId);

                $.ajax({
                    type: 'get',
                    url: "<?php echo e(url("admin/getCustomer")); ?>",
                    data: {
                        "_token": "<?php echo csrf_token(); ?>",
                        customerId: customerId,
                    },
                    dataType: "json",
                    success: function (res) {
                        // console.log(res);
                        if (res.result == true) {
                            // console.log(res.users);
                            $("#customerName").val(res.data.customer_name);
                            $('#customerType').val(res.data.name);
                            $('#customerAddress').val(res.data.address1);
                            $('#customerMail').val(res.data.customer_email);
                            $('#customerTelephone').val(res.data.customer_telephone);
                            $('#pref_tel').val(res.data.customer_main_telephone);
                            $('#pref_email').val(res.data.customer_email);
                            $('#pref_contact').html(res.users);
                            $('#Serviceplanlist').html(res.serviceplanselect);
                        }
                    }
                });
            });
        </script>

        <script>


            $('body').on('change', '#pref_contact', function (event) {
                event.preventDefault()

                var user_contact_id = $("#pref_contact").val();
                console.log(user_contact_id);

                $.ajax({
                    type: 'post',
                    url: "<?php echo e(url("admin/getUserContact")); ?>",
                    data: {
                        "_token": "<?php echo csrf_token(); ?>",
                        user_contact_id: user_contact_id,
                    },
                    dataType: "json",
                    success: function (res) {
                        //console.log(res);
                        if (res.result == true) {
                            $("#pref_email").val(res.email);
                            $('#pref_tel').val(res.phone);
                            $('#location').val(res.location);

                        }
                    }
                });
            });
        </script>


        <script type="text/html" id="scriptItems">
            <%   _.each(data, function(pricingdetails , index) { %>
            <tr id="<%= index %>">
            <td>
                <div class="am-checkbox">
                    <input id="radio_<%= pricingdetails['servicePriceId']%>" value="<%= pricingdetails['servicePriceId']%>" type="radio" name="servicePricingId" class="needsclick">
                    <label for="radio_<%= pricingdetails['servicePriceId']%>"></label>
                </div>
            </td>
            <td><h4><%= pricingdetails['opertionValue']%></h4></td>
            <input type="hidden" name="operation" id="operation" class="operation" value="<%= pricingdetails['operation']%>">
            <td><h4><%= pricingdetails['channelNumber']%></h4></td>
                        <input type="hidden" name="channelValue" id="channelValue" class="channelValue" value="<%= pricingdetails['channelValue']%>">
                        <input type="hidden" name="finalplanName" id="planName" class="planName" value="<%= pricingdetails['planName']%>">
                        <input type="hidden" name="channelNumber" id="channelNumber" class="channelNumber" value="<%= pricingdetails['channelNumber']%>">

            <td><%= pricingdetails['price']%></td>
                        <input type="hidden" name="price" id="price" class="price" value="<%= pricingdetails['price']%>">


        </tr>
        <% }); %>
    </script>



    <script type="text/html" id="equipmentunderscore">
        <tr id="<%= index %>" class="equipment-list  div-lits">
           <td style=""><%= equipmentName %>
                <input type="hidden" name="equipmentDetail[<%=index%>][equipmentName]" id="equipment-[<%=Id%>]" value='<%=equipmentName%>'/>
            </td>


             <td style=""><%=serialNo %>
                <input type="hidden" name="equipmentDetail[<%=index%>][serialNo]" id="equipment-[<%=Id%>]" value='<%=serialNo%>'/>

            </td>
            <td style=""><%=planName %>
                <input type="hidden" name="equipmentDetail[<%=index%>][planName]" id="equipment-[<%=planName%>]" value='<%=planval%>'/>

            </td>
              <td style=""><%=asset %>
                <input type="hidden" name="equipmentDetail[<%=index%>][asset]" id="equipment-[<%=Id%>]" value='<%=asset%>'/>
            </td>
            <td style=""><%=modelText %>
                <input type="hidden" name="equipmentDetail[<%=index%>][modelValue]" id="equipment-[<%=Id%>]" value='<%=modelValue%>'/>
            </td>
            <td style=""><%=lastDate %>
                <input type="hidden" name="equipmentDetail[<%=index%>][lastDate]" id="equipment-[<%=Id%>]" value='<%=lastDate%>'/>
            </td>

             <td style=""><%=nextDate %>
                <input type="hidden" name="equipmentDetail[<%=index%>][nextDate]" id="equipment-[<%=Id%>]" value='<%=nextDate%>'/>
            </td>

            <td style=""><%=asfoundText %>
                <input type="hidden" name="equipmentDetail[<%=index%>][asfoundValue]" id="equipment-[<%=Id%>]" value='<%=asfoundValue%>'/>
            </td>
            <td style=""><%=asCalibrateText %>
                <input type="hidden" name="equipmentDetail[<%=index%>][asCalibrateValue]" id="equipment-[<%=Id%>]" value='<%=asCalibrateValue%>'/>
            </td>
             <td style=""><%=location %>
                <input type="hidden" name="equipmentDetail[<%=index%>][location]" id="equipment-[<%=Id%>]" value='<%=location%>'/>
            </td>
             <td style=""><%=Description %>
                <input type="hidden" name="equipmentDetail[<%=index%>][Description]" id="equipment-[<%=Id%>]" value='<%=Description%>'/>
                <input type="hidden" name="equipmentDetail[<%=index%>][saveId]" id="equipment-[<%=saveId%>]" value='<%=saveId%>'/>
                <input type="hidden" name="equipmentDetail[<%=index%>][customerId]" id="equipment-[<%=customerId%>]" value='<%=customerId%>'/>
                <input type="hidden" name="equipmentDetail[<%=index%>][isActive]" id="equipment-[<%=isActive%>]" value='<%=isActive%>'/>
            </td>
              <td style=""><%=prefEmail %>
                <input type="hidden" name="equipmentDetail[<%=index%>][prefEmail]" id="equipment-[<%=Id%>]" value='<%=prefEmail%>'/>
            </td>
            <td style=""><%=prefContact %>
                <input type="hidden" name="equipmentDetail[<%=index%>][prefContact]" id="equipment-[<%=Id%>]" value='<%=prefContact%>'/>
            </td>
            <td style=""> <a href="javascript:void(0)"

                    onClick = "remove_price(<%=index %>)"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
      <div id="colored-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>Please fill all the required fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>