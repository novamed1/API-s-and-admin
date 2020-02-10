<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Sample Pipette Calibration Certificate</title>

    <style type="text/css">
        body { padding: 0mm; margin: 0; font-size: 10px; color: #000; font-family: Open Sans,Source Sans Pro,Helvetica,Arial,sans-serif;  box-sizing: border-box; }
        .wrapper { padding: 0 0mm 0mm; width: 100%; box-sizing: border-box; border: solid 0px #000; }

    </style>

</head>

<body>

<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Arial", "bold");
        $pdf->page_text(520, 720, "Certificate# 1014-1123", $font, 7, array(0, 0, 0));
        $pdf->page_text(555, 740, "Page {PAGE_NUM}/{PAGE_COUNT}", $font, 7, array(0, 0, 0));

      }
      </script>

<div class="wrapper">

    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
        <tr>
            <td width="20%" style="padding:2px 2px 2px 20px"> <img src="<?php echo e(asset('img/reportlogo.png')); ?>"> </td>
            <td width="60%" style="padding:2px">
                <h3 style="text-transform:uppercase;text-align:center;font-size:10pt;font-weight:700;margin:0;padding:0;">Novamed INC</h3>
                <h3 style="text-align:center;font-size:10pt;font-weight:700;margin:0;padding:0;">Consolidated Certificate of pipette calibration</h3>
            </td>
            <td width="20%" style="padding:2px;vertical-align:bottom;">

            </td>
        </tr>
    </table>

    <div style="border-bottom: solid 0px #ccc;border-top: 0px;margin-top:5px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: solid 1px #ccc;">
            <tr style="background: #ccc;">
                <td colspan="2" width="50%" style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0px #000"><h3 style="text-transform:uppercase;text-align:left;font-size:8pt;font-weight:700;margin:0;">Customer Details</h3></td>
                <td colspan="2" width="50%" style="border-bottom:solid 0px #000;padding:2px;border-right:solid 0px #000"><h3 style="text-transform:uppercase;text-align:left;font-size:8pt;font-weight:700;margin:0;">Environmental Conditions</h3></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Customer <span style="float:right; padding-right: 5pt">:</span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($customer->customer_name); ?></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Water Temperature <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;"><sup>o</sup>C</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->water_temperature_calibrated); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Contact <span style="float:right; padding-right: 5pt">:</span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($customer->primary_contact); ?></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Barometric Pressure <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;">KPa</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->barometric_pressure_calibrated); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Address <span style="float:right; padding-right: 5pt">:</span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($customer->address1); ?> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Relative Humidity <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;">&</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->relevent_humidity_calibrated); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Telephone <span style="float:right; padding-right: 5pt">:</span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($customer->customer_telephone); ?> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Air Density <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;">(g/cm3)</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->air_dencity_calibrated); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;"> <span style="float:right; padding-right: 5pt"></span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Liquid Density <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;">(gm/ml)</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->liquid_dencity_calibrated); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;"> <span style="float:right; padding-right: 5pt"></span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Z Factor <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span>  <span style="float: right;">(cm3/g)</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($workorder->z_factor_calibrated); ?></td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: solid 1px #ccc;">

            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Report Date <span style="float:right; padding-right: 5pt">:</span></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e(date('d-M-Y')); ?></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Technician <span style="float:right; padding-right: 5pt;padding-left: 5pt">:</span> </td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($technician->first_name.' '.$technician->last_name); ?></td>
            </tr>
            <tr>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;"></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;"></td>
                <td width="25%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt;text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"></td>
            </tr>
        </table>


        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:left;font-size:8pt;font-weight:700;margin:0;padding:2pt 2pt 2pt 20pt;">Standard Equipment</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
            <tr style="background: #ccc;">
                <th style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Standard</h3></th>
                <th style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Serial Number</h3></th>

            </tr>

            <tr>
                <td style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;">Balance</p></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($digital_balance_device); ?></p></td>

            </tr>

            <tr>
                <td style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;">Digital Barometer</p></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($digital_barometer_device); ?></p></td>

            </tr>
            <tr>
                <td style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;">Digital Thermometer</p></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($digital_thermometer_device); ?></p></td>

            </tr>
            <tr>
                <td style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;">Thermocouple w/Probe</p></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><p style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:400;margin:0;"><?php echo e($digital_thermocouple_device); ?></p></td>

            </tr>
            <tr>
                <td style="border-bottom:solid 0px #000;padding:6pt;border-right:solid 0pt #000"><p style="text-transform:uppercase;text-align:left;font-size:7pt;font-weight:400;margin:0;"></p></td>
                <td style="border-bottom:solid 0px #000;padding:6pt;border-right:solid 0pt #000"><p style="text-transform:uppercase;text-align:left;font-size:7pt;font-weight:400;margin:0;"></p></td>
                <td style="border-bottom:solid 0px #000;padding:6pt;border-right:solid 0pt #000"><p style="text-transform:uppercase;text-align:left;font-size:7pt;font-weight:400;margin:0;"></p></td>
                <td style="border-bottom:solid 0px #000;padding:6pt;border-right:solid 0pt #000"><p style="text-transform:uppercase;text-align:left;font-size:7pt;font-weight:400;margin:0;"></p></td>


            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:left;font-size:8pt;font-weight:700;margin:0;padding:2pt 2pt 2pt 20pt;">Calibration Data</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0pt #000;">
            <tr style="background: #ccc;">
                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Volume</td>
                <td colspan="2" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Unc. =/-</td>
                <td colspan="2" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Accuracy %</td>
                <td colspan="2" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Precision %</td>

                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
            </tr>
            <tr>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:left;font-size:7pt;font-weight:700;margin:0;">Asset#, Location, Pipette Model</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">(ul)</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Mean</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">SD</h3></td>
                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(k=2)</td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Actual</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Spec</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Actual</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Spec</h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:700;margin:0;">Status</h3></td>
            </tr>
            <?php if($calibrated_datas): ?>  <?php $__currentLoopData = $calibrated_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <tr>
                <td width="20%" style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;">
                        <table cellspacing="0" cellpadding="0" width="100%" >
                            <tr>
                                <td style="text-align: left;padding: 1pt;"><?php echo e($row['asset_no']); ?></td>
                                <td style="text-align: left;padding: 1pt;"><?php echo e($row['location']); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;padding: 1pt;"><?php echo e($row['model']); ?></td>
                            </tr>
                        </table>
                    </h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['volume']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['mean']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['sd']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"><?php echo e($row['unc']); ?></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['actual_accuracy']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['specification_accuracy']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['actual_precision']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['specification_precision']); ?></h3></td>
                <td style="border-bottom:solid 0px #000;padding:2pt;border-right:solid 0pt #000"><h3 style="text-transform:capitalize;text-align:center;font-size:7pt;font-weight:400;margin:0;"><?php echo e($row['status']); ?></h3></td>
            </tr>
            <?php if($row['spares'] && $row['spares']!=''): ?>
            <tr style="">
                <td colspan="10" style="padding: 2pt;border-bottom: solid thin #ccc;"> <b>Replacements Parts</b> : <?php echo e($row['spares']); ?> </td>
            </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <?php endif; ?>

        </table>

    </div>

    <div style="border: solid 0px #000;margin-top:5pt;padding-bottom: 30px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td height="60px" style="vertical-align: top;font-size: 8pt;font-weight:700;padding:2pt 2pt 2pt 20pt; height: auto;border-bottom:solid 0px #000;">Comment : <span style="font-weight:400;"> <?php echo e($comments); ?> </span></td>
            </tr>
            <tr>
                <td style="font-size: 7pt;padding:2pt 2pt 2pt 20pt;">
                    1. PASS grade denotes that pipette meets OEM specifications. This pipette was calibrated gravimetrically using Standard Operating Procedure (NSOP), De-Ionized Water, Manufacturer/Generic Pipette tips and a Mettler Analytical Balance. All the standard equipment used to calibrate pipettes are certified at regular intervals traceable to National Institute of Standards and Technology (NIST). The test procedures comply to ISO/IEC 17025 guidelines.
                </td>
            </tr>
            <tr>
                <td style="font-size: 7pt;padding:2pt 2pt 2pt 20pt;">
                    2. As a standard practice, Multi Channel Pipettes are checked & Calibrated at one random channel only, all other channels are verified.
                </td>
            </tr>
            <tr>
                <td style="font-size: 7pt;padding:2pt 2pt 2pt 20pt;">
                    3. Calibration and Measurement Capablility (CMC) is the smallest uncertainity of measurement that a laboratory can achieve wi0px its scope of accrediation when performing more or less routine calibrations of nearly ideal measurement standards or nearly ideal measuring equipment. Calibration and Measurement Capabilities represent expanced uncertainities expressed at approximately the 95% level of confidence, usually using a coverage factor of k=2. The actual measurement uncertainity of a specific calibration performed by a laboratory may be greater than tha CMC due to the behaviour of the customer's device and to influences from the circustances of the specific calibration.
                </td>
            </tr>
            <tr>
                <td style="font-size: 7pt;padding:2pt 2pt 2pt 20pt;">
                    4. The results above relate only to the item calibrated. This report shall not be reproduced except in full, without the written approval of Novamed Inc.
                </td>
            </tr>
            <tr>
                <td style="font-size: 7pt;padding:2pt 2pt 2pt 20pt;">
                    5. All syringes are checked and verified at 3% of inaccuracy and imprecision specifications
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top: solid 0pt #000;">
            <tr>
                <td width="10%" style="padding:2pt 5pt;font-size:8pt;font-weight:700;text-align: right;">Technician</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10pt 3pt 7pt;border-bottom:solid 0px #000;"><img src="<?php echo e(asset('users/signature/thumb/'.$uploaded_signature)); ?>"></p></td>
                <td width="10%" style="padding:2pt 5pt;font-size:8pt;font-weight:700;text-align: right;">Date</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10pt 3pt 7pt;border-bottom:solid 0px #000;"><?php echo e(date('M/d/Y',strtotime(str_replace('-','/',$tech_date)))); ?></p></td>
            </tr>
            <?php if($admin_signature): ?>
                <tr>
                    <td width="10%" style="padding:2pt 5pt;font-size:8pt;font-weight:700;text-align: right;">QC</td>
                    <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10pt 3pt 7pt;border-bottom:solid 0px #000;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img src="<?php echo e(asset('users/signature/thumb/'.$admin_signature)); ?>"></p></td>
                    <td width="10%" style="padding:2pt 5pt;font-size:8pt;font-weight:700;text-align: right;">Date</td>
                    <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10pt 3pt 7pt;border-bottom:solid 0px #000;"><?php echo e(date('M/d/Y')); ?></p></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div style="border-top:solid 0pt #000;margin-top: 10pt; padding-top:10px;">
        <table cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td style="text-align:center;font-weight:700;font-size:8pt;">Novamed Inc., 8136 Lawndale Ave., Skokie, IL 60076 USA, Tel: 800-354-6676, Fax: 847-675-3322, email: support@novamed1.com</td>
            </tr>

        </table>
    </div>





</div>

</body>

</html>
