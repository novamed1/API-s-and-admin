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
            <h2>Order Details (Number :<span></span> <?php echo e($order->order_number); ?>) Total Amount : <i class="fa fa-dollar"
                                                                                                 aria-hidden="true"></i>
                <?php if($order->grand_total): ?>
                <?php echo e($order->grand_total); ?>

                <?php else: ?>
                <?php echo e($totalAmount); ?>

                <?php endif; ?>
            </h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Order & Payment Management</a></li>
                <li><a href="#">Order Requests</a></li>
                <li><a href="#">Detail Page</a></li>
            </ol>

            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/orderrequests')); ?>" class="btn btn-space btn-primary"><-- Go Back</a>
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
                            <input type="hidden" value="<?php echo e($order->id); ?>" name="orderId" id="orderId"/>

                            <div class="sms-content-section div-section" id="pageResult">


                                <div class="campaign-wrapper" id="userPage">
                                    <?php if($customer): ?>
                                        <div class="">
                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Customer Details
                                                        <span class="right" id="editCustomerProperty"><a
                                                                    href="javascript:void(0)" id="CustomerPropertyEdit"
                                                                    data-id="<?php echo e($customer->id); ?>"
                                                                    data-attr="customer" class="cusomerDetailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinCustomerProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName"><?php echo e($customer->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1"><?php echo e($customer->address1); ?></span>
                                                            <br><span
                                                                    id="changedCustomerCity"><?php echo e($customer->city); ?></span>
                                                            <br><span
                                                                    id="changedCustomerState"><?php echo e($customer->state); ?></span>
                                                            <br><span
                                                                    id="changedCustomerZipcode"><?php echo e($customer->zip_code); ?></span>
                                                            <br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel"><?php echo e($customer->customer_telephone); ?></span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail"><?php echo e($customer->customer_email); ?></span></a>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Billing Details
                                                        <span class="right" id="editBillingProperty"><a
                                                                    href="javascript:void(0)" id="billingPropertyEdit"
                                                                    curr-id="<?php echo e($billing->id); ?>"
                                                                    customer-id="<?php echo e($billing->customer_id); ?>"
                                                                    data-attr="billing" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinBillingProperty"
                                                              style="display: none;"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="billing_name"><?php echo e($billing->billing_contact); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="billing_address1"><?php echo e($billing->address1); ?></span>
                                                            <br><span
                                                                    id="billing_city"><?php echo e($billing->city); ?></span>
                                                            <br><span
                                                                    id="billing_city"><?php echo e($billing->state); ?></span>
                                                            <br><span
                                                                    id="billing_city"><?php echo e($billing->zip_code); ?></span>
                                                            <br>
                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="billing_phone"><?php echo e($billing->phone); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="billing_email"><?php echo e($billing->email); ?></span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Shipping Details<span class="right"
                                                                                                     id="editShippingProperty"><a
                                                                    href="javascript:void(0)" id="shippingPropertyEdit"
                                                                    curr-id="<?php echo e($shipping->id); ?>"
                                                                    customer-id="<?php echo e($shipping->customer_id); ?>"
                                                                    data-attr="shipping" class="detailsEdit"><i
                                                                        class="fa fa-pencil"></i></a></span>
                                                        <span class="right" id="spinShippingProperty"
                                                              style="display: none"><i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="shipping_name"><?php echo e($shipping->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="shipping_address1"><?php echo e($shipping->address1); ?></span>
                                                            <br><span
                                                                    id="shipping_city"><?php echo e($shipping->city); ?></span>
                                                            <br><span
                                                                    id="shipping_city"><?php echo e($shipping->state); ?></span>
                                                            <br><span
                                                                    id="shipping_city"><?php echo e($shipping->zip_code); ?></span>
                                                            <br><abbr
                                                                    title="Phone">Contact: </abbr><span
                                                                    id="shipping_phone"><?php echo e($shipping->phone_num); ?></span>
                                                        </address>
                                                        <address><strong>Email Id <br></strong><a
                                                                    mailto:#><span
                                                                        id="shipping_email"><?php echo e($shipping->email); ?></span></a>
                                                        </address>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Contact Details</div>
                                                    <div class="panel-body">
                                                        <address>
                                                            <strong><span
                                                                        id="changedCustomerName"><?php echo e($customer->customer_name); ?></span>
                                                                <br>
                                                            </strong><span
                                                                    id="changedCustomeraddress1"><?php echo e($customer->address1); ?></span>
                                                            <br><span
                                                                    id="changedCustomerCity"><?php echo e($customer->city); ?></span>
                                                            <br><span
                                                                    id="changedCustomerState"><?php echo e($customer->state); ?></span>
                                                            <br><span
                                                                    id="changedCustomerZipcode"><?php echo e($customer->zip_code); ?></span>
                                                            <br>

                                                            <abbr title="Phone">Contact: </abbr><span
                                                                    id="changedCustomerTel"><?php echo e($customer->customer_telephone); ?></span>

                                                        </address>
                                                        <address><strong>Email Id<br></strong><a
                                                                    mailto:#><span
                                                                        id="changedCustomerEmail"><?php echo e($customer->customer_email); ?></span></a>
                                                        </address>
                                                    </div>
                                                    
                                                        
                                                            
                                                                
                                                            
                                                            
                                                            
                                                            
                                                            
                                                        
                                                        
                                                                    
                                                        
                                                    
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <div class="">
                                        <div class="col-sm-12">
                                            <div class="widget widget-fullwidth widget-small">
                                                <div class="sms-table-list" id="pageResult">
                                                    <div class="table-responsive noSwipe">
                                                        <table class="table table-fw-widget table-hover"
                                                               style="width:100%">

                                                            <thead>
                                                            <tr>
                                                                <th width="13%">Line Item</th>
                                                                <th width="13%">Asset #</th>
                                                                <th width="13%">Serial #</th>
                                                                <th width="31%">Instrument</th>
                                                                <th></th>
                                                                <th width="10%">Price/Unit </th>
                                                                <th width="10%">Amount</th>


                                                            </tr>
                                                            </thead>


                                                            <tbody class="no-border-x ">
                                                            <?php if(count($orderItems)): ?>
                                                                <?php $i = 1; ?>

                                                                <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                        

                                                                        <tr>
                                                                            <td ><?php echo e($i); ?></td>
                                                                            <td ><?php echo e($row['asset_no']); ?></td>
                                                                            <td ><?php echo e($row['serial_no']); ?></td>
                                                                            <td ><?php echo e($row['model_description']); ?></td>
                                                                            <td></td>
                                                                            <td >
                                                                                $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>
                                                                            <td >
                                                                                $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>


                                                                        </tr>

                                                                    
                                                                
                                                                <?php if(isset($row['checklistName'])): ?>
                                                                    <?php if(($row['checklistName'])): ?>
                                                                        <tr>
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
                                                                                Quantity
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
<?php $i++; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                            <?php endif; ?>

                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            

                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            

                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            

                                                            
                                                            <tr>
                                                                <td colspan="5" rowspan="4"
                                                                    style="font-size:12px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;">
                                                                    
                                                                    
                                                                </td>
                                                                <td colspan="1">
                                                                    Sub Total
                                                                </td>
                                                                <td >
                                                                    $ <?php echo e(number_format($totalAmount,2)); ?>

                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                   >
                                                                    Discount(-)
                                                                </td>
                                                                <td >
                                                                    <?php if($discount): ?>
                                                                        $ <?php echo e(number_format($discount,2)); ?>

                                                                    <?php else: ?>
                                                                        $ 0.00
                                                                    <?php endif; ?>

                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                    >
                                                                    Shipping &amp; Handling
                                                                </td>
                                                                <td >
                                                                    <?php if($shipping_price): ?>
                                                                        $ <?php echo e(number_format($shipping_price,2)); ?>

                                                                    <?php else: ?>
                                                                        $ 0.00
                                                                    <?php endif; ?>

                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td colspan="1"
                                                                    >
                                                                    Total
                                                                </td>
                                                                <td >
                                                                    <?php if($grand_total): ?>
                                                                        $ <?php echo e(number_format($grand_total,2)); ?>

                                                                    <?php else: ?>
                                                                        $ <?php echo e(number_format($totalAmount,2)); ?>

                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>

                                                            </tbody>


                                                        </table>

                                                    </div>
                                                </div>


                                                <div class="panel panel-default"
                                                     style='text-align: center;position: relative;top: 10px;'>
                                                    <div class="panel-body">
                                                        <div class="text-center download">
                                                            <?php if($orderDetails->po_document): ?>
                                                                <a href="<?php echo e(url('admin/downloadCustomerPO/'.$order->id)); ?>"
                                                                   class="dload">Download <br> Customer's PO</a>
                                                                
                                                                
                                                                
                                                                
                                                            <?php endif; ?>

                                                            <?php if($order->invoice_generation==1): ?>
                                                                <a target="_blank"
                                                                   href="<?php echo e(url('admin/openinvoice/'.$order->id.'')); ?>"
                                                                   class="btn btn-space btn-primary right invoicebtn"><i
                                                                            class="fa fa-eye"></i> View Invoice</a>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-space btn-primary right invoiceGeneration invoicebtn"
                                                                   id="<?php echo e($order->id); ?>" data-toggle="modal"
                                                                   data-target="#form-bp1"><i class="fa fa-file"></i>
                                                                    Generate Invoice</a>
                                                                
                                                            <?php endif; ?>
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

        <div id="form-bp1" data-toggle="modal" tabindex="-1" role="dialog"
             class="modal fade modal-colored-header">
            <div class="modal-dialog custom-width">

                <div class="modal-content">
                    <div class="modal-header"
                         style="background-color: #003162;color: #ffffff; padding: 20px 15px;">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close md-close"><i class="icon s7-close"></i></button>
                        
                        
                        <h3 class="modal-title" style="text-align: center; ">
                            Comments for invoice</h3>
                    </div>
                    
                    
                    
                    
                    

                    <form id="addTech" action="<?php echo e(url('admin/generateinvoice/'.$order->id)); ?>" method="get"
                          data-parsley-validate="" novalidate="">

                        
                        <div class="modal-body form">


                            <div class="form-group"><br></br>
                                <label>Comments</label>

                                <?php echo e(Form::textarea('comments', null, ['class' => 'form-control','id'=>'comments'])); ?>


                            </div>
                            <input type="hidden" name="id" id="order_id" value="<?php echo e($order->id); ?>">

                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal"
                                        class="btn btn-default md-close">
                                    Cancel
                                </button>

                                <button type="submit" id=""
                                        class="btn btn-primary">
                                    Proceed
                                </button>
                            </div>

                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        

                        
                        

                        
                        

                        
                        
                        
                        
                        

                        
                        
                        
                        
                        
                        

                        
                        

                        

                        


                        

                    </form>

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
                    <input type="hidden" value="<?php echo $order->id; ?>" id="updateServiceRequestPropertyId">
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
        <script>
            $("form").submit(function (e) {
                e.preventDefault();
                var comments = $('#comments').val();
                if (comments == '') {
                    $.toast({
                        heading: 'Warning',
                        text: 'Comments should not be empty',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        icon: 'error',
                        bgColor: '#ef6262',

                        loader: false
                    });
                    return false;
                } else {
                    $("form").submit();
                }
            });
            $('#shipping_cost').bind('keyup paste', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                var shipping_cost = $(this).val();
                var sub_total = $('#sub-total').val();
                var service_tax = $('#service_tax').val();
                if (service_tax) {
                    var totalcost = parseInt(sub_total) + parseInt(shipping_cost) + parseInt(service_tax);
                } else {
                    var totalcost = parseInt(sub_total) + parseInt(shipping_cost);
                }
                $('#total').val(totalcost);
            });
            $('#service_tax').bind('keyup paste', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                var service_tax = $(this).val();
                console.log(service_tax);
                var sub_total = $('#sub-total').val();
                var shipping_cost = $('#shipping_cost').val();
                if (shipping_cost) {
                    var totalcost = parseInt(sub_total) + parseInt(service_tax) + parseInt(shipping_cost);
                } else {
                    var totalcost = parseInt(sub_total) + parseInt(service_tax);

                }
                // console.log(totalcost);
                $('#total').val(totalcost);
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
        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #ef6262 !important;
                color: white !important;
            }


        </style>
        <div>
            <button data-modal="colored-warning" style="display:none;"
                    class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
            </button>
        </div>





<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>