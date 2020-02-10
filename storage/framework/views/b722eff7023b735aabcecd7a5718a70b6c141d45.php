<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Novamed </title>

    <style type="text/css">

        body { font-family: 'Port Lligat Slab', serif; margin: 2mm; }
        * { box-sizing: border-box; }
        p,h1,h2,h3,h4,h5,h6 { margin:0; padding: 0; }

        .page-break{

            page-break-after: always;
        }
    </style>
</head>
<body>

<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Helvetica","Arial","sans-serif");
        
    
    
    $pdf->page_text(250,670,"We appreciate your business.",$font,8,array(0,0,0));
    $pdf->page_text(200,680,"VISA/MasterCard/AMEX and ACH payments are accepted.",$font,8,array(0,0,0));
    $pdf->page_text(2,710,"Terms and Conditions",$font,8,array(0,0,0));
    $pdf->page_text(2,720,"Please visit www.novamed1.com/terms-and-conditons.",$font,8,array(0,0,0));
    $pdf->page_text(2,740,"Payment Terms : Net 30 days from Invoice Date, unless otherwise specified",$font,8,array(0,0,0));
    $pdf->page_text(28,750,"Shipping : Flat shipping charges are applied, if applicable",$font,8,array(0,0,0));
    $pdf->page_text(41,760,"Taxes : Applicable state/local taxes, if applicable, are additional",$font,8,array(0,0,0));
    $pdf->page_text(23,770,"Insurance : Buyer responsibility, unless specified",$font,8,array(0,0,0));

    $pdf->page_text(320,740,"Remit to",$font,8,array(0,0,0));
    $pdf->page_text(320,750,"Novamed Inc.,",$font,8,array(0,0,0));
    $pdf->page_text(320,760,"8136 N Lawndale Ave.,",$font,8,array(0,0,0));
    $pdf->page_text(320,770,"Skokie,L 60076",$font,8,array(0,0,0));

    $pdf->page_text(500,740,"ACH : JP Morgan Chase",$font,8,array(0,0,0));
    $pdf->page_text(480,750,"ABA Routing : 071 000 013",$font,8,array(0,0,0));
    $pdf->page_text(490,760,"ABA Wire : 121000418",$font,8,array(0,0,0));
    $pdf->page_text(490,770,"Account# : 6785000004366",$font,8,array(0,0,0));


      }
</script>

<table width="100%">
    <tr>
        <td width="25%" valign="top">
            <p><img src="<?php echo e(asset('img/reportlogo.png')); ?>"></p>
            <p style="font-size: 14px;font-weight: normal;color: #333333;line-height: 1.4;">Novamed Inc.      <br>
                8136 N Lawndale Ave., <br>
                Skokie, IL 60076 USA <br>
                Tel: +1 (800) 354-6676 <br>
                Fax: +1 (847) 675-3322 <br>
                www.novamed1.com <br>
                TIN: 36-3750788</p>
        </td>
        <td width="50%"></td>
        <td width="40%" valign="top" style="margin-left: 40px;">
            <h2 style="font-size: 24px;font-weight: normal;color: #000;line-height: 1.4;text-align: left;padding-right: 0px;">Invoice</h2>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Invoice Number</span> <?php echo e((isset($invoiceNum)&&$invoiceNum)?$invoiceNum:''); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Invoice Date</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e(date('m-d-Y',strtotime(str_replace('-','/',$order->invoice_date)))); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Terms</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e((isset($customer->pay_terms)&&$customer->pay_terms)?$customer->pay_terms:''); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Order#</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e($order->order_number); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;margin-bottom: 10px;"><span style="color: #262222;">Purchase Order</span>&nbsp;&nbsp;&nbsp; <?php echo e((isset($customerPoNum)&&$customerPoNum)?$customerPoNum:''); ?> </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> For questions about your invoice, contact us </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Phone +1 (800) 354-6676 </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Fax   +1 (847) 675-3322 </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Email   ar@novamed1.com </p>
        </td>
    </tr>
</table>

<table width="100%" style="margin-top: 20px;">
    <tr>
        <td width="65%" valign="top">
            <p style="font-size: 14px;padding: 10px;font-weight: normal;color: #333333;">
                <span style="font-size: 14px;font-weight: bold;color: #262222;line-height: 1.4;">Bill To</span>   <br>
                <?php echo e($customer->customer_name); ?> <br>
                Attn: <?php echo e($billing->billing_contact); ?><br>
                <?php echo e($billing->address1); ?> <br>
                <?php if(isset($billing->address2)&&$billing->address2): ?>
                    <?php echo e($billing->address2); ?> <br>
                <?php endif; ?>
                <?php echo e($billing->city.','.$billing->state.' '.$billing->zip_code); ?>  <br>
                Tel: +1 <?php echo e($billing->phone); ?> <br>
                <?php echo e($billing->email); ?></p>
        </td>
        <td width="35%" valign="top">
            <p style="font-size: 14px;font-weight: bold;color: #262222;line-height: 1.4;">Ship To</p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($customer->customer_name); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;">Attn: <?php echo e($prefContactName); ?></p>

            <?php

            if($customer->customer_type_id==2)
            {
            $roomNo = $shipping->room_no?$shipping->room_no:'---';
            $buildingName = $shipping->building_name?$shipping->building_name:'---';
            $mailCode = $shipping->mail_code?$shipping->mail_code:'---';
            ?>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($roomNo.', '.$buildingName.', '.$mailCode); ?> </p>
            <?php  }
            ?>

            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($shipping->address1); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($shipping->address2); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($shipping->city.','.$shipping->state.','.$shipping->zip_code); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"> Tel: +1 <?php echo e($shipping->phone_num); ?></p>
        </td>
    </tr>
