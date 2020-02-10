<?php $__env->startSection('content'); ?>

    <div class="am-content">
        <div class="page-head">

            <h2>Work Order Details (Work Order Number :<span></span> <?php echo e($getWorkOrder->work_order_number); ?>)</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Calibration</a></li>
                <li><a href="#">Service Request</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/workorderlist')); ?>" class="btn btn-space btn-primary">Go Back</a>
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
                            <input type="hidden" value="<?php echo e($workOrderId); ?>" name="workOrderId" id="workOrderId"/>

                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    <?php if($customerDetails): ?>
                                        <div class="">
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
                                                                    id="changedCustomerCity"><?php echo e($customerDetails['getCustomer']->city); ?></span>
                                                            <br><span
                                                                    id="changedCustomerState"><?php echo e($customerDetails['getCustomer']->state); ?></span>
                                                            <br><span
                                                                    id="changedCustomerZipcode"><?php echo e($customerDetails['getCustomer']->zip_code); ?></span>
                                                            <br>
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
                                                    <div class="panel-heading">Billing Details
                                                        <span class="right" id="editBillingProperty"><a
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
                                                                    id="billing_city"><?php echo e($customerDetails['getCustomerBilling']->city); ?></span>
                                                            <br><span
                                                                    id="billing_state"><?php echo e($customerDetails['getCustomerBilling']->state); ?></span>
                                                            <br><span
                                                                    id="billing_zipcode"><?php echo e($customerDetails['getCustomerBilling']->zip_code); ?></span>
                                                            <br>
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
                                                            <br><span
                                                                    id="shipping_state"><?php echo e($customerDetails['getCustomerShipping']->state); ?></span>
                                                            <br><span
                                                                    id="shipping_zipcode"><?php echo e($customerDetails['getCustomerShipping']->zip_code); ?></span>
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

                                                        <address><strong> <br></strong><a
                                                        mailto:#></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>


                                    <div class="">
                                        <div class="col-sm-12">
                                            <div class="widget widget-fullwidth widget-small">

                                                <div class="flash-message">
                                                    <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                                </div>


                                                <div class="sms-table-list" id="pageResult">

                                                    <div class="table-responsive noSwipe">
                                                        <button style="float: right;margin-top: 10px;margin-right: 10px;"
                                                                data-toggle="modal" data-target="#form-bp1"
                                                                id="changeTechnician" type="button"
                                                                class="btn btn-space btn-primary">
                                                            Change
                                                            Technician
                                                        </button>
                                                        <table style="width: 100%!important;" class="table table-striped table-fw-widget table-hover">

                                                            <thead>
                                                            <tr>

                                                                <th>Line Item</th>
                                                                <th>Asset #</th>
                                                                <th>Serial #</th>
                                                                <th>Instrument</th>
                                                                <th>Location</th>
                                                                <th>Pref Contact</th>
                                                                
                                                                

                                                            </tr>
                                                            </thead>
                                                            <?php if($workOrderDetails): ?>
                                                                <?php $i =1; ?>
                                                                <?php $__currentLoopData = $workOrderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                                    <tbody>

                                                                    <tr>

                                                                        <td><?php echo e($i); ?></td>
                                                                        <td><?php echo e($row['assetNumber']); ?></td>
                                                                        <td><?php echo e($row['serialNumber']); ?></td>

                                                                        <td><?php echo e($row['modelName']); ?></td>
                                                                        <td><?php echo e($row['location']); ?></td>
                                                                        <td><?php echo e($row['contact']); ?></td>
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        


                                                                    </tr>

                                                                    </tbody>
                                                                        <?php $i++; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                            <?php endif; ?>

                                                        </table>

                                                    </div>
                                                </div>
                                                
                                                <div class="panel panel-default">

                                                    <div class="panel-body">
                                                        <div class="text-right">
                                                            

                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
        <!--  <a style="display:none;" data-toggle="modal" data-target="#form-bp1"
            class="modalpopUp" data-icon="&#xe0be;" id="addService" data-keyboard="false"
            data-backdrop="static"></a> -->

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

        <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title">Change Technician</h3>
                    </div>

                    <form id="addTech" method="post" data-parsley-validate="" novalidate="">

                        <div class="modal-body form" style="padding-top: 10px;">

                            <div class="form-group"><br>
                                <label>Maintanence To</label>

                                <?php echo Form::select("maintanenceBy",$technician,$maintanencyBy,array('class'=>'form-control','required'=>'required','id'=>'maintanenceBy')); ?>


                            </div>
                            <div class="form-group">
                                <label>Calibration To</label>
                                <?php echo Form::select("calibrationBy",$technician,$calibrationBy,array('class'=>'form-control','required'=>'required','id'=>'calibrationBy')); ?>

                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal"
                                        data-target="#form-bp1"
                                        class="btn btn-default md-close">
                                    Cancel
                                </button>

                                <button type="button" data-modal="dark-primary"
                                        class="btn btn-primary md-trigger">
                                    Proceed
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--  <a style="display:none;" data-toggle="modal2" data-target="#dark-primary"
            class="AlertPopup" data-icon="&#xe0be;" id="alert" data-keyboard="false"
            data-backdrop="static"></a> -->
        <div id="dark-primary" data-toggle="modal2" class="modal-container modal-dark modal-effect-3">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal2" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Confirm Before You Continue</h3>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <!--  <div class="i-circle text-primary"><i class="icon s7-check"></i></div> -->
                        <h4>Alert!</h4>
                        <p>Are you sure you want to continue?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" data-dismiss="modal2" id="cancelAlertPopup"
                            class="btn btn-primary md-close modal-close">NO,Cancel
                    </button>
                    <button type="button" id="submitTech" data-dismiss="modal" class="btn btn-primary">Continue</button>
                </div>
            </div>
        </div>


        <a style="display:none;" data-toggle="modal" data-target="#propertyList"
           class="modalpopUpProperty" data-icon="&#xe0be;" data-keyboard="false"
           data-backdrop="static"></a>


        <div id="propertyList" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog">
                <div class="modal-content cuustomercontent">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        <h3 class="modal-title"><span id="propertyTitle"></span></h3>
                    </div>

                    <div class="table-responsive modal-body form propertyHtml">


                    </div>
                    <input type="hidden" id="updateType">
                    <input type="hidden" value="<?php echo $getWorkOrder->id; ?>" id="updateServiceRequestPropertyId">
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

        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo e(asset('css/jquery-confirm.css')); ?>">
        <script src="<?php echo e(asset('js/jquery-confirm.js')); ?>"></script>


        
        
        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #ef6262 !important;
                color: white !important;
            }
        </style>


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

            $('body').on('click', ".updateProperty", function () {

                $(this).hide();
                $('#spinLoader').show();
                var keyattr = $('#updateType').val();
                var id = $('input[name="property"]:checked').val();
                var workOrderId = $('#updateServiceRequestPropertyId').val();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    type: "POST",
                    data: {keyattr: keyattr, id: id, workOrderId: workOrderId},
                    url: "<?php echo e(url("admin/updateworkOrderProperty")); ?>",
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
            $('body').on('click', '#submitTech', function () {
                show_animation();

                var maintanenceBy = $('#maintanenceBy').val();
                var calibrationBy = $('#calibrationBy').val();
                var workOrderId = $('#workOrderId').val();

                if (maintanenceBy != '' && calibrationBy != '') {

                    hide_animation();

                    $.ajax({
                        type: "GET",
                        data: {
                            workOrderId: workOrderId,
                            calibrationBy: calibrationBy,
                            maintanenceBy: maintanenceBy
                        },
                        url: "<?php echo e(url("admin/changeTechnician")); ?>",
                        dataType: "JSON",
                        success: function (json) {
                            $('#form-bp1').modal('hide');
                            $('#dark-primary').removeClass('modal-show');
                            if (json.result) {

                            } else {

                            }
                        }
                    });
                }

            });

        </script>

        <style>
            .right {
                float: right !important;
            }

            .customerpopup {
                width: 56% !important;
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