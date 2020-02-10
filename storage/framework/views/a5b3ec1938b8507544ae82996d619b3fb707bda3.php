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
            /*page-break-inside: avoid;*/
        }
    </style>
</head>
<body>

<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Helvetica","Arial","sans-serif");
        
    
    
    $pdf->page_text(250,670,"Thank you for your inquiry, we appreciate your business.",$font,10,array(0,0,0));
    $pdf->page_text(200,680,"VISA/MasterCard/AMEX and ACH payments are accepted.",$font,10,array(0,0,0));
    $pdf->page_text(2,710,"Terms and Conditions",$font,10,array(0,0,0));
    $pdf->page_text(2,730,"This quote is subject to our terms and condtions. Sales or use taxes are not included, if applicable.",$font,10,array(0,0,0));
    $pdf->page_text(2,740,"Please visit www.novamed1.com/terms-and-conditons.",$font,10,array(0,0,0));
    $pdf->page_text(2,750,"Payment Terms : Net 30 days from Invoice Date, unless otherwise specified",$font,10,array(0,0,0));
    $pdf->page_text(28,760,"Shipping : Flat shipping charges are applied, if applicable",$font,10,array(0,0,0));
    $pdf->page_text(41,770,"Taxes : Applicable state/local taxes, if applicable, are additional",$font,10,array(0,0,0));
    $pdf->page_text(23,780,"Insurance : Buyer responsibility, unless specified",$font,10,array(0,0,0));

    $pdf->page_text(320,740,"Remit to",$font,10,array(0,0,0));
    $pdf->page_text(320,750,"Novamed Inc.,",$font,10,array(0,0,0));
    $pdf->page_text(320,760,"8136 N Lawndale Ave.,",$font,10,array(0,0,0));
    $pdf->page_text(320,770,"Skokie,L 60076",$font,10,array(0,0,0));

    $pdf->page_text(500,740,"ACH : JP Morgan Chase",$font,10,array(0,0,0));
    $pdf->page_text(480,750,"ABA Routing : 071 000 013",$font,10,array(0,0,0));
    $pdf->page_text(490,760,"ABA Wire : 121000418",$font,10,array(0,0,0));
    $pdf->page_text(490,770,"Account# : 6785000004366",$font,10,array(0,0,0));


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
            <h2 style="font-size: 24px;font-weight: normal;color: #000;line-height: 1.4;text-align: left;padding-right: 0px;">QUOTE</h2>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Quote Number</span> <?php echo e($qutationnumber); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Quote Date</span>&nbsp;&nbsp; <?php echo e(date('m-d-Y')); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Terms</span><?php echo e($data['paymentTerms']); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Quote Expires </span> <?php echo e(date('m-d-Y', strtotime("+90 days"))); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;"></span></p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> For quesitons about your quote, contact us </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Phone +1 (800) 354-6676 </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Fax   +1 (847) 675-3322 </p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> Email   ar@novamed1.com </p>
        </td>
    </tr>
</table>

