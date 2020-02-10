<?php $__env->startSection('content'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('css/service-req.css')); ?>" type="text/css">
    <style>
        .right {
            float: right;
        }

        .rightside {
            right: 20px;
        }

        .model-heading {
            font-weight: 600;
            text-align: center;
        }

        .serviceListdropdown {
            width: 100%;
            margin-left: -5px;
        }

        .viewDetail > td {
            width: 10%;
        }

        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes  fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
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
    <div class="am-content">
        <div class="page-head">

            <h2>Customer Service Details (Service Number :<span></span> <?php echo e($getServiceDetails->service_number); ?>)</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services & Workorders</a></li>
                <li><a href="#">Customer Service Orders</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/buyservice')); ?>" class="btn btn-space btn-primary">Go Back</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                <div class="panel panel-default keywordsearchpanel">

                </div>
            </div>

            <form role="form" id="servicesForm" data-parsley-validate>
                <div class="row">
                    

                    
                    <div class="col-sm-12">
                        <div class="widget widget-fullwidth widget-small">

                            <div class="flash-message">
                                <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            </div>


                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    <?php if($customerinfo): ?>
                                        <div class="">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details
                                                        <span class="right" id="editCustomerProperty"><a
                                                                    href="javascript:void(0)" id="CustomerPropertyEdit"
                                                                    data-id="<?php echo e($customerinfo['getCustomer']->id); ?>"
                                                                    data-attr="customer" class="cusomerDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinCustomerProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName"><?php echo e($customerinfo['getCustomer']->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1"><?php echo e($customerinfo['getCustomer']->address1); ?></span>
                                                            <br><span
                                                                    id="changedCustomerCity"><?php echo e($customerinfo['getCustomer']->city); ?></span><br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel"><?php echo e($customerinfo['getCustomer']->customer_telephone); ?></span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail"><?php echo e($customerinfo['getCustomer']->customer_email); ?></span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details
                                                        <span class="right" id="editBillingProperty"><a
                                                                    href="javascript:void(0)" id="billingPropertyEdit"
                                                                    curr-id="<?php echo e($customerinfo['getCustomerBilling']->id); ?>"
                                                                    customer-id="<?php echo e($customerinfo['getCustomerBilling']->customer_id); ?>"
                                                                    data-attr="billing" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinBillingProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="billing_name"><?php echo e($customerinfo['getCustomerBilling']->billing_contact); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="billing_address1"><?php echo e($customerinfo['getCustomerBilling']->address1); ?></span>
                                                            <br><span
                                                                    id="billing_city"><?php echo e($customerinfo['getCustomerBilling']->city); ?></span><br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="billing_phone"><?php echo e($customerinfo['getCustomerBilling']->phone); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="billing_email"><?php echo e($customerinfo['getCustomerBilling']->email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details<span class="right"
                                                                                                     id="editShippingProperty"><a
                                                                    href="javascript:void(0)" id="shippingPropertyEdit"
                                                                    curr-id="<?php echo e($customerinfo['getCustomerShipping']->id); ?>"
                                                                    customer-id="<?php echo e($customerinfo['getCustomerShipping']->customer_id); ?>"
                                                                    data-attr="shipping" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinShippingProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="shipping_name"><?php echo e($customerinfo['getCustomerShipping']->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="shipping_address1"><?php echo e($customerinfo['getCustomerShipping']->address1); ?></span>
                                                            <br><span
                                                                    id="shipping_city"><?php echo e($customerinfo['getCustomerShipping']->city); ?></span>
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><span
                                                                    id="shipping_phone"><?php echo e($customerinfo['getCustomerShipping']->phone_num); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="shipping_email"><?php echo e($customerinfo['getCustomerShipping']->email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Payment Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><?php echo e($customerinfo['getCustomer']->customer_name); ?>

                                                                <br>
                                                            </strong><?php echo e($customerinfo['getCustomerShipping']->address1); ?>

                                                            <br><?php echo e($customerinfo['getCustomerShipping']->city); ?>

                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><?php echo e($customerinfo['getCustomerShipping']->phone_num); ?>

                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><?php echo e($customerinfo['getCustomerShipping']->email); ?></a>
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


                                                    <div id="pageresult">
                                                        <input type="hidden" class="form-control serviceList"
                                                               name="serviceID"
                                                               value="<?php echo e($serviceID); ?>">

                                                        <?php if($data): ?>
                                                            <input type="hidden"
                                                                   value="<?php echo e($customerinfo['getPlans']); ?>"
                                                                   name="plans">


                                                            <div class="col-sm-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        

                                                                        
                                                                        
                                                                        
                                                                        

                                                                        
                                                                        
                                                                        


                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        


                                                                        
                                                                        
                                                                        
                                                                        
                                                                        


                                                                        
                                                                        <div class="col-sm-12">
                                                                            <table id="example2"
                                                                                   class="footable table table-bordered table-hover">


                                                                                <tr>
                                                                                    
                                                                                    
                                                                                    
                                                                                    <th>Model Description</th>
                                                                                    <th>Asset Number</th>
                                                                                    <th>Serial Number</th>
                                                                                    <th>Last Cal Date</th>
                                                                                    <th style="width:15%!important;">
                                                                                        Frequency
                                                                                    </th>
                                                                                    
                                                                                    <th>
                                                                                        Next Due Date
                                                                                    </th>


                                                                                    
                                                                                    
                                                                                    
                                                                                    <th style="width:15%!important;">
                                                                                        Service
                                                                                        Plan
                                                                                    </th>
                                                                                    <th>Price</th>
                                                                                </tr>

                                                                                <!--                                                                                    --><?php //$model_index = 1; ?>
                                                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                                                    <div class="ser-detail-sec-inr">
                                                                                        <?php $total_equipments = $row['quantity'];
                                                                                        //                                                                                        $sub_index = 1;
                                                                                        ?>

                                                                                        <?php for($i=0;$i<$total_equipments;$i++): ?>
                                                                                            <tbody>

                                                                                            <tr class="viewDetail">

                                                                                                
                                                                                                
                                                                                                <td><?php echo e($row['modelDescription']); ?></td>
                                                                                                <td><input type="text"
                                                                                                           class="form-control serviceList"
                                                                                                           name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][asset_no]">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input type="text"
                                                                                                           class="form-control serviceList"
                                                                                                           name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][serial_no]">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input
                                                                                                            type="text"
                                                                                                            class="form-control serviceList datepickerselect"
                                                                                                            id="lastCal-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>"
                                                                                                            name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][last_cal_date]">
                                                                                                </td>
                                                                                                <input type="hidden"
                                                                                                       name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][hiddenfreq]"
                                                                                                       value="<?php echo e($row['frequency']); ?>">
                                                                                                <td>
                                                                                                    <?php echo Form::select("model[".$row['buy_service_model_id']."][".$i."][frequency]",$frequency,$row['frequency'],array('class'=>'changeFrequency serviceList serviceListdropdown form-control','data-id'=>$row['buy_service_model_id'],'data-val'=>$i,)); ?>


                                                                                                    <span class="exactTextDate-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?> popuptext"
                                                                                                          id="myPopup-<?php echo e($row['buy_service_model_id']); ?>-<?php echo e($i); ?>"
                                                                                                          style="display: none;    margin-left: -5px;
    margin-top: 3px;">

                                                                                                            <input
                                                                                                                    type="text"
                                                                                                                    class="form-control serviceList exactdatepicker"
                                                                                                                    placeholder="Pick up date"
                                                                                                                    attr="nextDue-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>"
                                                                                                                    id="exactDate-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>"
                                                                                                                    name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][exact_date]">
                                                                                                        </span>
                                                                                                </td>

                                                                                                <td class="nextDueTextDate-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>">
                                                                                                    <?php
                                                                                                    if ($row['frequency'] && $row['days']) {

                                                                                                        $next_due_date = date('m/d/Y', strtotime("+" . $row['days'] . " months"));

                                                                                                    } else {
                                                                                                        $next_due_date = '';
                                                                                                    }
                                                                                                    ?>
                                                                                                    <input
                                                                                                            type="text"
                                                                                                            id="nextDue-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>"
                                                                                                            value="<?php echo e($next_due_date); ?>"
                                                                                                            readonly
                                                                                                            class="form-control serviceList nextDue-<?php echo e($row["buy_service_model_id"]); ?>-<?php echo e($i); ?>"
                                                                                                            name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][next_due_date]">
                                                                                                </td>


                                                                                                
                                                                                                
                                                                                                

                                                                                                
                                                                                                
                                                                                                
                                                                                                
                                                                                                
                                                                                                <td>
                                                                                                    <?php echo Form::select("model[".$row['buy_service_model_id']."][".$i."][service_plan]",$row['service_plans'],'',array('class'=>'change_service_plan serviceList serviceListdropdown form-control','id'=>'service_plan_'.$row["buy_service_model_id"].'_'.$i,'unq_id'=>$row["buy_service_model_id"],'current_row'=>$i)); ?>


                                                                                                </td>

                                                                                                <td> $ <span
                                                                                                            id="service_price_<?php echo e($row["buy_service_model_id"]); ?>_<?php echo e($i); ?>"><?php echo e($row['service_price']); ?></span>

                                                                                                    <input type="hidden"
                                                                                                           class="serviceList"
                                                                                                           name="model[<?php echo e($row["buy_service_model_id"]); ?>][<?php echo e($i); ?>][servicePriceId]"
                                                                                                           value="<?php echo e($row['service_price_id']); ?>"
                                                                                                           id="service_price_id_<?php echo e($row["buy_service_model_id"]); ?>_<?php echo e($i); ?>">
                                                                                                </td>

                                                                                            </tbody>



                                                                                            <!--                                                                                            --><?php //$sub_index++; ?>
                                                                                        <?php endfor; ?>
                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                                                                    </div>


                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <!--                                                                --><?php //$model_index++; ?>

                                                        <?php endif; ?>


                                                    </div>

                                                </div>


                                            </div>


                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>


                    </div>

                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="am-radio inline">
                                    <input type="radio" checked="checked" name="accesstype"
                                           id="accessonly" value="1">
                                    <?php if($customerinfo['access'] == 1): ?>

                                        <label for="accessonly">Access for customer portal</label>
                                        <input type="hidden" value="1" name="logincredentials">
                                    <?php else: ?>

                                        <label for="accessonly">Create Equipment</label>
                                        <input type="hidden" value="0" name="logincredentials">
                                    <?php endif; ?>                                            </div>

                                <div class="am-radio inline">
                                    <input type="radio" name="accesstype" id="accesswithrequest"
                                           value="2">
                                    <?php if($customerinfo['access'] == 1): ?>
                                        <label for="accesswithrequest">Access for customer portal with service
                                            request</label>

                                        <input type="hidden" value="1" name="logincredentials">
                                    <?php else: ?>

                                        <label for="accesswithrequest">Create equipment with service request</label>
                                        <input type="hidden" value="0" name="logincredentials">

                                    <?php endif; ?>
                                </div>
                                <div class="am-radio inline">
                                    <div class="shippingDiv" style="display: none;">
                                        <label>Shipping Date</label>

                                        <p class=""><input type="text" id="shippingDate"
                                                           class="form-control datepickerselect"
                                                           name="shippingDate"></p>
                                    </div>
                                </div>

                                <div style="float:right;">
                                    <a href="javascript:void(0)" id="addToPortal"
                                       class="btn btn-space btn-primary">Add to portal</a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                

                


                

                
                <input type="hidden" value="<?php echo e($getServiceDetails->customer_id); ?>" name="customer_id">

            </form>
        </div>
    </div>


    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>


    <script type="text/javascript">

        $('body').on('click', '#accesswithrequest', function () {

            console.log('show');
            $('.shippingDiv').show();
        });


    </script>
    <script type="text/javascript">

        $('body').on('click', '#accessonly', function () {
            console.log('hide');

            $('.shippingDiv').hide();
        });


    </script>
    <script>
        $('body').on('change', '.change_service_plan', function () {
            var cus_service_model_id = $(this).attr('unq_id');
            var current_row = $(this).attr('current_row');
            var service_plan_id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                data: {cus_service_model_id: cus_service_model_id, service_plan_id: service_plan_id},
                url: "<?php echo e(url("admin/pricingValue")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        $('#service_price_' + cus_service_model_id + '_' + current_row).text(json.data.price);
                        $('#service_price_id_' + cus_service_model_id + '_' + current_row).val(json.data.id);
                        $.toast({
                            heading: 'Alert',
                            text: "Price has been changed",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });
                    } else {
                        $('#service_price_id_' + cus_service_model_id + '_' + current_row).val('');
                        $('#service_price_' + cus_service_model_id + '_' + current_row).text(0);
                        $.toast({
                            heading: 'Warning',
                            text: "No price value added for this combination",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loader: false
                        });


                    }
                }
            });
        });

        $('body').on('click', '#addToPortal', function () {


            var datastring = $('#servicesForm').serialize();
            var inputs = $(".serviceList");


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                data: datastring,
                url: "<?php echo e(url("admin/addToPortal")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        console.log(json.reqId)

                        if (json.value == 1) {
                            var msg = 'Login created successfully.';
                            window.location.replace('buyservice');
                        } else {
                            var msg = 'Service request created sucessfully.';
                            window.location = '/novamed/admin/requestViewDetails/' + json.reqId;
                            //window.location = '/admin/requestViewDetails/' + json.reqId;
                        }


                        $.toast({
                            heading: 'Alert',
                            text: msg,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loader: false
                        });
                    } else {

                        $.toast({
                            heading: 'Warning',
                            text: "Something went wrong.Please checkout orders",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loader: false
                        });


                    }
                }
            });
        });

    </script>
    <script type="text/javascript">

        // $('body').on('click', '.changeFrequency', function () {
        //     var value = $(this).val();
        //     var modelId = $(this).attr('data-id');
        //     var val = $(this).attr('data-val');
        //     console.log(value)
        //     console.log(modelId)
        //     console.log(val)
        //
        //     if (value == 4) {
        //
        //
        //         var popup = document.getElementById("myPopup-" + modelId + "-" + val);
        //         popup.classList.toggle("show");
        //
        //         // $('.exactDate-' + modelId).show();
        //         // $('.exactTextDate-' + modelId + '-' + val).show();
        //         // $('.nextDueDate-' + modelId).hide();
        //         // $('.nextDueTextDate-' + modelId + '-' + val).hide();
        //     } else {
        //         console.log('exit')
        //         // var popup = document.getElementById("myPopup-"+modelId+"-"+val);
        //         // popup.classList.toggle("close");
        //         console.log("myPopup-" + modelId + "-" + val)
        //         $("#myPopup-" + modelId + "-" + val).hide();
        //         $("#myPopup-" + modelId + "-" + val).removeClass('show');
        //         // $('.nextDueDate-' + modelId).show();
        //         // $('.nextDueTextDate-' + modelId + '-' + val).show();
        //         // $('.exactDate-' + modelId).hide();
        //         // $('.exactTextDate-' + modelId + '-' + val).hide();
        //     }
        // });


        $('body').on('click', '.changeFrequency', function () {
            var value = $(this).val();
            var modelId = $(this).attr('data-id');
            var val = $(this).attr('data-val');

            if (value == 1) {

                var month = 3;
            } else if (value == 2) {
                var month = 6;
            } else if (value == 3) {
                var month = 12;
            }


            if (value == 4) {


                $('#nextDue-' + modelId + "-" + val).val('');

                var popup = document.getElementById("myPopup-" + modelId + "-" + val);
                popup.classList.toggle("show");

                $("#myPopup-" + modelId + "-" + val).show();


            } else {


                var date = new Date();
                console.log(date)
                date.setMonth(date.getMonth() + parseInt(month));

                console.log(date)

                // var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);


                // var nexDue = dateFormat(new Date(lastDay), 'mm/dd/yyyy');
                // var insVal = dateFormat(new Date(lastDay), 'yyyy-mm-dd');


                $('#exactDate-' + modelId + "-" + val).val('');


                var nexDue = $.datepicker.formatDate('mm/dd/yy', date);

                console.log(nexDue)

                $('#nextDue-' + modelId + "-" + val).val(nexDue);

                console.log("myPopup-" + modelId + "-" + val)
                $("#myPopup-" + modelId + "-" + val).hide();
                $("#myPopup-" + modelId + "-" + val).removeClass('show');

            }
        });
    </script>

    <script>
        $(".telephone").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <div>
        <button data-modal="colored-deletewarning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-deletewarning deletePopUp">Warning
        </button>
    </div>
    <div id="colored-deletewarning"
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
                    <p>Please fill all the required fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>
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
                <input type="hidden" value="<?php echo $getServiceDetails->id; ?>" id="updateServiceRequestPropertyId">
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
        <div class="modal-dialog customerpopup">
            <div class="modal-content cuustomercontent">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close md-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title"><span id="">Customer Detail</span></h3>
                </div>

                <div class="modal-body" id="detailsForm">

                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary right"
                       id="updateCustomerDetailClick">Update</a>
                    <span style="display: none" id="spinLoader"><i
                                class="fa fa-spinner fa-spin inside-ico"></i></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.exactdatepicker')
                .datepicker({
                    format: 'mm/dd/yyyy',
                    orientation: "bottom",
                    //startDate: date
                })
                .on('changeDate', function (e) {
                    var value = $(this).val();
                    var attr = $(this).attr('attr');
                    $('#' + attr).val(value);
                    $(this).datepicker('hide');
                });


            $('.datepickerselect')
                .datepicker({
                    format: 'mm/dd/yyyy',
                    orientation: "top",
                    //startDate: date
                })
                .on('changeDate', function (e) {
                    $(this).datepicker('hide');
                });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>