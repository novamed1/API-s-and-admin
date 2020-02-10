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
            <td><img src="<?php echo e(asset('img/logo.png')); ?>" width="100px"></td>

        </tr>
    </table>
    <table>
        <tr>
            <td width="70%">
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Novamed INC, 8136
                    Lawndale</p>
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Ave, Skokie, IL
                    60076-3413,</p>
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Tel:
                    1-800-354-6676,</p>
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Fax:
                    1-847-675-3322,</p>
                <p style="font-size: 11px;color:#333;text-transform: uppercase;margin: 0;padding: 0;">Email:
                    support@novamed.com</p>
            </td>
            <td style="text-align: left;vertical-align: top;">
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Invoice date:</span> <?php echo e(date('m-d-Y',strtotime(str_replace('/','-',$data['purchase']->payment_date)))); ?>

                </p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">PO#:</span> <?php echo e($data['purchase']->order_number); ?>

                </p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Payment Terms:</span></p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Payment Due By:</span></p>
            </td>
        </tr>
    </table>
    <table style="background: #f5f5f5;">
        <tr>
            <td style="font-size: 14px;color:#000;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 600;">
                INVOICE
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td width="60%">
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Bill To :</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['billing']->billing_contact); ?>

                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['billing']->address1); ?>

                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['billing']->city); ?></p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['billing']->state); ?></p>
            </td>
            <td style="text-align: left;vertical-align: top;">
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Ship To :</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['shipping']->customer_name); ?>

                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['shipping']->address1); ?>

                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['shipping']->city); ?></p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;"><?php echo e($data['shipping']->state); ?></p>
            </td>
        </tr>
    </table>
    <table style="border-top: solid thin #ddd;border-bottom: solid thin #ddd;">
        <tr>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Customer Name</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e($data['purchase']->customer_name); ?></h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Payment Type</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e($data['purchase']->name); ?></h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Work Order</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">
                    #254563</h3>
            </td>
            <td>
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Purchase Order</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;"><?php echo e($data['purchase']->order_number); ?></h3>
            </td>
        </tr>

    </table>

    <div style="padding: 10px;">
        <table style="border: solid thin #ddd;border-bottom: 0px;">
            <tr style="background: #cecece;">
                <th style="border-right:solid thin #ddd;" width="4%"></th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="25%">Model Name#
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Type#
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Qty
                </th>
                
                    
                
                
                    
                
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Price
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid 0px #ddd;font-weight:500;"
                    width="21%">Amount
                </th>
            </tr>

            <?php if(count($orderItems)): ?>
                <?php $i = 1; ?>
                <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr style="border-bottom: solid thin #ddd;">
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($i); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row->name); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row->type); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"><?php echo e($row->quantity); ?></td>
                        
                        
                            
                        
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                            $ <?php echo e(number_format($row->price,2)); ?></td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;">
                            $ <?php echo e(number_format($row->total_price,2)); ?></td>
                    </tr>
                    
                        
                        
                            
                            
                        
                    

                            <tr style="border-bottom: solid 0px #ddd;background: #eceaea;">
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">

                                </td>
                                <td style="font-size:12px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;">

                                </td>
                            </tr>



                    <?php $i++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <?php endif; ?>


            <tr style="border-top: solid thin #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="5" rowspan="4"
                    style="font-size:12px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;">
                    <p style="font-size: 11px;color:#666;">Comments</p>
                    <p style="font-size: 11px;color:#999;"><?php echo e($comments['comments']); ?></p>
                </td>
                <td colspan="2"
                    style="font-size:11px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Sub Total
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    $ <?php echo e(number_format($totalCost,2)); ?>

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
                    $ <?php echo e(number_format($totalCost,2)); ?>

                </td>
            </tr>
        </table>
    </div>


</div>

</body>

</html>