</table>

<table width="100%" style="margin-top: 20px;">
    <tr>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;"><span style="color: #262222;">Service Request </span>: <?php echo e((isset($serviceRequestNo)&&$serviceRequestNo)?$serviceRequestNo:''); ?></p>
        </td>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Work Order  </span>: <?php echo e((isset($workOrderNo)&&$workOrderNo)?$workOrderNo:''); ?>


            </p>

        </td>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Service Date  </span>:   <?php echo e((isset($serviceDate)&&$serviceDate)?$serviceDate:''); ?>

            </p>

        </td>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Total Instruments </span>: <?php echo e((isset($orderItems)&&$orderItems)? count($orderItems) :''); ?>

            </p>

        </td>
    </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Line Items</th>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Catalog#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Asset#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Serial#</th>
        <th width="40%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Description/Instrument</th>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;padding-right: 10px;text-align:right;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Price</th>
    </tr>

    <?php if(count($orderItems)): ?>
        <?php $i = 1; ?>
        <?php $p = 1 ?>
        <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

            <tr>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($i); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['service_plan']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['asset_no']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['serial_no']); ?></td>
                <td style="font-family: 'Port Lligat Slab','DejaVu Sans';font-size: 12px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['model_description']); ?></td>
                <td style="font-size: 14px;padding:10px 10px 10px 0;text-align:right;font-weight: normal;color: #000;">$ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>

            </tr>
                <?php if( $p%4==0 ){

                    echo '<div class="page-break"></div>';
                } ?>
                <?php $p++ ?>

                <div class="clearfix"></div>

            <?php if($row['partdetails']): ?>
                <?php $j = 1; ?>
                <?php $__currentLoopData = $row['partdetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sparekey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($i.'.'.$j); ?></td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($sparekey['SKU']); ?></td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;">N/A</td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;">N/A</td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($sparekey['partName']); ?></td>
                        <td style="font-size: 14px;padding:10px 10px 10px 0;text-align:right;font-weight: normal;color: #000;">$ <?php echo e(number_format($sparekey['totalAmount'],2)); ?></td>
                    </tr>

                    <?php $j++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

            <?php endif; ?>
            <?php if($row['checklistName']): ?>
                <tr>
                    <td colspan="6" style="border-bottom:solid 2px #ddd;font-size: 14px;padding:10px 0 20px;text-align:left;font-weight: normal;color: #818181;">
                        <span style="color: #000;display: inline-block;padding: 0 15px;">Maintenance:</span> <?php echo e($row['checklistName']); ?></span>
                    </td>
                </tr>

            <?php endif; ?>


                <?php $i++; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

    <?php endif; ?>




    <tr>
        <td colspan="5" style="border-top:solid 2px #000; font-size: 14px;padding:15px 0 5px;text-align:right;font-weight: normal;color: #000;">Sub Total</td>
        <td style="border-top:solid 2px #000; font-size: 14px;padding:15px 10px 5px 0;text-align:right;font-weight: normal;color: #000;">$ <?php echo e(number_format($totalAmount,2)); ?></td>
    </tr>
    <tr>
        <td colspan="5" style="font-size: 14px;padding:5px 0;text-align:right;font-weight: normal;color: #000;">Discount(-)</td>
        <td style="font-size: 14px;padding:5px 10px 5px 0;text-align:right;font-weight: normal;color: #000;"><?php if($discount): ?>
                $ <?php echo e(number_format($discount,2)); ?>

            <?php else: ?>
                $ 0.00
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="font-size: 14px;padding:5px 0;text-align:right;font-weight: normal;color: #000;">Shipping & Handling</td>
        <td style="font-size: 14px;padding:5px 10px 5px 0;text-align:right;font-weight: normal;color: #000;"><?php if($shipping_price): ?>
                $ <?php echo e(number_format($shipping_price,2)); ?>

            <?php else: ?>
                $ 0.00
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="font-size: 14px;padding:10px 0;text-align:right;font-weight: normal;color: #000;">Total</td>
        <td style="font-size: 14px;padding:10px 10px 5px 0;text-align:right;font-weight: normal;color: #000;"><?php if($grand_total): ?>
                $ <?php echo e(number_format($grand_total,2)); ?>

            <?php else: ?>
                $ <?php echo e(number_format($totalAmount,2)); ?>

            <?php endif; ?>
        </td>
    </tr>

</table>



</body>
</html>