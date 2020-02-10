<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title></title>


    <style type="text/css">
        body {
            padding: 0mm 0 0mm 0;
            margin: 0;
            font-size: 10px;
            font-family: Open Sans, Source Sans Pro, Helvetica, Arial, sans-serif;
            box-sizing: border-box;
        }

        .wrapper {
            padding: 0 0mm 0mm;
            width: 100%;
            box-sizing: border-box;
            margin-top: 1mm;
            float: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td {
            padding: 5px;
            vertical-align: middle;
            font-size: 14px;
        }

        p {
            margin: 0;
            font-size: 10px;
        }
    </style>

</head>

<body>

<div class="wrapper">

    <table>
        <tr>
            <td><img src="<?php echo e(asset('img/reportlogo.png')); ?>" width="100px"></td>

        </tr>
    </table>
    <table>
        <tr>
            <td width="70%">
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Novamed INC, 8136
                    Lawndale,Ave, Skokie, IL
                    60076-3413,</p>
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Tel:
                    1-800-354-6676,Fax:
                    1-847-675-3322,Email:
                    support@novamed.com</p>

            </td>
            <td style="text-align: left;vertical-align: top;">
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Invoice date:</span> <?php echo e(date('m-d-Y',strtotime(str_replace('/','-',$order->invoice_date)))); ?>

                </p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">PO#:</span> <?php echo e($order->order_number); ?>

                </p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Payment Terms:</span><?php echo e((isset($customer->pay_terms)&&$customer->pay_terms)?$customer->pay_terms:''); ?></p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Payment Due By:</span><?php echo e(date('m-d-Y',strtotime('+30 days'))); ?></p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="60%">
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Bill To : <span style="font-size: 12px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($billing->billing_contact.', '.$billing->address1.', '.$billing->city.', '.$billing->state); ?>

                    ,</span></p><br/>
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Ship To : <span style="font-size: 12px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($shipping->customer_name.', '.$shipping->address1.', '.$shipping->city.', '.$shipping->state); ?>

                        ,</span></p>


            </td>

        </tr>
    </table>
    <table style="border-top: solid thin #ddd;border-bottom: solid thin #ddd;">
        <tr>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Customer Id</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($customer->unique_id)&&$customer->unique_id)?$customer->unique_id:''); ?></h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Payment Type</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($customer->name)&&$customer->name)?$customer->name:''); ?></h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Work Order</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($workOrderNo)&&$workOrderNo)?$workOrderNo:''); ?></h3>
            </td>
            <td>
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Purchase Order</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($orderNo)&&$orderNo)?$orderNo:''); ?></h3>
            </td>
        </tr>
        <tr style="border-top: solid thin #ddd;">
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Service Location</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: capitalize;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">---</h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Service Request</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: capitalize;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($serviceRequestNo)&&$serviceRequestNo)?$serviceRequestNo:''); ?></h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Service Date</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: capitalize;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($serviceDate)&&$serviceDate)?$serviceDate:''); ?></h3>
            </td>
            <td>
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">Ship Date</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: capitalize;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e((isset($shipDate)&&$shipDate)?$shipDate:''); ?></h3>
            </td>
        </tr>
    </table>


    <div style="padding: 10px;">
        <table style="border: solid thin #ddd;border-bottom: 0px;">
            <tr style="background: #cecece;">
                <th style="border-right:solid thin #ddd;" width="4%"></th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="13%">Catalogue#
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="13%">Item#
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="13%">Serial#
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="31%">Description
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="5%">Qty
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="10%">Price/Unit
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid 0px #ddd;font-weight:500;"
                    width="10%">Amount
                </th>
            </tr>

            <?php if(count($orderItems)): ?>
                <?php $i = 1; ?>
                <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr style="border-bottom: solid thin #ddd;">
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($i); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row['asset_no']); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row['serial_no']); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row['model_name']); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                            1
                        </td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                            $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;">
                            $ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>
                    </tr>

                    <?php if($row['partdetails']): ?>
                            <tr style="border-bottom: solid 0px #ddd;background: #eceaea;">
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"></td>
                                <td colspan="7"
                                    style="font-size:12px;text-transform:uppercase;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    Spare Parts / Accessories for Above Item
                                </td>
                            </tr>
                        <?php $__currentLoopData = $row['partdetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sparekey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr style="border-bottom: solid 0px #ddd;background: #eceaea;">
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['serialNumber']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['SKU']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['spareMode']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['spareMode']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['partName']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    <?php echo e($sparekey['totalQuantity']); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                                    $ <?php echo e(number_format($sparekey['partPrice'],2)); ?>

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;">
                                    $ <?php echo e(number_format($sparekey['totalAmount'],2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    <?php endif; ?>


                    <?php $i++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <?php endif; ?>


            <tr style="border-top: solid thin #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="5" rowspan="4"
                    style="font-size:12px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;">
                    <p style="font-size: 11px;color:#666;">Comments</p>
                    <p style="font-size: 11px;color:#999;"><?php echo e($comments); ?></p>
                </td>
                <td colspan="2"
                    style="font-size:11px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Sub Total
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    $ <?php echo e(number_format($totalAmount,2)); ?>

                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="2"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Shipping &amp; Handling
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    $0.00
                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="2"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Sales Tax
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    $0.00
                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="2"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;font-weight: 700;">
                    Total
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;font-weight: 700;">
                    $ <?php echo e(number_format($totalAmount,2)); ?>

                </td>
            </tr>
        </table>
    </div>


</div>

</body>

</html>
