<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Sample Pipette Calibration Certificate</title>
    <style type="text/css">
        body {
            padding: 0mm;
            font-size: 10px;
            color: #000;
            font-family: Open Sans, Source Sans Pro, Helvetica, Arial, sans-serif;
            box-sizing: border-box;
        }

        .wrapper {
            padding: 0 1mm 0mm;
            width: 100%;
            box-sizing: border-box;
            border: solid 0px #000;
        }

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

    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid thin #000;page-break-inside: avoid;">
        <tr>
            <td width="20%" style="padding:2px"><img src="{{asset('img/reportlogo.png')}}"> <img
                        src="{{asset('img/reportimg.png')}}"></td>
            <td width="60%" style="padding:2px">
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">
                    Novamed INC</h3>
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">
                    Certificate of pipette calibration</h3>
            </td>

        </tr>
    </table>

    <div style="border: solid thin #000;margin-top:5px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td colspan="2" width="50%"
                    style="border-bottom:solid thin #000;padding:2px;border-right:solid thin #000"><h3
                            style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Customer Details</h3></td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Asset#
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->asset_no}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Customer
                </td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->customer_name}}</td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Serial Number
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->serial_no}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Contact
                </td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->primary_contact}}</td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Pipette Model
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->model_name}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Location
                </td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->location}}</td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Calibration Date
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->last_cal_date)))}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Address
                </td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->address1}}</td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Next Due Date
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$equipment_details->next_due_date)))}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Technician
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->tfname.' '.$equipment_details->tlname}}</td>
            </tr>
            <tr>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Telephone
                </td>
                <td width="35%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$equipment_details->customer_telephone}}</td>
                <td width="15%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                    Report Print Date
                </td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y')}}</td>
            </tr>
        </table>
    </div>

    <div style="border: solid thin #000;margin-top:15px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid thin #000;page-break-inside: avoid;">
            <tr>
                <td colspan="2" width="33.3%"
                    style="border-bottom:solid thin #000;padding:2px;border-right:solid thin #000"><h3
                            style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Environmental Conditions</h3></td>
                <td width="21%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td width="12%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="21%"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
            </tr>
            @if($as_found_workorder)
                <tr>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Water Temprature <span
                                style="font-weight:400;float:right;padding-right:5px;"><sup>o</sup>C</span></td>
                    <td width="12%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->water_temperature}}</td>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Barometric Pressure <span style="font-weight:400;float:right;padding-right:5px;">kPa</span></td>
                    <td width="12%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->barometric_pressure}}</td>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Z Factor <span style="font-weight:400;float:right;padding-right:5px;">(cm3/g)</span></td>
                    <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->z_factor}}</td>
                </tr>
                <tr>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Relative Humidity <span style="font-weight:400;float:right;padding-right:5px;">%</span></td>
                    <td width="12%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->relevent_humidity}}</td>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Air Density <span style="font-weight:400;float:right;padding-right:5px;">g/cm3</span></td>
                    <td width="12%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->air_dencity}}</td>
                    <td width="21%"
                        style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;">
                        Liquid Density <span style="font-weight:400;float:right;padding-right:5px;">(gm/ml)</span></td>
                    <td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$as_found_workorder->liquid_dencity}}</td>
                </tr>
            @endif
        </table>
    </div>

    <div style="border: solid thin #000;margin-top:15px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td style="padding: 2px;"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Standard Equipments</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 1px #000;page-break-inside: avoid;">

            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Balance
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Thermocouple
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Digital Thermometer
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Digital Barometer
                </td>
            </tr>
                    <tr>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{isset($calibrated_workorder->balance_device_serial_no)?$calibrated_workorder->balance_device_serial_no:''}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{isset($calibrated_workorder->thermocouple_device_serial_no)?$calibrated_workorder->thermocouple_device_serial_no:''}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{isset($calibrated_workorder->thermometer_device_serial_no)?$calibrated_workorder->thermometer_device_serial_no:''}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{isset($calibrated_workorder->barometer_device_serial_no)?$calibrated_workorder->barometer_device_serial_no:''}}</td>
                        </tr>

        </table>
    </div>

    <div style="border: solid thin #000;margin-top:15px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Maintenance</h3></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 1px #000;">

            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Services Performed
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Parts Replaced
                </td>

            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$performed}}</td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$parts_replaced}}</td>
             </tr>

        </table>
    </div>


    <div style="border: solid thin #000;margin-top:15px;">
    <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
        <tr>
            <td><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                    Calibration Data - As Found</h3></td>
        </tr>
    </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 1px #000;page-break-inside: avoid;">
            <tr>
                <td colspan="4"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Volume (ul)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Unc. =/-
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Accuracy %
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Precision %
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample Weight
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample Volume
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Volume (ul)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Ch
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Mean
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    SD
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (k=2)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Actual
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Target
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Actual
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Target
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Status
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (mg)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (uL)
                </td>
            </tr>
            @if($found_log)
                @foreach($found_log as $fkey=>$frow)
                    <tr>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['volume']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['channel']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['mean']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['sd']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['actual_accuracy']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['target_accuracy']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['actual_precision']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['target_precision']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$frow['status']}}</td>
                        <td colspan="3"
                            style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                    </tr>

                    @if($frow['readings'])
                        <?php $i = 1;
                              $j = 0;
                              $len = count($frow['readings']);
                        ?>
                        @foreach($frow['readings'] as $subreadings)


                            <tr>
                                <?php if ($j == $len - 1) { ?>
                                <td colspan="10"
                                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                                <?php } else { ?>
                                    <td colspan="10"
                                        style="border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                                    <?php } ?>
                                    <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$i}}</td>
                                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadings->sample_weight}}</td>
                                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadings->sample_volume}}</td>
                            </tr>
                            <?php $i++; $j++; ?>

                        @endforeach
                    @endif
                @endforeach
            @endif
        </table>
    </div>
    <div style="border: solid thin #000;margin-top:15px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Calibration Data - As Calibrated</h3></td>
            </tr>
        </table>
       
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 1px #000;page-break-inside: avoid;">
            <tr>
                <td colspan="4"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Volume (ul)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Unc. =/-
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Accuracy %
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Precision %
                </td>
                <td colspan="2"
                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;"></td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample Weight
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample Volume
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Volume (ul)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Ch
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Mean
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    SD
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (k=2)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Actual
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Target
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Actual
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Target
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Status
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    Sample
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (mg)
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:700;margin:0;">
                    (uL)
                </td>
            </tr>


            @if($calibrated_log)
                @foreach($calibrated_log as $ckey=>$crow)
                    <tr>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['volume']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['channel']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['mean']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['sd']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['actual_accuracy']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['target_accuracy']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['actual_precision']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['target_precision']}}</td>
                        <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['status']}}</td>
                        <td colspan="3"
                            style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                    </tr>

                    @if($crow['readings'])
                        <?php $c = 1;
                              $d = 0;
                              $len = count($crow['readings']);
                        ?>
                        @foreach($crow['readings'] as $subreadingscalibrated)
                            <tr>

                                <?php if ($d == $len - 1) { ?>
                                <td colspan="10"
                                    style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                                <?php } else { ?>
                                <td colspan="10"
                                    style="border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                                <?php } ?>
                                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$c}}</td>
                                <td style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadingscalibrated->sample_weight}}</td>
                                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$subreadingscalibrated->sample_volume}}</td>
                            </tr>
                            <?php $c++; $d++; ?>

                        @endforeach
                    @endif
                @endforeach
            @endif
        </table>
    </div>


    <div style="border-top:solid 2px #000;margin-top: 10px; padding-top:10px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td colspan="3" style="text-align:center;font-weight:700;font-size:12px;">Novamed Inc., 8136 Lawndale
                    Ave., Skokie, IL 60076 USA, Tel: 800-354-6676, Fax: 847-675-3322, email: support@novamed1.com
                </td>
            </tr>

        </table>
    </div>


    <table cellspacing="0" cellpadding="0" width="100%" style="border: solid thin #000; page-break-inside: avoid;">
        <tr>
            <td width="20%" style="padding:2px"><img src="{{asset('img/reportlogo.png')}}"> <img
                        src="{{asset('img/reportimg.png')}}"></td>
            <td width="60%" style="padding:2px">
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">
                    Novamed INC</h3>
                <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;">
                    Certificate of pipette calibration</h3>
            </td>
            <td width="20%" style="padding:2px;vertical-align:bottom;">

        </tr>
    </table>

    <div style="border: solid thin #000;margin-top:5px;padding-bottom: 30px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="page-break-inside: avoid;">
            <tr>
                <td height="60px"
                    style="vertical-align: top;font-size: 12px;font-weight:700;padding:2px;border-bottom:solid thin #000;">
                    Comment : <span style="font-weight:400;"> {{$comments}} </span></td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2px;">
                    1. PASS grade denotes that pipette meets OEM specifications. This pipette was calibrated
                    gravimetrically using Standard Operating Procedure (NSOP), De-Ionized Water, Manufacturer/Generic
                    Pipette tips and a Mettler Analytical Balance. All the standard equipment used to calibrate pipettes
                    are certified at regular intervals traceable to National Institute of Standards and Technology
                    (NIST). The test procedures comply to ISO/IEC 17025 guidelines.
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2px;">
                    2. As a standard practice, Multi Channel Pipettes are checked & Calibrated at one random channel
                    only, all other channels are verified.
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2px;">
                    3. Calibration and Measurement Capablility (CMC) is the smallest uncertainity of measurement that a
                    laboratory can achieve within its scope of accrediation when performing more or less routine
                    calibrations of nearly ideal measurement standards or nearly ideal measuring equipment. Calibration
                    and Measurement Capabilities represent expanced uncertainities expressed at approximately the 95%
                    level of confidence, usually using a coverage factor of k=2. The actual measurement uncertainity of
                    a specific calibration performed by a laboratory may be greater than tha CMC due to the behaviour of
                    the customer's device and to influences from the circustances of the specific calibration.
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2px;">
                    4. The results above relate only to the item calibrated. This report shall not be reproduced except
                    in full, without the written approval of Novamed Inc.
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;padding:2px;">
                    5. All syringes are checked and verified at 3% of inaccuracy and imprecision specifications
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-top: solid 2px #000;page-break-inside: avoid;">

            <tr>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Technician
                </td>
                <td width="40%" style="vertical-align:bottom;"><p
                            style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img
                                src="{{asset('users/signature/thumb/'.$tech_signature)}}"></p></td>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Date</td>
                <td width="40%" style="vertical-align:bottom;"><p
                            style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;">{{date('M/d/Y',strtotime(str_replace('-','/',$tech_date)))}}</p>
                </td>
            </tr>
            <tr>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">QC Designee
                </td>
                <?php if($admin_signature)
                { ?>
                <td width="40%" style="vertical-align:bottom;"><p
                            style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img
                                src="{{asset('users/signature/thumb/'.$admin_signature)}}"></p></td>
                <?php  } else {?>
                <td width="40%" style="vertical-align:bottom;"><p
                            style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"></p></td>
                <?php } ?>
                <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Review Date
                </td>
                <td width="40%" style="vertical-align:bottom;"><p
                            style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;">{{date('M/d/Y',strtotime(str_replace('-','/',$admin_date)))}}</p>
                </td>
            </tr>


        </table>
    </div>


</div>

</body>

</html>
