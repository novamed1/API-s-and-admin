<?php $__env->startSection('content'); ?>
    <style>
        #discount_price {
            display: inline-block;
            width: 50px;
        }

        .table-responsive {
            overflow-x: hidden;
        }

        .discountPrices {
            display: inline-block;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Work Order Summary (Service Request Number :<span></span> <?php echo e($customerDetails['servicerequest']); ?>)</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services & Work Orders </a></li>
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
                                                                    id="changedCustomerzipcode"><?php echo e($customerDetails['getCustomer']->zip_code); ?></span>
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
                                                    <div class="panel-heading">Payment Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><?php echo e($customerDetails['getCustomer']->customer_name); ?>

                                                                <br>
                                                            </strong><?php echo e($customerDetails['getCustomerShipping']->address1); ?>

                                                            <br><?php echo e($customerDetails['getCustomerShipping']->city); ?>

                                                            <br><?php echo e($customerDetails['getCustomerShipping']->state); ?>

                                                            <br><?php echo e($customerDetails['getCustomerShipping']->zip_code); ?>

                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><?php echo e($customerDetails['getCustomerShipping']->phone_num); ?>

                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><?php echo e($customerDetails['getCustomerShipping']->email); ?></a>
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
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        <table class="table table-striped table-fw-widget table-hover">

                                                            <thead>
                                                            <tr>

                                                                <th>S.NO</th>
                                                                <th>Asset #</th>
                                                                <th>Serial #</th>
                                                                <th>Instrument</th>
                                                                <th style="width: 80px">Price</th>
                                                                <th>Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <?php if(isset($customerDetails['orderItems']) ): ?>
                                                                <?php $i = 1; ?>
                                                                <?php $__currentLoopData = $customerDetails['orderItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                                    <tbody>

                                                                    <tr>

                                                                        <td style="font-size: 16px"><?php echo e($i); ?></td>
                                                                        <td style="font-size: 16px"><?php echo e($row['asset_no']); ?></td>
                                                                        <td style="font-size: 16px"><?php echo e($row['serial_no']); ?></td>

                                                                        <td style="font-size: 16px"><?php echo e($row['model_description']); ?></td>
                                                                        
                                                                        <td style="font-size: 16px">
                                                                            $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>
                                                                        <td style="font-size: 16px">
                                                                            $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>

                                                                    </tr>
                                                                    <?php if(isset($row['checklistName'])): ?>
                                                                        <?php if(($row['checklistName'])): ?>
                                                                            <tr>
                                                                                <td>
                                                                                </td>
                                                                                <td style="font-weight:bold">
                                                                                    Maintenance:
                                                                                </td>
                                                                                <td colspan="5"
                                                                                >
                                                                                    <?php echo e($row['checklistName']); ?>

                                                                                </td>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                        <?php if($row['partdetails']): ?>
                                                                            <tr style="border-bottom: solid 0px #ddd;background: #eceaea;">
                                                                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"></td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                    SKU#
                                                                                </td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                    Spare Mode
                                                                                </td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                    Description
                                                                                </td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                </td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                    Price
                                                                                </td>
                                                                                <td
                                                                                        style="font-size:10px;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                                                                    Amount
                                                                                </td>
                                                                            </tr>
                                                                            <?php $__currentLoopData = $row['partdetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sparekey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php echo e($sparekey['serialNumber']); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo e($sparekey['SKU']); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo e($sparekey['spareMode']); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo e($sparekey['partName']); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo e($sparekey['totalQuantity']); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        $ <?php echo e(number_format($sparekey['partPrice'],2)); ?>

                                                                                    </td>
                                                                                    <td>
                                                                                        $ <?php echo e(number_format($sparekey['totalAmount'],2)); ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <?php $i++; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                                                
                                                                    <tr>
                                                                        <td colspan="5" rowspan="4"
                                                                        >
                                                                        </td>
                                                                        <td colspan="1"
                                                                        >
                                                                            Sub Total
                                                                        </td>
                                                                        <td>
                                                                            $ <?php echo e(number_format($customerDetails['totalAmount'],2)); ?>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="1">
                                                                            Shipping &amp; Handling
                                                                        </td>
                                                                        <td>
                                                                            <?php if($customerDetails['shipping_Charge']&&$customerDetails['on_site']==2): ?>
                                                                                $ <?php echo e(number_format($customerDetails['shipping_Charge'],2)); ?>

                                                                            <?php else: ?>
                                                                                $ 0.00
                                                                            <?php endif; ?>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="1">
                                                                            Discount
                                                                        </td>
                                                                        <td style="width:110px">
                                                                            $ <span id="discount_price">
                                                                      <?php if($customerDetails['discount']): ?>
                                                                                    <?php echo e(($customerDetails['discount'])); ?>

                                                                                <?php else: ?>
                                                                                    0.00
                                                                                <?php endif; ?>
                                                                          </span>
                                                                            <a href="javascript:void(0);"
                                                                               class="DiscountEdit" id="discountedit"
                                                                               data-attr="<?php echo e($customerDetails['request_id']); ?>"><i
                                                                                        class="fa fa-pencil"></i></a>
                                                                            <a href="javascript:void(0);"
                                                                               class="DiscountSave"
                                                                               id="savediscount"
                                                                               style="display:none;color: #3CB371;"
                                                                               data-attr="<?php echo e($customerDetails['request_id']); ?>"><i
                                                                                        class="fa fa-check inside-ico"></i></a>
                                                                            <i class="fa fa-spinner fa-spin inside-ico"
                                                                               id="spinner"
                                                                               style="display:none;"></i>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="1" style="font-weight: bold">
                                                                            Total
                                                                        </td>
                                                                        <td>
                                                                            <?php if($customerDetails['grand_total']): ?>
                                                                                $
                                                                                <span id="grand_total"><?php echo e(number_format($customerDetails['grand_total'],2)); ?></span>
                                                                            <?php else: ?>
                                                                                $ 0.00
                                                                            <?php endif; ?>
                                                                            <input type="hidden" id="total"
                                                                                   value="<?php echo e($customerDetails['total']); ?>"
                                                                                   name="total">
                                                                        </td>
                                                                    </tr>
                                                                
                                                                    
                                                                        
                                                                            
                                                                        
                                                                    
                                                                <?php endif; ?>
                                                            
                                                                
                                                                    
                                                                    
                                                                
                                                            

                                                        </table>

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
            $('body').on('click', '.DiscountEdit', function () {
                $(this).hide();
                var id = $(this).attr('data-attr');
                console.log(id);
                var price = $('#discount_price').text();
                // $('#discount_price').html('<td style="display: inline-block;"><input type="text" attr="price" name="discountPrice" id="discountPrice" class="form-control discountPrices" value= ' + price + ' ><td>');
                $('#discount_price').html('<input type="text" attr="price" name="discountPrice" id="discountPrice" onkeypress ="return isNumberKey(event,this)" class="form-control discountPrices" value= ' + price + ' >');
                $('.DiscountSave').show();
            });

            function isNumberKey(evt, obj) {

                var charCode = (evt.which) ? evt.which : event.keyCode
                var value = obj.value;
                var dotcontains = value.indexOf(".") != -1;
                if (dotcontains)
                    if (charCode == 46) return false;
                if (charCode == 46) return true;
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }


            // $("#discountPrice").bind('keyup paste', function () {
            //     this.value = this.value.replace(/[^0-9]/g, '');
            // });
            $('body').on('click', '.DiscountSave', function () {
                var id = $(this).attr('data-attr');
                var grand_total = $('#total').val();
                var discountPrice = $('#discountPrice').val();
                if (discountPrice == '') {
                    var discountPrice = $('#discount_price').text();
                }
                var datastring = {
                    discountPrice: discountPrice,
                    id: id,
                    "_token": "<?php echo csrf_token(); ?>"
                };
                $('.DiscountSave').hide();
                $('#spinner').show();
                $.ajax({
                    type: 'post',
                    url: "<?php echo e(url("admin/savediscountprice")); ?>",
                    data: datastring,
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            if (data.result == true) {
                                $('#discount_price').html('<p>' + discountPrice + '</p>');
                                var total = grand_total - discountPrice;
                                $('#grand_total').text(total);
                                $('#spinner').hide();
                                $('#discountedit').show();
                                if (data.updated == true) {
                                    $.toast({
                                        heading: 'Updated',
                                        text: data.message,
                                        //position: 'top-left',
                                        showHideTransition: 'slide',
                                        icon: 'success',

                                        loader: false
                                    });
                                }

                            } else {
                                $.toast({
                                    heading: 'Error',
                                    text: data.message,
                                    //position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'error',

                                    loader: false
                                });
                            }

                        }
                    }

                });

            });


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
                } else if (attr == 'shipping') {
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
                        } else if (attr == 'shipping') {
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
                            } else if (json.keyattr == 'shipping') {
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
                } else if (attr == 'shipping') {
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
                        } else if (attr == 'shipping') {
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>