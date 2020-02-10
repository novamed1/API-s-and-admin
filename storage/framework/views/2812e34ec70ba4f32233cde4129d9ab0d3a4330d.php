<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Calibration Certificate</title>
    <!--<link href="css/style.css" rel="stylesheet" type="text/css">-->

    <style type="text/css">
        @page{
            margin: 10mm;
        }
        body {
            padding: 0mm;
            font-size: 10px;
            color: #000;
            font-family: 'Port Lligat Slab';
            box-sizing: border-box;
        }

        .wrapper {
            padding: 0 1mm 0mm;
            width: 100%;
            box-sizing: border-box;
            border: solid 0px #000;
        }
        /*#footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }*/
        /*#footer .page:after { content: counter(page, upper-roman); }*/
        footer { position: fixed; bottom: 0px; left: 0px; right: 0px; lightblue; height: 50px; }
        .page-break{

            overflow: hidden;

            position: relative;
            page-break-after: always;
            /*page-break-inside: avoid;*/
        }

        #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px;}
        #footer .page:after { content: counter(page, upper-roman); }
    </style>

</head>

<body>

<?php
$calibDate = date('mdy', strtotime(str_replace('/', '', $equipment_details->last_cal_date)));
$dueDate = date('mdy', strtotime(str_replace('/', '', $equipment_details->next_due_date)));
$assetNo = $equipment_details->asset_no;
$requestNo = $equipment_details->request_no;
//$certificateNo = $calibDate . '-' . $assetNo . '-' . $dueDate;
$certificateNo = $requestNo.'-'.$calibDate . '-' . $assetNo;

$now = new DateTime();
$today_date = $now->format('d-M-Y');
?>
<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Helvetica","Arial","sans-serif");
        
    
    
    $pdf->page_text(150,750,"Novamed Inc, 8136 N Lawndale Ave, Skokie,IL 60076 USA Tel: 847-675-3350",$font,10,array(0,0,0));
    $pdf->page_text(250,762,"Email: support@novamed1.com",$font,10,array(0,0,0));
    $pdf->page_text(3, 775, "CALCERT V 1.0", $font, 10, array(0, 0, 0));
    $pdf->page_text(270, 775, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));
    $pdf->page_text(430, 775, "Certificate# <?php echo e($certificateNo); ?>", $font, 10, array(0, 0, 0));

      }
</script>


