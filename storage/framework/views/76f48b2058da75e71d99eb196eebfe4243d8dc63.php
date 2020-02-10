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
            <h2 style="font-size: 24px;font-weight: normal;color: #000;line-height: 1.4;text-align: left;padding-right: 0px;">Service Summary</h2>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Service Request#</span> <?php echo e((isset($ServicerequestNO)&&$ServicerequestNO)?$ServicerequestNO:''); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;">Service Date</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e($ServiceRequestDate); ?></p>
            <p style="font-size: 14px;font-weight: normal;color: #6f6f6f;line-height: 1.4;"><span style="color: #262222;"></span></p>
            <p style="font-size: 12px;font-weight: normal;color: #818181;line-height: 1.4;margin-bottom: 0px;"> For questions about your summary, contact us </p>
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
                <?php echo e($customer->customer_name); ?> <br>
                Attn: <?php echo e($billing->billing_contact); ?><br>
                <?php echo e($billing->address1); ?> <br>
                <?php if($billing->address2): ?>
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
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;"><span style="color: #262222;">Service Request </span>: <?php echo e((isset($ServicerequestNO)&&$ServicerequestNO)?$ServicerequestNO:''); ?></p>
        </td>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Work Order  </span>: <?php echo e((isset($workorder)&&$workorder)?$workorder:''); ?>


            </p>

        </td>

        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Total Instruments </span>: <?php echo e((isset($orderItems)&&$orderItems)? count($orderItems) :''); ?>

            </p>

        </td>
        <td width="30%" valign="top">
            <p style="font-size: 14px;font-weight: normal;color: #818181;line-height: 1.9;">
                <span style="color: #262222;">Service Type  </span>:   <?php echo e($serviceType?$serviceType:''); ?>

            </p>

        </td>
    </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 35px;">
    <tr>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Line Items</th>
        <th width="10%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Catalog#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Asset#</th>
        <th width="15%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Serial#</th>
        <th width="50%" style="font-size: 14px;padding-bottom:5px;text-align:left;border-bottom:solid 2px #262222;font-weight: normal;color: #262222;">Description/Instrument</th>

    </tr>
    <?php
    //Columns must be a factor of 12 (1,2,3,4,6,12)
    $numOfCols = 3;
    $rowCount = 0;
    $bootstrapColWidth = 12 / $numOfCols;
    ?>
    <?php if(count($orderItems)): ?>
        <?php $i = 1; ?>
        <?php $p = 0 ?>
        <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <?php $p++ ?>
            <tr>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($i); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['service_plan']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['asset_no']); ?></td>
                <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['serial_no']); ?></td>
                <td style="font-family: 'Port Lligat Slab','DejaVu Sans';font-size: 12px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($row['model_description']); ?></td>

            </tr>


            <?php if($row['partdetails']): ?>
                <?php $j = 1; ?>
                <?php $__currentLoopData = $row['partdetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sparekey): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($i.'.'.$j); ?></td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($sparekey['SKU']); ?></td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;">N/A</td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;">N/A</td>
                        <td style="font-size: 14px;padding:10px 0;text-align:left;font-weight: normal;color: #000;"><?php echo e($sparekey['partName']); ?></td>

                    </tr>

                    <?php $j++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

            <?php endif; ?>
            <?php if($row['checklistName']): ?>
                <tr>
                    <td colspan="5" style="border-bottom:solid 2px #ddd;font-size: 14px;padding:10px 0 20px;text-align:left;font-weight: normal;color: #818181;">
                        <span style="color: #000;display: inline-block;padding: 0 15px;">Maintenance:</span> <?php echo e($row['checklistName']); ?></span>
                    </td>
                </tr>

            <?php endif; ?>
            <?php $i++; ?>
            <?php
            $rowCount++;
            if ($rowCount % $numOfCols == 0) {
                echo '</tr><tr style="padding-top: 10px">';
            }
            ?>
            <?php if( $p % 10 == 0 ){
                echo '<div class="page-break"></div>';
            } ?>
            <div class="clearfix"></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

    <?php endif; ?>


    <div class="clearfix"></div>


</table>



</body>
</html>