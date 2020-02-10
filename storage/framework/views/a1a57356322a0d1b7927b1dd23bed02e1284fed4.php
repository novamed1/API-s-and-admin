
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
    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Customer Setup</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Management</a></li>
                <li class="active">Customer Setup</li>
            </ol>
        </div>


        <div class="panel-body">
            <!--                    <p>
                                    The jQuery Validation Plugin provides drop-in validation for your existing forms, while making all kinds of customizations to fit your application really easy.
                                </p>-->

            <form id="userForm" method="post" data-parsley-validate action="<?php echo e(url('admin/addCustomerSetup')); ?>">


                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="customerName" id="customerName"
                       value="<?php echo e($customerId); ?>">
                <div class="col-lg-12" style="">

                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">

                        <h5 class="heading" style="margin-bottom: 22px;">Mode Of Payment</h5>

                        <div class="col-lg-4">


                            <?php if($selectPayMethods): ?>
                                <?php $__currentLoopData = $selectPayMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paykey=>$payval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <?php
                                        if($customer_setups)
                                            {
                                                if($customer_setups['payMethod']==$payval->id)
                                                {
                                                    $checked = 'checked';
                                                }
                                                else
                                                {
                                                    $checked = '';
                                                }
                                            }
                                            else
                                                {
                                                    $checked = '';
                                                }

                                    ?>

                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="radio" name="payMethod"
                                                   class="<?php echo e($payval->name); ?>"
                                                   id="pay-<?php echo e($payval->id); ?>"
                                                   value="<?php echo e($payval->id); ?>" <?php echo e($checked); ?>>
                                            <label for="pay-<?php echo e($payval->id); ?>"><?php echo e($payval->name); ?></label>
                                        </div>
                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>
                        </div>


                        <div class="form-group" style="margin-top: 134px;">
                            <label style="font-size: 15px">Payment Terms:</label>
                            <?php echo Form::textarea('paymentTerms',isset($customer_setups['payTerms'])?$customer_setups['payTerms']:'', array('class'=>'form-control','id'=>'paymentTerms','cols'=>30,'rows'=>'5')); ?>


                        </div>


                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Cal Specifications</h5>


                        <?php if($calSpecification): ?>
                            <?php $__currentLoopData = $calSpecification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speckey=>$specval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <?php
                                    if($customer_setups)
                                        {
                                            if($customer_setups['calSpecification']==$specval->id)
                                            {
                                                $checked = 'checked';
                                            }
                                            else
                                            {
                                                $checked = '';
                                            }
                                        }
                                        else
                                            {
                                                $checked = '';
                                            }

                                ?>
                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="calSpecification"
                                               class="<?php echo e($specval->cal_specification); ?> chooseSpec"
                                               id="<?php echo e($specval->id); ?>"
                                               value="<?php echo e($specval->id); ?>" <?php echo e($checked); ?>>
                                        <label for="<?php echo e($specval->id); ?>"><?php echo e($specval->cal_specification); ?></label>
                                    </div>

                                </div>


                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                        <?php endif; ?>

                        <div class="form-group speccomment" style="display:none;">

                            <label class="labelheading">Specification Comments</label>
                            <?php echo Form::textarea('specComments',isset($customer_setups['specComments'])?$customer_setups['specComments']:'', array( 'placeholder' => 'Enter the Comment','class'=>'form-control','id'=>'specComments','rows'=>'5')); ?>

                        </div>


                        
                        

                        

                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>


                        <div>
                            <b>
                                (By default, pipettes are calibrated to the manufacturer's specifications)</b>

                        </div>


                    </div>
                </div>

                <hr/>


                <div class="col-lg-12" style="margin-bottom: 30px;">


                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">
                        <h5 style="margin-bottom: 22px;">Cal Frequency</h5>

                        <?php if($calFrequency): ?>
                            <?php $__currentLoopData = $calFrequency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $freqkey=>$freqval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <?php
                                    if($customer_setups)
                                        {
                                            if($customer_setups['calFrequency']==$freqval->id)
                                            {
                                                $checked = 'checked';
                                            }
                                            else
                                            {
                                                $checked = '';
                                            }
                                        }
                                        else
                                            {
                                                $checked = '';
                                            }

                                ?>

                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="calFrequency"
                                               class="<?php echo e($freqval->name); ?>"
                                               id="freq-<?php echo e($freqval->id); ?>"
                                               value="<?php echo e($freqval->id); ?>" <?php echo e($checked); ?> >
                                        <label for="freq-<?php echo e($freqval->id); ?>"><?php echo e($freqval->name); ?></label>
                                    </div>
                                </div>




                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                        <?php endif; ?>
                        <?php
                        if($customer_setups)
                        {
                            if($customer_setups['calFrequency'])
                            {
                                $exact_date = '';
                            }
                            else
                            {
                                $exact_date = isset($customer_setups['excatDate']) ? date('m-d-Y',strtotime(str_replace('/','-',$customer_setups['excatDate']))) : '';
                            }
                        }
                        else
                            {
                                $exact_date = '';
                            }


                        ?>

                        
                            
                            

                        

                        <span>
                                    
                                    <b>(By default, end of the month is printed as the next due date on the certificate.)</b>
                                </span>

                    </div>

                    <div class="col-lg-6">

                        <h5 style="margin-bottom: 22px;">Labeling</h5>

                        <?php if($selectLabeling): ?>
                            <?php $__currentLoopData = $selectLabeling; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $labelkey=>$labelval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <?php
                                    if(isset($customer_setups['assetLabel']) && $customer_setups['assetLabel'])
                                        {

                                if(in_array($labelval->id,$customer_setups['assetLabel']))
                                    {
                                        $checked = 'checked';
                                    }
                                    else
                                        {
                                            $checked = '';
                                        }
                                        }
                                        else
                                            {
                                                $checked='';
                                            }
                                ?>

                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="checkbox" name="labeling[]"
                                               class="<?php echo e($labelval->name); ?>"
                                               id="label-<?php echo e($labelval->id); ?>"
                                               value="<?php echo e($labelval->id); ?>" <?php echo e($checked); ?>>
                                        <label for="label-<?php echo e($labelval->id); ?>"><?php echo e($labelval->name); ?></label>
                                    </div>
                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        <?php endif; ?>


                    </div>


                </div>
                <hr/>


                <div class="col-lg-12" style="">

                    <div class="col-lg-6" style="border-right: 1px solid #eee;;">

                        <h5 class="heading" style="margin-bottom: 22px;">Shipping</h5>

                        <?php if($selectShipping): ?>
                            <?php $__currentLoopData = $selectShipping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipkey=>$shipval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <?php
                                if((isset($customer_setups['shipping']) && $customer_setups['shipping']==$shipval->id))
                                {
                                    $checked = 'checked';
                                }
                                else
                                {
                                    $checked = '';
                                }
                                ?>
                                <div class="col-sm-12">
                                    <div class="am-radio inline">
                                        <input type="radio" name="shipValue"
                                               class="<?php echo e($shipval->name); ?>"
                                               id="ship-<?php echo e($shipval->id); ?>"
                                               value="<?php echo e($shipval->id); ?>" <?php echo e($checked); ?> >
                                        <label for="ship-<?php echo e($shipval->id); ?>"><?php echo e($shipval->name); ?></label>
                                    </div>
                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        <?php endif; ?>

                        <div class="form-group" style="margin-bottom: 30px;margin-top: 109px;">
                            <label style="font-size: 15px">Comments:</label>
                            <?php echo Form::textarea('shippingComment',isset($customer_setups['shippingComments'])?$customer_setups['shippingComments']:"", array( 'placeholder' => 'Enter the Comment','class'=>'form-control','id'=>'shippingComment','rows'=>'5')); ?>


                        </div>


                    </div>
                    <div class="col-lg-6">

                        <h5 class="heading" style="margin-bottom: 22px;">Service Plan</h5>

                        <div class="col-lg-3">


                            <?php if($selectServicePlan): ?>
                                <?php $__currentLoopData = $selectServicePlan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serplankey=>$serplanval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <?php
                                        if((isset($customer_setups['plans'])&&$customer_setups['plans']))
                                            {

                                    if(in_array($serplanval->id,$customer_setups['plans']))
                                    {
                                        $checked = 'checked';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }

                                    }
                                    else
                                        {
                                            $checked = '';
                                        }
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="checkbox"
                                                   data-parsley-multiple="groups"
                                                   name="servicePlan[]"
                                                   data-parsley-errors-container="#error-container1"
                                                   class="<?php echo e($serplanval->service_plan_name); ?>"
                                                   id="serviceplan-<?php echo e($serplanval->id); ?>"
                                                   value="<?php echo e($serplanval->id); ?>" <?php echo e($checked); ?>>
                                            <label for="serviceplan-<?php echo e($serplanval->id); ?>"><?php echo e($serplanval->service_plan_name); ?></label>
                                        </div>
                                    </div>


                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </div>

                    </div>
                </div>

                <hr/>


                <br/>
                <div style="margin-left: 44%;">
                    <button class="btn btn-sm btn-primary m-t-n-xs"
                            id="customerSetupSubmission"
                            style="background-color: #f58634;border-color:#f58634;"><strong>Submit</strong></button>
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

    <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });

    </script>

    

        
            
            
            
            
            
                
                    
                
                
                
                
                
                
                    
                        
                        
                    
                
            
        

    


    <script type="text/javascript">

        $('body').on('click', '.chooseSpec', function () {
            var value = $(this).attr('id');

            if (value == 3) {
                $('.speccomment').show();
            } else {
                $('.speccomment').hide();
            }
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