<div class="wrapper">

    
    
    
    
    
    

    
    

    
    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000; padding: 0 0 5pt; ">
        <tr>
            <td width="25%" style="padding:2px">
                <p><img src="<?php echo e(asset('img/reportlogo.png')); ?>"></p>
                <p style="font-size: 14px;font-weight: normal;color: #333333;line-height: 1.4;">Novamed Inc.      <br>
                    8136 N Lawndale Ave., <br>
                    Skokie, IL 60076 USA <br>
                    Tel: +1 (800) 354-6676 <br>
                    Fax: +1 (847) 675-3322 <br>
                    www.novamed1.com <br>
                    TIN: 36-3750788</p>
            </td>

            <td width="60%" style="padding:2px;text-align: center;">
                <h2 style="font-size: 24px;font-weight: normal;color: #000;padding-right: 0px;margin-left:30px;margin-top: -75px;">Certificate of Calibration</h2>
            </td>
            <td width="40%" style="padding:2px;text-align: center;">
                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:500;margin:0;padding:0;text-align: right;margin-top: -77px;">
                    Certificate#: <?php echo e($certificateNo); ?><br/>
                    Date: <?php echo e($today_date); ?>


                </p>

            </td>

        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">

        <tr>

            <td width="40%" style="padding-right: 10pt;border:solid thin #ddd;border-radius: 4px;background: #fff;"
                valign="top">
                <div style="display: block;border:solid 0px #ddd;border-radius: 4px;padding: 5pt;">
                    <p style="text-transform:capitalize;text-align:left;font-size:14px;font-weight:600;margin:0;padding:0 0 6pt;">
                        Customer</p>
                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                        <?php echo e($equipment_details->customer_name); ?></p>
                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                        <?php echo e($equipment_details->address1); ?></p>
                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                        <?php echo e($equipment_details->city); ?>, <?php echo e($equipment_details->state); ?>, <?php echo e($equipment_details->zip_code); ?></p>
                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 8pt;">
                        Tel: <?php echo e($equipment_details->customer_telephone); ?></p>
                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                        Contact: <?php echo e($equipment_details->primary_contact); ?> </p>
                    
                    
                </div>
            </td>

            <td style="padding-left: 5pt;">

                <div style="display: block;border:solid thin #ddd;border-radius: 4px;padding: 5pt; margin-bottom: 5pt;background: #fff;">
                    <p style="text-transform:capitalize;text-align:left;font-size:14px;font-weight:600;margin:0;padding:0 0 6pt;">
                        Instrument Details</p>

                    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                        <tr>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Asset# </p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($equipment_details->asset_no); ?></p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Serial#</p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($equipment_details->serial_no); ?></p>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Instrument </p>
                                <p style="font-family: 'DejaVu Sans';text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($equipment_details->model_description); ?></p></td>

                        </tr>
                    </table>


                      <table>
                        <tr>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Calibration date: </p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e(date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->last_cal_date)))); ?></p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Next Due Date:</p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e(date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->next_due_date)))); ?></p>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Technician: </p></td>
                            <td width="20">
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($equipment_details->tfname.' '.$equipment_details->tlname); ?></p></td>

                        </tr>
                    </table>



                </div>

                <div style="display: block;border:solid thin #ddd;border-radius: 4px;padding: 5pt;background: #fff;">
                    <p style="text-transform:capitalize;text-align:left;font-size:14px;font-weight:600;margin:0;padding:0 0 6pt;">
                        Environmental Conditions</p>
                    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Relative Humidity (%): <?php echo e(number_format($calibrated_workorder->relevent_humidity,2)); ?> </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Z-Factor (cm3 /g): <?php echo e($calibrated_workorder->z_factor); ?></p></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Barometric Pressure (kPa): <?php echo e(number_format($calibrated_workorder->barometric_pressure,2)); ?></p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Liquid Density (gm/ml): <?php echo e($calibrated_workorder->liquid_dencity); ?></p></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Water Temperature ( &#186 C): <?php echo e(number_format($calibrated_workorder->water_temperature,2)); ?></p></td>

                        </tr>
                    </table>
                </div>

            </td>

        </tr>

    </table>

    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000; padding-top: 5pt">

        <tr>

            <td width="40%" style="padding-right: 10pt;border:solid thin #ddd;border-radius: 4px;" valign="top">
                <div style="display: block;border:solid 0px #ddd;border-radius: 4px;padding: 5pt;background: #fff;">
                    <p style="text-transform:capitalize;text-align:left;font-size:14px;font-weight:600;margin:0;padding:0 0 6pt;">
                        Standard Equipment</p>
                    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                        <tr>
                            <th style="font-size: 14px;">Standard</th>
                            <th style="font-size: 14px;">Serial Number</th>
                            <th style="font-size: 14px;">Calibration Date</th>
                            <th style="font-size: 14px;">Next Due Date</th>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Balance </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($calibrated_workorder->balance_device_serial_no); ?> </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($balance_last_date); ?></p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($balance_due_date); ?></p></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Digital Barometer </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($calibrated_workorder->barometer_device_serial_no); ?> </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($barometer_last_date); ?></p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($barometer_due_date); ?></p></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    Thermocouple </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($calibrated_workorder->thermocouple_device_serial_no); ?> </p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($thermocouple_last_date); ?></p></td>
                            <td>
                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                    <?php echo e($thermocouple_due_date); ?> </p></td>
                        </tr>
                    </table>
                </div>
            </td>

        </tr>

    </table>

    <?php
    $foundLogCount = count($found_log);
    $calibratedLogCount = count($calibrated_log);
    ?>


    <?php if($foundLogCount==1&&$calibratedLogCount==1): ?>


        <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">

            <tr>
                <?php
                //Columns must be a factor of 12 (1,2,3,4,6,12)
                $numOfCols = 3;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <?php if($found_log): ?>

                    <?php $p = 0 ?>
                    <?php $__currentLoopData = $found_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fkey=>$frow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <?php $p++ ?>

                        <td style="padding-right: 10px;padding-top:10px;" valign="top">

                            <h3 style="text-align: center;margin-left: 20px;font-size: 16px;">
                                <span>AS FOUND - <?php echo e($foundPassFail); ?> </span>
                            </h3>
                            <div style="padding: 20px;background: #fff;border-right:solid thin #ddd;border-radius: 0px;background: #fff;">

                                <p style="text-transform:capitalize;text-align:center;font-size:14px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Test Volume <?php echo e($frow['test_target']); ?>(µl)</p>
                                <p style="text-transform:capitalize;text-align:center;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Channel  <?php echo e($frow['channel']); ?></p>
                                
                                

                                <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                                    <tr>
                                        <th width="10%"><p
                                                    style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample </p></th>
                                        <th width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample Weight</p></th>
                                        <th width="45%"><p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample Volume</p></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                (mg)</p></td>
                                        <td width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                (ul)</p></td>
                                    </tr>
                                    <?php $i = 1;
                                    $j = 0;
                                    $len = count($frow['readings']);
                                    ?>
                                    <?php $__currentLoopData = $frow['readings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subreadings): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <tr>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($i); ?></p>
                                            </td>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadings->sample_weight); ?></p>
                                            </td>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadings->sample_volume); ?></p>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Mean</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['mean'],5,'.','')); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">SD</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['sd'],5,'.','')); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Accuracy Specification</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['target_accuracy'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Actual Observed</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['actual_accuracy'],2,'.','')); ?> %</p></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Precision Specification</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['target_precision'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Actual Observed</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($frow['actual_precision'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">Result</p>
                                        </td>

                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                                <?php echo e($frow['status']); ?></p></td>
                                    </tr>
                                </table>

                            </div>
                        </td>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>


                    <?php if($calibrated_log): ?>

                        <?php $q = 0 ?>
                        <?php $__currentLoopData = $calibrated_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey=>$crow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <?php $q++ ?>

                            <td style="padding-right: 10pt;padding-top:10px;" valign="top">
                                <h3 style="text-align: center;margin-left: 22px;font-size: 16px;">
                                    <span>AS CALIBRATED - <?php echo e($calibratedPassFail); ?> </span>
                                </h3>
                                <div style="padding: 22px;background: #fff;border-right:solid thin #ddd;border-radius: 0px;background: #fff;">

                                    <p style="text-transform:capitalize;text-align:center;font-size:14px;font-weight:600;margin:0;padding:0 0 3pt;">
                                        Test Volume <?php echo e($crow['test_target']); ?>(µl)</p>
                                    <p style="text-transform:capitalize;text-align:center;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                        Channel  <?php echo e($crow['channel']); ?></p>

                                    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                                        <tr>
                                            <th width="10%"><p
                                                        style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Sample </p></th>
                                            <th width="45%"><p
                                                        style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Sample Weight</p></th>
                                            <th width="45%"><p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Sample Volume</p></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td width="45%"><p
                                                        style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    (mg)</p></td>
                                            <td width="45%"><p
                                                        style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    (ul)</p></td>
                                        </tr>
                                        <?php $c = 1;
                                        $d = 0;
                                        $len = count($crow['readings']);
                                        ?>
                                        <?php $__currentLoopData = $crow['readings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subreadingscalibrated): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <tr>
                                                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                    <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($c); ?></p>
                                                </td>
                                                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                    <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadingscalibrated->sample_weight); ?></p>
                                                </td>
                                                <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                    <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadingscalibrated->sample_volume); ?></p>
                                                </td>
                                            </tr>
                                            <?php $c++; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Mean</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['mean'],5,'.','')); ?></p></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    SD</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['sd'],5,'.','')); ?></p></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Accuracy Specification</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['target_accuracy'],2,'.','')); ?> %</p></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Actual Observed</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['actual_accuracy'],2,'.','')); ?> %</p></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Precision Specification</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['target_precision'],2,'.','')); ?> %</p></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    Actual Observed</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                    <?php echo e(number_format($crow['actual_precision'],2,'.','')); ?> %</p></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                            </td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                                    Result</p></td>
                                            <td>
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                                    <?php echo e($crow['status']); ?></p></td>
                                        </tr>
                                    </table>

                                </div>
                            </td>
                            <?php
                            $rowCount++;
                            if ($rowCount % $numOfCols == 0) {
                                echo '</tr><tr style="padding-top: 10px">';
                            }
                            ?>

                            <div class="clearfix"></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    <?php endif; ?>

            </tr>

        </table>


    <?php else: ?>

    <?php if($found_log): ?>

    <h3 style="text-align: center;margin-left: 20px;font-size: 16px;">
        <span>AS FOUND - <?php echo e($foundPassFail); ?> </span>
    </h3>


    <table cellspacing="0" cellpadding="0" width="100%">

        <tr>
            <?php
            //Columns must be a factor of 12 (1,2,3,4,6,12)
            $numOfCols = 3;
            $rowCount = 0;
            $bootstrapColWidth = 12 / $numOfCols;
            ?>
            <?php if($found_log): ?>

                <?php $p = 0 ?>
                <?php $__currentLoopData = $found_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fkey=>$frow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <?php $p++ ?>
                    <td style="padding-right: 10px;padding-top:10px;" valign="top">
                        <div style="padding: 20px;background: #fff;border-right:solid thin #ddd;border-radius: 0px;background: #fff;">
                            <p style="text-transform:capitalize;text-align:center;font-size:14px;font-weight:600;margin:0;padding:0 0 3pt;">
                                Test Volume <?php echo e($frow['test_target']); ?>(µl)</p>
                            <p style="text-transform:capitalize;text-align:center;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                Channel <?php echo e($frow['channel']); ?></p>
                            
                            

                            <table cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <th width="10%"><p
                                                style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            Sample </p></th>
                                    <th width="45%"><p
                                                style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            Sample Weight</p></th>
                                    <th width="45%" ><p
                                                style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            Sample Volume</p></th>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>
                                    <td width="45%"><p
                                                style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            (mg)</p></td>
                                    <td width="45%"><p
                                                style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            (ul)</p></td>
                                </tr>
                                <?php $i = 1;
                                $j = 0;
                                $len = count($frow['readings']);
                                ?>
                                <?php $__currentLoopData = $frow['readings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subreadings): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <tr>
                                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($i); ?></p>
                                        </td>
                                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadings->sample_weight); ?></p>
                                        </td>
                                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadings->sample_volume); ?></p>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Mean</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['mean'],5,'.','')); ?></p></td>

                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">SD</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['sd'],5,'.','')); ?></p></td>

                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Accuracy Specification</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['target_accuracy'],2,'.','')); ?> %</p></td>

                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Actual Observed</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['actual_accuracy'],2,'.','')); ?> %</p></td>

                                </tr>

                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Precision Specification</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['target_precision'],2,'.','')); ?> %</p></td>

                                </tr>
                                <tr>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">Actual Observed</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                            <?php echo e(number_format($frow['actual_precision'],2,'.','')); ?> %</p></td>

                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">Result</p>
                                    </td>

                                    <td>
                                        <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                            <?php echo e($frow['status']); ?></p></td>

                                </tr>
                            </table>

                        </div>
                    </td>
                    <?php
                    $rowCount++;
                    if ($rowCount % $numOfCols == 0) {
                        echo '</tr><tr style="padding-top: 10px">';
                    }
                    ?>

                    <div class="clearfix"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <?php endif; ?>

        </tr>

    </table>
    <?php endif; ?>


    <?php if($calibrated_log): ?>


            <h3 style="text-align: center;margin-left: 22px;font-size: 16px;">
                <span>AS CALIBRATED - <?php echo e($calibratedPassFail); ?> </span>
            </h3>
        <table cellspacing="0" cellpadding="0" width="100%">

            <tr>
                <?php
                //Columns must be a factor of 12 (1,2,3,4,6,12)
                $numOfCols = 3;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <?php if($calibrated_log): ?>

                    <?php $q = 0 ?>
                    <?php $__currentLoopData = $calibrated_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey=>$crow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <?php $q++ ?>
                        <td style="padding-right: 10pt;padding-top:10px;" valign="top">
                            <div style="padding: 22px;background: #fff;border-right:solid thin #ddd;border-radius: 0px;background: #fff;">
                                <p style="text-transform:capitalize;text-align:center;font-size:14px;font-weight:600;margin:0;padding:0 0 3pt;">
                                    Test Volume <?php echo e($crow['test_target']); ?>(µl)</p>
                                <p style="text-transform:capitalize;text-align:center;font-size:12px;font-weight:600;margin:0;padding:0 0 6pt;">
                                   Channel <?php echo e($crow['channel']); ?></p>

                                <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
                                    <tr>
                                        <th width="10%"><p
                                                    style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample </p></th>
                                        <th width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample Weight</p></th>
                                        <th width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Sample Volume</p></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                (mg)</p></td>
                                        <td width="45%"><p
                                                    style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                (ul)</p></td>
                                    </tr>
                                    <?php $c = 1;
                                    $d = 0;
                                    $len = count($crow['readings']);
                                    ?>
                                    <?php $__currentLoopData = $crow['readings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subreadingscalibrated): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <tr>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($c); ?></p>
                                            </td>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadingscalibrated->sample_weight); ?></p>
                                            </td>
                                            <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                                                <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"><?php echo e($subreadingscalibrated->sample_volume); ?></p>
                                            </td>
                                        </tr>
                                        <?php $c++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Mean</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['mean'],5,'.','')); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                SD</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['sd'],5,'.','')); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Accuracy Specification</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['target_accuracy'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Actual Observed</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['actual_accuracy'],2,'.','')); ?> %</p></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Precision Specification</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['target_precision'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                Actual Observed</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;">
                                                <?php echo e(number_format($crow['actual_precision'],2,'.','')); ?> %</p></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:left;font-size:12px;font-weight:400;margin:0;padding:0 0 3pt;"></p>
                                        </td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                                Result</p></td>
                                        <td>
                                            <p style="text-transform:capitalize;text-align:right;font-size:12px;font-weight:600;margin:0;padding:0 0 3pt;">
                                                <?php echo e($crow['status']); ?></p></td>
                                    </tr>
                                </table>

                            </div>
                        </td>
                        <?php
                        $rowCount++;
                        if ($rowCount % $numOfCols == 0) {
                            echo '</tr><tr style="padding-top: 10px">';
                        }
                        ?>

                        <div class="clearfix"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>

            </tr>

        </table>
    <?php endif; ?>

    <?php endif; ?>

    <div class="clearfix"></div>
    
    
    
    
    <table cellspacing="0" cellpadding="0" width="100%" style="padding: 10pt 0;">

        <tr style="padding: 10pt 0;" valign="bottom">
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0;">
                Maintenance and Parts Replaced:
            </td>
            <?php if($performed): ?>
                <?php $story = $performed ?>
            <?php else: ?>
                <?php $story = '---'; ?>
            <?php endif; ?>
            <td width="50%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;border-bottom: solid thin #ddd;"><p style="width: 60%;border-bottom:solid 0px #000;"><?php echo e($story); ?></p></td>
        </tr>

        <tr style="padding: 10pt 0;" valign="bottom">
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0;">
                Technician Comments:
            </td>
            <td width="30%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;border-bottom: solid thin #ddd;"><p style="width: 60%;border-bottom:solid 0px #000;"><?php echo e($comments); ?></p></td>

        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" width="100%" style="padding: 10pt 0;">

        <tr style="padding: 10pt 0;" valign="bottom">
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0;">
                Technician:
            </td>
            <td width="30%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0;border-bottom: solid thin #ddd;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><img
                            src="<?php echo e(asset('users/signature/thumb/'.$tech_signature)); ?>"></p></td>
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0pt 10pt;">
                Date:
            </td>
            <td width="30%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0;border-bottom: solid thin #ddd;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><?php echo e(date('d-M-Y',strtotime(str_replace('-','/',$tech_date)))); ?></p></td>
        </tr>

        <tr style="padding: 10pt 0;" valign="bottom">
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0;">QC
                Review:
            </td>
            <?php if($admin_signature)
            { ?>
            <td width="30%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0;border-bottom: solid thin #ddd;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><img
                            src="<?php echo e(asset('users/signature/thumb/'.$admin_signature)); ?>"></p></td>
            <?php } ?>
            <td width="15%" style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0 0pt 10pt;">
                Date:
            </td>
            <td width="30%"
                style="text-align:left;font-size:13px;font-weight:400;margin:0;padding: 6pt 0;border-bottom: solid thin #ddd;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><?php echo e(date('d-M-Y',strtotime(str_replace('-','/',$admin_date)))); ?></p></td>
        </tr>

        
            
                
            
            
                
            
                
            
            
                
        

    </table>

    <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: solid thin #ddd;border-radius: 4px;border-top: solid thin #ddd;border-radius: 4px;">

        <tr>
            <td style="font-size: 10px;padding:2px;">
                1. PASS grade denotes that pipette meets OEM specifications. This pipette was calibrated gravimetrically using Standard Operating Procedure (NSOP), De-Ionized Water, Manufacturer/Generic Pipette tips and a Mettler Analytical Balance. All the standard equipment used to calibrate pipettes are certified at regular intervals traceable to National Institute of Standards and Technology (NIST). The test procedures comply to ISO/IEC 17025 guidelines.
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px;padding:2px;">
                2. As a standard practice, Multi Channel Pipettes are checked & Calibrated at one random channel only, all other channels are verified.
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px;padding:2px;">
                3. Calibration and Measurement Capability (CMC) is the smallest uncertainity of measurement that a laboratory can achieve within its scope of accrediation when performing more or less routine calibrations of nearly ideal measurement standards or nearly ideal measuring equipment. Calibration and Measurement Capabilities represent expanced uncertainities expressed at approximately the 95% level of confidence, usually using a coverage factor of k=2. The actual measurement uncertainity of a specific calibration performed by a laboratory may be greater than tha CMC due to the behaviour of the customer's device and to influences from the circustances of the specific calibration.
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px;padding:2px;">
                4. The results above relate only to the item calibrated. This report shall not be reproduced except in full, without the written approval of Novamed Inc.
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px;padding:2px;">
                5. All syringes are checked and verified at 3% of inaccuracy and imprecision specifications
            </td>
        </tr>
    </table>





</div>


</body>

</html>
