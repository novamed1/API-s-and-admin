<?php $__env->startSection('content'); ?>

    <style>
        .right {
            float: right;
        }

        .customerpopup {
            width: 80% !important;
        }

        .cuustomercontent {

            max-width: 100% !important;
        }

        .modal-header {
            padding: 10px !important;
        }

        .modal-body {
            padding: 1px;
        }

        .inline-edit {
            font-size: 12px;
            height: 35px;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>Work Order Allocation (Service Request Number :<span></span> <?php echo e($getServiceRequest->request_no); ?>)</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services & Work Orders</a></li>
                <li><a href="#">Service Request</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/servicerequest')); ?>" class="btn btn-space btn-primary">Go Back</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                <div class="panel panel-default keywordsearchpanel">

                </div>
            </div>


            <div class="row">
                

                
                <div class="col-sm-12">
                    <div class="widget widget-fullwidth widget-small">

                        <div class="flash-message">
                            <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <form role="form" id="workOrderForm" data-parsley-validate>
                            <input type="hidden" value="<?php echo e($requestId); ?>" name="requestId" id="requestId"/>

                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    <?php if($customerDetails): ?>
                                        <div class="panel panel-default">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details
                                                        <span class="right" id="editCustomerProperty"><a
                                                                    href="javascript:void(0)" id="CustomerPropertyEdit"
                                                                    data-id="<?php echo e($customerDetails['getCustomer']->id); ?>"
                                                                    data-attr="customer" class="cusomerDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinCustomerProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>

                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName"><?php echo e($customerDetails['getCustomer']->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1"><?php echo e($customerDetails['getCustomer']->address1); ?></span>
                                                            <br><span
                                                                    id="changedCustomerCity"><?php echo e($customerDetails['getCustomer']->city); ?></span><br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel"><?php echo e($customerDetails['getCustomer']->customer_telephone); ?></span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail"><?php echo e($customerDetails['getCustomer']->customer_email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details <span class="right"
                                                                                                     id="editBillingProperty"><a
                                                                    href="javascript:void(0)" id="billingPropertyEdit"
                                                                    curr-id="<?php echo e($customerDetails['getCustomerBilling']->id); ?>"
                                                                    customer-id="<?php echo e($customerDetails['getCustomerBilling']->customer_id); ?>"
                                                                    data-attr="billing" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinBillingProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="billing_name"><?php echo e($customerDetails['getCustomerBilling']->billing_contact); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="billing_address1"><?php echo e($customerDetails['getCustomerBilling']->address1); ?></span>
                                                            <br><span
                                                                    id="billing_city"><?php echo e($customerDetails['getCustomerBilling']->city); ?></span><br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="billing_phone"><?php echo e($customerDetails['getCustomerBilling']->phone); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="billing_email"><?php echo e($customerDetails['getCustomerBilling']->email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details<span class="right"
                                                                                                     id="editShippingProperty"><a
                                                                    href="javascript:void(0)" id="shippingPropertyEdit"
                                                                    curr-id="<?php echo e($customerDetails['getCustomerShipping']->id); ?>"
                                                                    customer-id="<?php echo e($customerDetails['getCustomerShipping']->customer_id); ?>"
                                                                    data-attr="shipping" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinShippingProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="shipping_name"><?php echo e($customerDetails['getCustomerShipping']->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="shipping_address1"><?php echo e($customerDetails['getCustomerShipping']->address1); ?></span>
                                                            <br><span
                                                                    id="shipping_city"><?php echo e($customerDetails['getCustomerShipping']->city); ?></span>
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><span
                                                                    id="shipping_phone"><?php echo e($customerDetails['getCustomerShipping']->phone_num); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="shipping_email"><?php echo e($customerDetails['getCustomerShipping']->email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Payment Details
                                                        <span class="right" id="editPaymentProperty"><a
                                                                    href="javascript:void(0)" id="PaymentPropertyEdit"
                                                                    curr-id="<?php echo e(isset($customerDetails['getPayment']->id) ? $customerDetails['getPayment']->id : ''); ?>"
                                                                    customer-id="<?php echo e($customerDetails['getCustomer']->id); ?>"
                                                                    data-attr="customer" class="paymentDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinPaymentProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">

                                                        <address>

                                                            Payment mode: <strong><span
                                                                        id="paymentmode"><?php echo e(isset($customerDetails['getPayment']->name) ? $customerDetails['getPayment']->name : ''); ?></span>

                                                                <br>
                                                            </strong>
                                                            <br>
                                                            <br><abbr
                                                                    title="Phone">Payment
                                                                Terms: </abbr><span
                                                                    id="paymentterm"><?php echo e(isset($customerDetails['getPayment']->pay_terms) ?$customerDetails['getPayment']->pay_terms : ''); ?></span>
                                                        </address>

                                                        
                                                                    
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>


                                    <div class="row">

                                        <div class="col-sm-12">
                                            <?php if($planDetails): ?>
                                                <?php $__currentLoopData = $planDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plankey => $planrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                    <div id="accordion3" class="panel-group accordion accordion-semi">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">

                                                                <h4 class="panel-title"><a data-toggle="collapse"
                                                                                           data-parent="#accordion3"
                                                                                           href="#<?php echo e($planrow['planId']); ?>"
                                                                                           class="collapsed"
                                                                                           aria-expanded="false">
                                                                        <table style="width:100%">
                                                                            <tbody>
                                                                            <tr>

                                                                                <td style="width:10%">
                                                                                    <i
                                                                                            class="icon s7-angle-down"></i>
                                                                                </td>

                                                                                <td style="width:20%">
                                                                                    <span>Plan Name</span>
                                                                                    :
                                                                                    <span><?php echo e($planrow['planName']); ?></span>
                                                                                </td>
                                                                                <td style="width:20%">
                                                                                    <span>Plan Type</span>
                                                                                    :
                                                                                    <span><?php echo e($planrow['planType']); ?></span>
                                                                                </td>
                                                                                <td style="width:20%"><p>
                                                                                        <span>Total Number of Instruments</span>
                                                                                        :
                                                                                        <span><?php echo e($planrow['totalInstruments']); ?></span>
                                                                                    </p>
                                                                                </td>

                                                                                
                                                                                
                                                                                

                                                                            </tr>
                                                                            </tbody>

                                                                        </table>
                                                                    </a></h4>
                                                            </div>
                                                            <div id="<?php echo e($planrow['planId']); ?>"
                                                                 class="panel-collapse collapse" aria-expanded="false">
                                                                <div class="panel-body">
                                                                    <table class="table table-fw-widget table-hover"
                                                                           style="width:100%">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>
                                                                                <?php if($planrow['totalWorkOrderStatus'] != 1): ?>

                                                                                    <div class="am-checkbox">
                                                                                        <input id="valueforoverall<?php echo e($planrow['planId']); ?>"
                                                                                               type="checkbox"
                                                                                               class="selectallValues selectAllClick-<?php echo e($planrow['planId']); ?>"
                                                                                               data-attr-all="<?php echo e($planrow['planId']); ?>">
                                                                                        <label for="valueforoverall<?php echo e($planrow['planId']); ?>"></label>
                                                                                    </div>

                                                                                <?php endif; ?>
                                                                            </th>

                                                                            <th>Model Name</th>
                                                                            <th>Asset Number</th>
                                                                            <th>Serial Number</th>
                                                                            <th>Work Order Number</th>
                                                                        </tr>
                                                                        </thead>

                                                                        <?php if($planrow['instrumentDetails']): ?>
                                                                            <?php ($i = 1); ?>
                                                                            <?php $__currentLoopData = $planrow['instrumentDetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instrumentkey=>$instrumentval): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                <tbody class="no-border-x ">
                                                                                <tr>
                                                                                    <td style="width:10%">
                                                                                        <?php if($instrumentval['workOrderStatus'] != 1): ?>
                                                                                            <div class="am-checkbox">
                                                                                                <input id="check<?php echo e($instrumentval['equipmentId']); ?>"
                                                                                                       value="<?php echo e($instrumentval['request_item_id']); ?>"
                                                                                                       type="checkbox"
                                                                                                       data-attr="<?php echo e($planrow['planId']); ?>"
                                                                                                       data-val="<?php echo e($instrumentval['equipmentId']); ?>"
                                                                                                       name="instrumentDetails[<?php echo e($plankey); ?>][<?php echo e($i); ?>][requestItemId]"
                                                                                                       class="individualClick-<?php echo e($planrow['planId']); ?> selectCheck">
                                                                                                <label for="check<?php echo e($instrumentval['equipmentId']); ?>"></label>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    </td>


                                                                                    <td style="width:20%">

                                                                                        <span><?php echo e($instrumentval['modelName']); ?></span>
                                                                                    </td>
                                                                                    <td style="width:20%"><p>

                                                                                            <span><?php echo e($instrumentval['assetNo']); ?></span>
                                                                                        </p>
                                                                                    </td>
                                                                                    <td style="width:20%"><p>

                                                                                            <span><?php echo e($instrumentval['serialNo']); ?></span>
                                                                                        </p>
                                                                                    </td>
                                                                                    <td><p>
                                                                                            <span><?php echo e($instrumentval['workOrderNumber']); ?></span>
                                                                                        </p></td>

                                                                                    <?php echo Form::hidden("instrumentDetails[".$plankey."][".$i."][planId]",$planrow['planId'],array('class'=>'form-control','id'=>'plan'.'-'.$instrumentkey)); ?>

                                                                                    <?php echo Form::hidden("instrumentDetails[".$plankey."][".$i."][equipmentId]",$instrumentval['equipmentId'],array('class'=>'form-control','id'=>'plan'.'-'.$instrumentkey)); ?>


                                                                                </tr>
                                                                                </tbody>
                                                                                <?php ($i++); ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                                        <?php endif; ?>

                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <?php endif; ?>
                                            <?php echo Form::hidden("maintanenceTo",'',array('class'=>'form-control','required'=>'required','id'=>'maintanenceTo')); ?>

                                            <?php echo Form::hidden("calibrationTo",'',array('class'=>'form-control','required'=>'required','id'=>'calibrationTo')); ?>

                                        </div>
                                    </div>


                                    <div class="panel panel-default"
                                         style='text-align: center;position: relative;top: 10px;'>
                                        <div class="panel-body">
                                            <div class="text-center">

                                                <button id="technicianAllocation" type="button"
                                                        class="btn btn-space btn-primary">Technician Allocation
                                                </button>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>

                            <a style="display:none;" data-toggle="modal" data-target="#form-bp1"
                               class="modalpopUp" data-icon="&#xe0be;" id="addService" data-keyboard="false"
                               data-backdrop="static"></a>


                            <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
                                 class="modal fade modal-colored-header">
                                <div class="modal-dialog custom-width">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                    class="close md-close"><i class="icon s7-close"></i></button>
                                            <h3 class="modal-title">Technician Allocation</h3>
                                        </div>

                                        <form id="addTech" method="post" data-parsley-validate="" novalidate="">

                                            <div class="modal-body form">


                                                <div class="title title-count"
                                                     style="width: 50%;float: right;font-weight:bold">Total Selected
                                                    Instruments : <span id="countNum">0</span></div>
                                                <div class="title title-count"
                                                     style="width: 50%;float: right;font-weight:bold">Total Selected
                                                    Plans : <span id="planNum">0</span></div>


                                                <div class="form-group"><br></br>
                                                    <label>Maintanence To</label>

                                                    <?php echo Form::select("maintanenceBy",$technician,'',array('class'=>'form-control','required'=>'required','id'=>'maintanenceBy')); ?>


                                                </div>
                                                <div class="form-group">
                                                    <label>Calibration To</label>
                                                    <?php echo Form::select("calibrationBy",$technician,'',array('class'=>'form-control','required'=>'required','id'=>'calibrationBy')); ?>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal"
                                                            class="btn btn-default md-close">
                                                        Cancel
                                                    </button>

                                                    <button type="button" data-dismiss="modal" id="submitTech"
                                                            class="btn btn-primary md-close">
                                                        Proceed
                                                    </button>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>


        <a style="display:none;" data-toggle="modal" data-target="#propertyList"
           class="modalpopUpProperty" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>


        <div id="propertyList" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog customerpopup">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="propertyTitle"></span></h3>
                    </div>

                    <div class="table-responsive modal-body form propertyHtml">


                    </div>
                    <input type="hidden" id="updateType">
                    <input type="hidden" value="<?php echo $getServiceRequest->id; ?>" id="updateServiceRequestPropertyId">
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-primary right updateProperty">Update</a>
                        <span style="display: none" id="spinLoader"><i
                                    class="fa fa-spinner fa-spin inside-ico"></i></span>
                    </div>
                </div>
            </div>
        </div>


        <a style="display:none;" data-toggle="modal" data-target="#customerDetail"
           class="modalpopUpCustomerProperty" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>

        <div id="customerDetail" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="">Customer Detail</span></h3>
                    </div>

                    <div class="modal-body" id="detailsForm">

                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-primary right" id="updateCustomerDetailClick">Update</a>
                        <span style="display: none" id="spinLoader"><i
                                    class="fa fa-spinner fa-spin inside-ico"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <a style="display:none;" data-toggle="modal" data-target="#paymentDetail"
           class="modalpopUpPaymentProperty" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>
        <div id="paymentDetail" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="">Payment Detail</span></h3>
                    </div>

                    <div class="modal-body" id="paymentdetailsForm">

                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-primary right"
                           id="updatePaymentDetailClick">Update</a>
                        <span style="display: none" id="spinLoader"><i
                                    class="fa fa-spinner fa-spin inside-ico"></i></span>
                    </div>
                </div>
            </div>
        </div>


        <div id="saving_container" style="display:none;">
            <div id="saving"
                 style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
            <img id="saving_animation" src="<?php echo e(asset('img/load.gif')); ?>" alt="saving"
                 style="z-index:100001;     margin-left: -42px;margin-top: -86px; position:fixed; left:50%; top:50%"/>

            <div id="saving_text"
                 style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">
                <br>
            </div>
        </div>


        <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
        <script>
            $('body').on('click', '.selectallValues', function () {
                var planID = $(this).attr('data-attr-all');

                checkboxselectall(planID);
            });

            function checkboxselectall(planID) {

                var i = 0;

                if ($(".selectAllClick-" + planID).prop('checked') == true) {
                    planArray.push(planID);
                    $('.individualClick-' + planID).each(function () {
                        $(this).prop("checked", true);
                        i += 1;
                    });
                }
                else {
                    $('.individualClick-' + planID).each(function () {
                        $(this).prop("checked", false);

                    });
                    var removeItem = planID;
                    planArray = jQuery.grep(planArray, function (value) {
                        return value != removeItem;
                    });
                    console.log(planArray)
                }
                $('#countNum').text(i);
            }

            $(document).ready(function () {
                // we call the function
                planArray = [];
            });

            $('body').on('click', '.selectCheck', function () {

                var planId = $(this).attr('data-attr');
                var equipmentId = $(this).attr('data-val');
                console.log(equipmentId)
                checkboxeach(planId, equipmentId);

            });


            function checkboxeach(planId, equipmentId) {

                var i = 0;
                var j = 0;

                var totalItems = $('.individualClick-' + planId).length;
                console.log(totalItems)


                $('.individualClick-' + planId).each(function () {

                    if (this.checked) {

                        i += 1;
                    }
                    else {

                        j += 1;

                        $(".selectAllClick-" + planId).prop("checked", false);
                    }
                });
                if (i == totalItems) {
                    $(".selectAllClick-" + planId).prop("checked", true);
                }

                if ($("#check" + equipmentId).prop('checked') == true) {
                    planArray.push(planId);
                    console.log(planArray)
                } else {
                    console.log(j)

                    if (j == totalItems) {

                        var removeItems = planId;
                        planArray = jQuery.grep(planArray, function (value) {
                            return value != removeItems;
                        });

                    }
                    console.log(planArray)

                }

            }

        </script>
        <script>
            $(document).ready(function () {

                jQuery.validator.addMethod("specialChar", function (value, element) {
                    return this.optional(element) || /([0-9a-zA-Z\s])$/.test(value);
                }, "Please Fill Correct Value in Field.");
                $("#addTech").validate({
                    errorElement: "div",
                    //set the rules for the field names

                    rules: {
                        calibrationBy: {
                            required: true,
                        },
                        maintanenceBy: {
                            required: true,
                        },

                    },
                    //set messages to appear inline

                    messages: {
                        calibrationBy: {
                            required: "Plese Choose Technician",
                        },
                        maintanenceBy: {
                            required: "Plese Choose Technician",
                        },

                    },
                    errorPlacement: function (error, element) {
                        error.appendTo(element.parent());
                    }
                });
            });</script>
        <script>
            $('body').on('click', '#submitTech', function (event) {
                event.preventDefault()

                show_animation();

                var maintainedBy = $('#maintanenceBy').val();
                var calibrationBy = $('#calibrationBy').val();
                if (maintainedBy != '' && calibrationBy != '') {

                    $('#maintanenceTo').val(maintainedBy);

                    $('#calibrationTo').val(calibrationBy);
                    var data = $('#workOrderForm').serialize();


                    $.ajax({
                        headers: {
                            'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                        },
                        type: "POST",
                        data: data,
                        url: "<?php echo e(url("admin/addWorkOrder")); ?>",
                        dataType: "JSON",
                        success: function (json) {
                            hide_animation();
                            if (json.result) {
                                hide_animation();
                                $('#submitForm').trigger('click');
                                //window.location = "<?php echo e(url("admin/servicerequest")); ?>";
                            }
                        }
                    });
                }
                hide_animation();

            });
        </script>
        <script>
            $('body').on('click', '#technicianAllocation', function () {
                var totalValue = $('input:checkbox.selectCheck:checked').length;


                if (totalValue == 0) {
                    hide_animation();
                    $('.popUp').trigger('click');
                    return false;
                } else {

                    var planfinalarray = [];
                    console.log(planArray)
                    $.each(planArray, function (i, e) {
                        if ($.inArray(e, planfinalarray) == -1) planfinalarray.push(e);
                    });
                    console.log(planArray)
                    planCount = planfinalarray.length;

                    $('#planNum').text(planCount);
                    $('#countNum').text(totalValue);
                    $('.modalpopUp').trigger('click');
                    $('#modal').modal('toggle');
                }
            });


        </script>


        <script>
            $('body').on('click', '#addWorkOrder', function () {
                show_animation();


                var data = $('#workOrderForm').serialize();


                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/addWorkOrder")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        hide_animation();
                        if (json.result) {
                            $('#submitForm').trigger('click');
                            //window.location = "<?php echo e(url("admin/servicerequest")); ?>";
                        }
                    }
                });
            });

        </script>


        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #003162 !important;
                color: white !important;
            }
        </style>
        <div>
            <button data-modal="colored-warning" style="display:none;"
                    class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
            </button>
        </div>
        <div style="display:none;">
            <form action="<?php echo e(url("admin/servicerequest")); ?>" method="post" id="formSubmission">
                <input type="text" value="1" name="postvalue">
                <input type="text" value="<?php echo $getServiceRequest->id; ?>" name="postTestPlanId">
                <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                <input type="submit" id="submitForm">
            </form>
        </div>

        <div id="colored-warning"
             class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Warning</h3>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                        <h4>Warning!</h4>
                        <p>Please choose any one service request items</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
                </div>
            </div>
        </div>


        <script>
            $('body').on('click', '.detailsEdit', function () {

                var customer_id = $(this).attr('customer-id');
                var curr_id = $(this).attr('curr-id');
                var attr = $(this).attr('data-attr');
                var data = 'curr_id=' + curr_id + '&customer_id=' + customer_id + '&attr=' + attr;

                if (attr == 'billing') {
                    $('#spinBillingProperty').show();
                    $('#editBillingProperty').hide();
                }
                else if (attr == 'shipping') {
                    $('#spinShippingProperty').show();
                    $('#editShippingProperty').hide();
                }
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/requestDetailsEdit")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        if (attr == 'billing') {
                            console.log(attr);
                            $('#spinBillingProperty').hide();
                            $('#editBillingProperty').show();
                        }
                        else if (attr == 'shipping') {
                            $('#spinShippingProperty').hide();
                            $('#editShippingProperty').show();
                        }
                        if (json.result) {
                            $('#propertyTitle').text(json.title);
                            $('.propertyHtml').html(json.data);
                            $('#updateType').val(json.attr);
                            $('.modalpopUpProperty').trigger('click');

                        }
                    }
                });
            });

        </script>


        <script>
            $('body').on('click', '.propertyDataEdit', function () {
                $(this).hide();
                var id = $(this).attr('data-id');
                $('.edit_' + id).each(function () {
                    var content = $(this).html();
                    var name = $(this).attr('attr');
                    $(this).html('<input type="text" attr=' + name + ' id="property_' + name + '_' + id + '" class="form-control inline-edit text_value_' + id + '" value=' + content + '>');
                });
                $('#saveproperty_' + id).show();
            });

            $('body').on('click', '.propertyDataSave', function () {
                var id = $(this).attr('data-attr');
                var name = $('#property_name_' + id).val();
                var phone = $('#property_phone_' + id).val();
                var email = $('#property_email_' + id).val();
                var address1 = $('#property_address1_' + id).val();
                var address2 = $('#property_address2_' + id).val();
                var city = $('#property_city_' + id).val();
                var state = $('#property_state_' + id).val();
                var zipcode = $('#property_zip_' + id).val();
                var keyattr = $('#updateType').val();

                var datastring = {
                    id: id, name: name, phone: phone, email: email, address1: address1, address2: address2,
                    city: city, state: state, zipcode: zipcode, keyattr: keyattr,
                    "_token": "<?php echo csrf_token(); ?>"
                };
                $('#saveproperty_' + id).hide();
                $('#spinner_' + id).show();
                $.ajax({
                    type: 'post',
                    url: "<?php echo e(url("admin/saveajaxcustomerproperty")); ?>",
                    data: datastring,
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            if (data.result) {
                                $('.edit_' + id).each(function () {
                                    var attr = $(this).attr('attr');
                                    var content = $('#property_' + attr + '_' + id).val();
                                    $(this).html(content);
                                });

                                $('#spinner_' + id).hide();
                                $('#editpropertylist_' + id).show();
                                if (data.updated) {
                                    $.toast({
                                        heading: 'Updated',
                                        text: data.message,
                                        //position: 'top-left',
                                        showHideTransition: 'slide',
                                        icon: 'success',

                                        loader: false
                                    });
                                }

                            }

                        }
                    }

                });

            });
        </script>


        <script>
            $('body').on('click', "input:checkbox", function () {
                $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            });

            $('body').on('click', ".updateProperty", function () {

                $(this).hide();
                $('#spinLoader').show();
                var keyattr = $('#updateType').val();
                var id = $('input[name="property"]:checked').val();
                var serviceRequestId = $('#updateServiceRequestPropertyId').val();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: {keyattr: keyattr, id: id, serviceRequestId: serviceRequestId},
                    url: "<?php echo e(url("admin/updateProperty")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        if (json.result) {
                            $('.close').trigger('click');
                            $('#spinLoader').hide();
                            $('.updateProperty').show();
                            if (json.keyattr == 'billing') {
                                $("#billingPropertyEdit").attr("curr-id", id);
                                $('#billing_name').text(json.data.name);
                                $('#billing_address1').text(json.data.address1);
                                $('#billing_city').text(json.data.city);
                                $('#billing_phone').text(json.data.phone);
                                $('#billing_email').text(json.data.email);
                            }
                            else if (json.keyattr == 'shipping') {
                                $("#shippingPropertyEdit").attr("curr-id", id);
                                $('#shipping_name').text(json.data.name);
                                $('#shipping_address1').text(json.data.address1);
                                $('#shipping_city').text(json.data.city);
                                $('#shipping_phone').text(json.data.phone);
                                $('#shipping_email').text(json.data.email);
                            }
                            $.toast({
                                heading: 'Updated',
                                text: json.msg,
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'success',

                                loader: false
                            });
                        }
                    }
                });
            });
        </script>

        <script>
            $('body').on('click', '.cusomerDetailsEdit', function () {

                var id = $(this).attr('data-id');
                var attr = $(this).attr('data-attr');
                var data = '&id=' + id + '&attr=' + attr;

                if (attr == 'customer') {
                    $('#spinCustomerProperty').show();
                    $('#editCustomerProperty').hide();
                }
                else if (attr == 'shipping') {
                    $('#spinShippingProperty').show();
                    $('#editShippingProperty').hide();
                }
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/mainDetailsEdit")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        if (json.attr == 'customer') {
                            $('#spinCustomerProperty').hide();
                            $('#editCustomerProperty').show();
                        }
                        else if (attr == 'shipping') {
                            $('#spinShippingProperty').hide();
                            $('#editShippingProperty').show();
                        }
                        if (json.result) {
                            $('#detailsForm').html(json.data);
                            $('.modalpopUpCustomerProperty').trigger('click');

                        }
                    }
                });
            });

        </script>


        <script>
            $('body').on('click', '.paymentDetailsEdit', function () {

                var id = $(this).attr('customer-id');
                var attr = $(this).attr('data-attr');
                var data = '&id=' + id + '&attr=' + attr;

                if (attr == 'customer') {
                    $('#spinPaymentProperty').show();
                    $('#editPaymentProperty').hide();
                }
                else if (attr == 'shipping') {
                    $('#spinPaymentProperty').show();
                    $('#editPaymentProperty').hide();
                }
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/paymentedit")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        if (json.attr == 'customer') {
                            $('#spinPaymentProperty').hide();
                            $('#editPaymentProperty').show();
                        }
                        else if (attr == 'shipping') {
                            $('#spinPaymentProperty').hide();
                            $('#editPaymentProperty').show();
                        }
                        if (json.result) {
                            console.log(json.data)
                            $('#paymentdetailsForm').html(json.data);
                            $('.modalpopUpPaymentProperty').trigger('click');

                        }
                    }
                });
            });

        </script>
        <script>
            $('body').on('click', '#updateCustomerDetailClick', function () {

                var data = $('#updatedCustomerForm').serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/customerDetailsSubmit")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        $('.close').trigger('click');
                        if (json.result) {
                            $('#changedCustomerName').text(json.data.customer_name);
                            $('#changedCustomeraddress1').text(json.data.address1);
                            $('#changedCustomerCity').text(json.data.city);
                            $('#changedCustomerTel').text(json.data.customer_telephone);
                            $('#changedCustomerEmail').text(json.data.customer_email);
                            $.toast({
                                heading: 'Updated',
                                text: "Customer details are updated",
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'success',

                                loader: false
                            });
                        }
                    }
                });
            });

        </script>

        <script>
            $('body').on('click', '#updatePaymentDetailClick', function () {

                var data = $('#updatedPaymentForm').serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: data,
                    url: "<?php echo e(url("admin/paymentDetailsSubmit")); ?>",
                    dataType: "JSON",
                    success: function (json) {
                        $('.close').trigger('click');
                        if (json.result) {
                            $('#paymentmode').text(json.data.name);
                            $('#paymentterm').text(json.data.pay_terms);

                            $.toast({
                                heading: 'Updated',
                                text: "Payment details are updated",
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'success',

                                loader: false
                            });
                        }
                    }
                });
            });

        </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>