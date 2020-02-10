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

<?php
$calibDate = date('mdY',strtotime(str_replace('/','',$equipment_details->last_cal_date)));
$dueDate = date('mdY',strtotime(str_replace('/','',$equipment_details->next_due_date)));
$assetNo = $equipment_details->asset_no;
$certificateNo = $calibDate.'-'.$assetNo.'-'.$dueDate;

?>
<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Arial", "bold");
        $pdf->page_text(480, 720, "Certificate# {{$certificateNo}}", $font, 7, array(0, 0, 0));
        $pdf->page_text(480, 740, "Page {PAGE_NUM}/{PAGE_COUNT}", $font, 7, array(0, 0, 0));

      }
      </script>

<div class="wrapper">

    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 0px #000;">
        <tr>
            <td width="20%" style="padding:2pt 2pt 2pt 20pt"> <img src="{{asset('img/reportlogo.png')}}"> <img src="{{asset('img/reportimg.png')}}"> </td>
            <td width="60%" style="padding:2pt">
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">Novamed INC</h3>
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">Certificate of pipette calibration</h3>
            </td>

        </tr>
    </table>

    <div style="border: solid 0px #000;margin-top:5px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td colspan="2" width="50%" style="border-bottom:solid 0px #000;padding:2pt 2pt 2pt 20pt;border-right:solid 0px #000"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">Customer Details</h3></td>
                <td colspan="2" width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Product Details</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Customer</td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->customer_name}}</td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Asset#</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->asset_no}}</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Contact</td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->primary_contact}}</td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Serial Number</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->serial_no}}</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Location</td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->location}}</td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Pipette Model</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->model_name}}</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Address</td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->address1}}</td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Calibration Date</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->last_cal_date)))}}</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Next Due Date</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->next_due_date)))}}</td>
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Telephone</td>
                <td width="35%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->customer_telephone}}</td>
                <td width="15%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Technician</td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->tfname.' '.$equipment_details->tlname}}</td>
            </tr>
        </table>

        @if($as_found_workorder)
        <table cellspacing="0" cellpadding="0" width="100%" style="padding-top: 3pt;">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:center;font-size:12px;font-weight:700;margin:0;">Calibration Data - As Found</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
            <tr>
                <td colspan="2" width="33.3%" style="border-bottom:solid 1px #ccc;padding:2pt 2pt 2pt 20pt;border-right:solid 0px #000"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">Environmental Conditions</h3></td>
                <td width="21%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td width="12%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="21%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid 1px #ccc;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
            </tr>
            <tr>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Water Temprature <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5pt;"><sup>o</sup>C</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->water_temperature}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Barometric Pressure <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">kPa</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->barometric_pressure}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Z Factor <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">(cm3/g)</span></td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->z_factor}}</td>
            </tr>
            <tr>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Relative Humidity <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">%</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->relevent_humidity}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Air Density <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">g/cm3</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->air_dencity}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Liquid Density <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">(gm/ml)</span></td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->liquid_dencity}}</td>
            </tr>
        </table>
        @if($found_log)
                @foreach($found_log as $fkey=>$frow)

        <div style="display: inline-block;width: 60%; margin-right: -4px;">
            <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
                <tr>
                    <td colspan="5" style="padding: 2pt 2pt 2pt 20pt;border-bottom:solid 1px #ccc;border-right:solid 0px #000;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Test Volume {{$frow['test_target']}}(µl)</td>
                </tr>
                <tr>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Volume (µl)</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Ch</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Mean</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">SD</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(k=2)</td>
                </tr>
                <tr>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['volume']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['channel']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['mean']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['sd']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                </tr>
                <tr>

                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Actual</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Target</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Actual</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Target</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                </tr>
                <tr>

                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['actual_accuracy']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['target_accuracy']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['actual_precision']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['target_precision']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top:solid 1px #ccc;border-right:solid 0px #000;padding: 5pt 2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                        <span style="background: #ccc; padding: 2pt 5pt; color:#000; text-transform: uppercase;font-weight: 600;">{{$frow['status']}}</span>
                    </td>
                </tr>

            </table>
        </div>
                    @if($frow['readings'])
                        <?php $i = 1;
                        $j = 0;
                        $len = count($frow['readings']);
                        ?>

        <div style="display: inline-block;width: 40%; margin-right: -4px;vertical-align: top;">
            <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
                <tr>

                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Sample</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(mg)</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(µl)</td>
                </tr>
                @foreach($frow['readings'] as $subreadings)
                <tr>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$i}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadings->sample_weight}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadings->sample_volume}}</td>
                </tr>
                    <?php $i++; $j++; ?>
                @endforeach
           </table>
        </div>
                        @endif
                @endforeach
        @endif
        @endif

       @if($calibrated_workorder)
        <table cellspacing="0" cellpadding="0" width="100%" style="padding-top: 3pt;">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:center;font-size:12px;font-weight:700;margin:0;">Calibration Data - As Calibrated</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
            <tr>
                <td colspan="2" width="33.3%" style="border-bottom:solid 1px #ccc;padding:2pt 2pt 2pt 20pt;border-right:solid 0px #000"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">Environmental Conditions</h3></td>
                <td width="21%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td width="12%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="21%" style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid 1px #ccc;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
            </tr>
            <tr>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Water Temprature <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5pt;"><sup>o</sup>C</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->water_temperature}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Barometric Pressure <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">kPa</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->barometric_pressure}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Z Factor <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">(cm3/g)</span></td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->z_factor}}</td>
            </tr>
            <tr style="background: #ccc;">
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Relative Humidity <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">%</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->relevent_humidity}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Air Density <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">g/cm3</span></td>
                <td width="12%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->air_dencity}}</td>
                <td width="21%" style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Liquid Density <span style="font-weight:400;float:right;padding-right:5pt;">:</span> <span style="font-weight:400;float:right;padding-right:5px;">(gm/ml)</span></td>
                <td style="border-bottom:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$calibrated_workorder->liquid_dencity}}</td>
            </tr>
        </table>

    @if($calibrated_log)
        @foreach($calibrated_log as $ckey=>$crow)

        <div style="display: inline-block;width: 60%; margin-right: -4px;">
            <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
                <tr>
                    <td colspan="5" style="padding: 2pt 2pt 2pt 20pt;border-bottom:solid 1px #ccc;border-right:solid 0px #000;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">Test Volume {{$crow['test_target']}}(µl)</td>
                </tr>
                <tr>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2pt 2pt 2pt 20pt;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Volume (µl)</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Ch</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Mean</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">SD</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(k=2)</td>
                </tr>
                <tr>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['volume']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['channel']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['mean']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['sd']}}</td>
                    <td style="border-bottom:solid 1px #ccc;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                </tr>
                <tr>

                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Actual</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Target</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Actual</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Target</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                </tr>
                <tr>

                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['actual_accuracy']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['target_accuracy']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['actual_precision']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['target_precision']}}</td>
                    <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top:solid 1px #ccc;border-right:solid 0px #000;padding: 5pt 2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">
                        <span style="background: #ccc; padding: 2pt 5pt; color:#000; text-transform: uppercase;font-weight: 600;">{{$crow['status']}}</span>
                    </td>
                </tr>

            </table>
        </div>
            @if($crow['readings'])
                <?php $c = 1;
                $d = 0;
                $len = count($crow['readings']);
                ?>
            <div style="display: inline-block;width: 40%; margin-right: -4px;vertical-align: top;">
                <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0px #000;">
                    <tr>

                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">Sample</td>
                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(mg)</td>
                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">(uL)</td>
                    </tr>
                    @foreach($crow['readings'] as $subreadingscalibrated)
                    <tr>
                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$c}}</td>
                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadingscalibrated->sample_weight}}</td>
                        <td style="border-bottom:solid 0px #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadingscalibrated->sample_volume}}</td>
                    </tr>
                    @endforeach

                </table>
            </div>
            @endif
                    @endforeach
                @endif
        @endif




    </div>


    <div style="border: solid 0px #000;margin-top:5px;padding-bottom: 30px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="">
            @if($parts_replaced)
            <tr>
                <td height="0px" style="vertical-align: top;font-size: 12px;font-weight:700;padding:2pt 2pt 2pt 20pt;border-bottom:solid 1px #ccc;"> Replacecment Parts</td>
            </tr>
            <tr>
                <td height="60px" style="vertical-align: top;font-size: 12px;font-weight:700;padding:2px;border-bottom:solid 0px #000;">
                    <ul>
                        @foreach($parts_replaced as $partsrow)
                        <li style="float: left; margin-right: 20pt;font-weight: 300;"> {{$partsrow}} </li>
                       @endforeach
                    </ul>
                </td>
            </tr>
            @endif
            <tr>
                <td style="vertical-align: top;font-size: 12px;font-weight:700;padding:2pt 2pt 2pt 20pt;border-bottom:solid 1px #ccc;">Comment :</td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2pt 2pt 2pt 20pt;">
                    {{$comments}}
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top: solid 0px #000;">
            <tr>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Technician</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><img
                                src="{{asset('users/signature/thumb/'.$tech_signature)}}"></p></td>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Date</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;">{{date('M/d/Y',strtotime(str_replace('-','/',$tech_date)))}}</p></td>
            </tr>
            <?php if($admin_signature)
            { ?>
            <tr>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">QC Designee</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;"><img
                                src="{{asset('users/signature/thumb/'.$admin_signature)}}"></p></td>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Review Date</td>
                <td width="40%" style="vertical-align:bottom;"><p style="width: 60%;padding:10px 3px 7px;border-bottom:solid 0px #000;">{{date('M/d/Y',strtotime(str_replace('-','/',$admin_date)))}}</p></td>
            </tr>
            <?php  } ?>
        </table>
    </div>

    <div style="border-top:solid 1px #ccc;margin-top: 10px; padding-top:10px;">
        <table cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td colspan="3" style="text-align:center;font-weight:700;font-size:12px;">Novamed Inc., 8136 Lawndale Ave., Skokie, IL 60076 USA, Tel: 800-354-6676, Fax: 847-675-3322, email: support@novamed1.com</td>
            </tr>

        </table>
    </div>


</div>

</body>

</html>