<table width="100%" style="margin-top: 20px;">
    <tr>
        <td width="65%" valign="top">
            <p style="font-size: 14px;padding: 10px;font-weight: normal;color: #333333;line-height: 1.4;background-color: #eeeeee;border-radius: 7px;">
                <span style="font-size: 14px;font-weight: bold;color: #262222;line-height: 1.4;">Bill To</span>   <br>
                <?php echo e((isset($data['customer']->customer_name)&&$data['customer']->customer_name)?$data['customer']->customer_name:''); ?> <br>
                Attn: <?php echo e($data['billing']->billing_contact); ?> <br>
                <?php echo e($data['billing']->address1); ?> <br>
                <?php if(isset($data['billing']->address2)&&$data['billing']->address2): ?>
                <?php echo e($data['billing']->address2); ?> <br>
                <?php endif; ?>
                <?php echo e($data['billing']->city.','.$data['billing']->state.' '.$data['billing']->zip_code); ?>  <br>
                Tel: +1 <?php echo e($data['billing']->phone); ?> <br>

                <?php echo e($data['billing']->email); ?></p>
        </td>
        <td width="35%" valign="top">
            <p style="font-size: 14px;font-weight: bold;color: #262222;line-height: 1.4;">Ship To</p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e((isset($data['customer']->customer_name)&&$data['customer']->customer_name)?$data['customer']->customer_name:''); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;">Attn: <?php echo e($data['prefContactName']); ?></p>
            <?php

                if($data['customer']->customer_type_id==2)
                    {
                        $roomNo = $data['shipping']->room_no?$data['shipping']->room_no:'---';
                        $buildingName = $data['shipping']->building_name?$data['shipping']->building_name:'---';
                        $mailCode = $data['shipping']->mail_code?$data['shipping']->mail_code:'---';
                        ?>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($roomNo.', '.$buildingName.', '.$mailCode); ?> </p>
                   <?php  }
            ?>


            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($data['shipping']->address1); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($data['shipping']->address2); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"><?php echo e($data['shipping']->city.','.$data['shipping']->state.','.$data['shipping']->zip_code); ?> </p>
            <p style="font-size: 14px;font-weight: normal;color: #262222;line-height: 1.4;"> Tel: +1 <?php echo e($data['shipping']->phone_num); ?></p>
        </td>
    </tr>
</table>



<table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 45px;">
    <tr>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Line Items</th>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Catalog#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Asset#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Serial#</th>
        <th width="40%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Description/Instrument</th>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;padding-right: 10px;text-align:right;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Price</th>
    </tr>
    <?php
    //Columns must be a factor of 12 (1,2,3,4,6,12)
    $numOfCols = 3;
    $rowCount = 0;
    $bootstrapColWidth = 12 / $numOfCols;
    ?>
    <?php if(count($items)): ?>
        <?php $i = 1; ?>
        <?php $p = 1 ?>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row1): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <?php if(count($row1['lineItems'])): ?>
                    <?php $__currentLoopData = $row1['lineItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <?php $p++ ?>

            <tr>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($i); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['service_plan']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['asset_no']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['serial_no']); ?></td>
                <td style="font-family: 'Port Lligat Slab','DejaVu Sans';font-size: 12px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['model_description']); ?></td>
                <td style="font-size: 14px;padding:10px 10px 10px 0;text-align:right;font-weight: normal;color: #000;">$ <?php echo e(number_format($row['order_item_amt'],2)); ?></td>
            </tr>

            <?php $i++; ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

    <?php endif; ?>


    <div class="clearfix"></div>

    <tr>
        <td colspan="5" style="border-top:solid 2px #000; font-size: 14px;padding:15px 0 5px;text-align:right;font-weight: normal;color: #000;">Sub Total</td>
        <td style="border-top:solid 2px #000; font-size: 14px;padding:15px 10px 5px 0;text-align:right;font-weight: normal;color: #000;">$ <?php echo e(number_format($totalCost,2)); ?></td>
    </tr>

    <tr>
        <td colspan="5" style="font-size: 14px;padding:5px 0;text-align:right;font-weight: normal;color: #000;">Shipping & Handling</td>
        <td style="font-size: 14px;padding:5px 10px 5px 0;text-align:right;font-weight: normal;color: #000;"><?php if($shippingCharge): ?>
                $ <?php echo e(number_format($shippingCharge,2)); ?>

            <?php else: ?>
                $ 0.00
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="font-size: 14px;padding:10px 0;text-align:right;font-weight: normal;color: #000;">Total</td>
        <td style="font-size: 14px;padding:10px 10px 5px 0;text-align:right;font-weight: normal;color: #000;"><?php if($GrandTotal): ?>
                $ <?php echo e(number_format($GrandTotal,2)); ?>

            <?php else: ?>
                $ <?php echo e(number_format($totalCost,2)); ?>

            <?php endif; ?>
        </td>
    </tr>

</table>



</body>
</html>