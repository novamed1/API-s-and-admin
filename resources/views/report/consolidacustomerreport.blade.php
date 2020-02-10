<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Sample Pipette Calibration Certificate</title>


    <style type="text/css">
        @page{
            margin: 10mm;
        }
        body { padding: 0mm;  font-size: 10px; color: #000; font-family:'Port Lligat Slab';  box-sizing: border-box; }
        .wrapper { padding: 0 1mm 0mm; width: 100%; box-sizing: border-box; border: solid 0px #000; }

        .page-break{
            page-break-before: always;

            page-break-after: always;

        }

    </style>

</head>

<body>

<div class="wrapper">

    <?php
    //    $calibDate = date('mdY', strtotime(str_replace('/', '', $equipment_details->last_cal_date)));
    //    $dueDate = date('mdY', strtotime(str_replace('/', '', $equipment_details->next_due_date)));
    //    $assetNo = $equipment_details->asset_no;
    //$certificateNo = $last_cal_date . '-' . $asset_no . '-' . $next_due_date;

    $now = new DateTime();
    $today_date = $now->format('d-M-Y');
    $certDate = date('mdY');
    $certificateNo = $customer->request_no . '-' . $certDate;
    ?>
    <script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Helvetica","Arial","sans-serif");
        {{--$pdf->page_text(480, 720, "Certificate# {{$certificateNo}}", $font, 7, array(0, 0, 0));--}}
        {{--$pdf->page_text(480, 720, "Certificate# {{$certificateNo}}", $font, 7, array(0, 0, 0));--}}
        {{--$pdf->page_text(480, 740, "Page {PAGE_NUM}/{PAGE_COUNT}", $font, 7, array(0, 0, 0));--}}

        $pdf->page_text(150,750,"Novamed Inc, 8136 N Lawndale Ave, Skokie,IL 60076 USA Tel: 847-675-3350",$font,10,array(0,0,0));
        $pdf->page_text(250,762,"Email: support@novamed1.com",$font,10,array(0,0,0));
        $pdf->page_text(3, 775, "CERTALL V 1.0", $font, 10, array(0, 0, 0));
        $pdf->page_text(270, 775, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));
        $pdf->page_text(430, 775, "Certificate# {{$certificateNo}}", $font, 10, array(0, 0, 0));

      }


    </script>

        <table cellspacing="0" cellpadding="0" width="100%" style="border: solid thin #000;">
            <tr>
                <td width="15%" style="">
                    <p><img src="{{asset('img/reportlogo.png')}}"></p>

                </td>
                <td width="85%" style="">
                    <div style="">
                        <p style="text-transform:uppercase;text-align:center;font-size:12px;font-weight:500;margin:0;padding:0;">Novamed INC, 8136 N Lawndale Ave.,Skokie, IL 60076 USA,Tel: +1 (800) 354-6676,Fax: +1 (847) 675-3322</p>

                        <h3 style="text-transform:uppercase;text-align:center;font-size:14px;font-weight:700;margin:0;padding:0;margin-top: 10px;">Certificate of calibration</h3>
                    </div>

                </td>

            </tr>
        </table>

    <div style="border: solid thin #000;margin-top:5px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td colspan="2" style="border-bottom:solid thin #000;padding:2px;border-right:solid thin #000"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">Customer Details</h3></td>
                <td colspan="2" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:700;margin:0;"><h3 style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">Environmental Conditions</h3></td>
                {{--<td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Calibration Date</td>--}}
                {{--<td style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$last_cal_date)))}}</td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Customer</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$customer->customer_name}}</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Water Temperature ( &#186 C)</td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->water_temperature_calibrated, 2, '.', '')}}</td>
                {{--<td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Next Due Date</td>--}}
                {{--<td width="15%" style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y',strtotime(str_replace('/','-',$next_due_date)))}}</td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Contact</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$customer->primary_contact}}</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Barometric Pressure (kPa)</td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->barometric_pressure_calibrated,2,'.','')}}</td>
                {{--<td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Report Date</td>--}}
                {{--<td width="15%" style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{date('d-M-Y')}}</td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Address</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$customer->address1}}</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Relative Humidity (%)</td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->relevent_humidity_calibrated,2,'.','')}}</td>
                {{--<td colspan="2" width="15%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Technician : {{$technician->first_name}} {{$technician->last_name}}</td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">City,State,Zip</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$customer->city.', '.$customer->state.', '.$customer->zip_code}}</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Z-Factor (cm3 /g)</td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->z_factor_calibrated,5,'.','')}}</td>
                {{--<td colspan="2" width="15%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Telephone</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{$customer->customer_telephone}}</td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Air Density <span>(G/cm<sup>3</sup>)</span></td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->air_dencity_calibrated,5,'.','')}}</td>
                {{--<td colspan="2" width="15%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>--}}
            </tr>
            <tr>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td width="20%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Liquid Density (gm/ml)</td>
                <td width="15%" style="border-bottom:solid thin #000;border-right:solid thin #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">{{number_format($workorder->liquid_dencity_calibrated,5,'.','')}}</td>
                {{--<td colspan="2" width="15%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>--}}
            </tr>
        </table>


        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 0 #000;">


            {{--<td width="40%" style="padding-right: 10pt;border:solid thin #ddd;border-radius: 4px;" valign="top">--}}
            {{--<div style="display: block;border:solid 0px #ddd;border-radius: 4px;padding: 5pt;background: #fff;">--}}
            {{--<p style="text-transform:capitalize;text-align:left;font-size:14px;font-weight:600;margin:0;padding:0 0 6pt;">--}}
            {{--Standard Equipment</p>--}}
            {{--<table cellspacing="0" cellpadding="0" width="100%">--}}
            <tr>
                <td colspan="4" style="border-bottom:solid thin #000;padding:2px;border-right:solid 0px #000"><h3
                            style="text-transform:uppercase;text-align:left;font-size:12px;font-weight:700;margin:0;">
                        Standard Equipment</h3></td>
            </tr>
            <tr>
                <th style="border-bottom:solid thin #000;">Standard</th>
                <th style="border-bottom:solid thin #000;">Serial Number</th>
                <th style="border-bottom:solid thin #000;">Calibration Date</th>
                <th style="border-bottom:solid thin #000;">Next Due Date</th>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    Balance
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    {{$digital_balance_device}} </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$balance_last_cal_date)))}}</td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$balance_next_due_date)))}}</td>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    Digital Barometer
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    {{$digital_barometer_device}} </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$barometer_last_cal_date)))}}</td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$barometer_next_due_date)))}}</td>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    Digital Thermometer
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    {{$digital_thermometer_device}} </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$thermometer_last_cal_date)))}}</td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$thermometer_next_due_date)))}}</td>
            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    Thermocouple
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">
                    {{$digital_thermocouple_device}}
                </td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$thermocouple_last_cal_date)))}}</td>
                <td style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">

                    {{date('d-M-Y',strtotime(str_replace('/','-',$thermocouple_next_due_date)))}}</td>
            </tr>
            {{--</table>--}}
            {{--</div>--}}
            {{--</td>--}}


        </table>


        <table cellspacing="0" cellpadding="0" width="100%" style="">
            <tr>
                <td><h3 style="text-transform:uppercase;text-align:center;font-size:12px;font-weight:700;margin:0;">Calibration Data</h3></td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" width="100%" style="border-top:solid 1px #000;">
            <tr>
                <td width="30%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;"></td>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%">

                        <tr>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Volume</td>
                            <td width="8%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                            {{--<td width="8%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">(k=2)</td>--}}
                            <td width="21%" style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Accuracy (%)</td>
                            <td width="1%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                            <td width="21%" style="border-bottom:solid thin #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Precision (%)</td>
                            <td width="1%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;"></td>
                        </tr>
                    </table>
                </td>

            </tr>
            <tr>
                <td style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:left;font-size:10px;font-weight:400;margin:0;">Asset#,Serial#,Instrument,Location</td>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">(ul)</td>
                            <td width="8%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">ch</td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Mean</td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">SD</td>
                            {{--<td width="8%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">(k=2)</td>--}}
                            <td width="12%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Accuracy Specification</td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Actual Observed</td>
                            <td width="12%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Precision Specification</td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Actual Observed</td>
                            <td width="10%" style="border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">Status</td>
                        </tr>
                    </table>
                </td>

            </tr>
            @if(count($calibrated_datas))
                <?php $p = 0 ?>
                @foreach($calibrated_datas as $key=>$row)

                    <tr>
                        <td style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-align:left;font-size:10px;font-weight:400;margin:0;">
                            <p style="font-family: 'DejaVu Sans';font-size:11px;">{{$row['asset_no']}},{{$row['serial_no']}},{{$row['model']}},{{$row['location']}}</p>

                        </td>
                        <td valign="top">

                            <table cellspacing="0" cellpadding="0" width="100%">
                                @if(count($row['calibrated_log']))
                                    @foreach($row['calibrated_log'] as $ckey=>$crow)
                                        <tr>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:12px 2px 6px 2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['target_value'],2,'.','')}}</td>
                                            <td width="8%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['channel'],2,'.','')}}</td>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['mean'],2,'.','')}}</td>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['sd']}}</td>
                                            {{--<td width="8%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['unc']}}</td>--}}
                                            <td width="12%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['specification_accuracy'],2,'.','')}} %</td>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['actual_accuracy'],2,'.','')}} %</td>
                                            <td width="12%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['specification_precision'],2,'.','')}} %</td>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 1px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{number_format($crow['actual_precision'],2,'.','')}} %</td>
                                            <td width="10%" style="border-top:solid thin #000;border-bottom:solid thin #000;border-right:solid 0px #000;padding:2px;text-transform:capitalize;text-align:center;font-size:10px;font-weight:400;margin:0;">{{$crow['status']}}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                    <tr>
                                        <td colspan="9">
                                            @if($row['spares'])
                                                <p> <span style="font-size: 12px;font-weight: 700">Maintenance And Spares </span>:  {{$row['spares']}}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="9" style="border-bottom:solid thin #000;">
                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                    <td><span style="font-size: 10px;font-weight: 700">Calibration Date</span><p>{{$row['last_cal_date']}}</p></td>
                                                    <td><span style="font-size: 10px;font-weight: 700">Report Date</span><p>{{$row['reported_date']}}</p></td>
                                                    <td><span style="font-size: 10px;font-weight: 700">Next Due Date</span><p>{{$row['next_due_date']}}</p></td>
                                                    <td><span style="font-size: 10px;font-weight: 700">Technician</span><p>{{$row['technician_name']}}</p></td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>

                            </table>

                        </td>


                    </tr>


                    <?php $p++ ?>
                @endforeach
            @endif

        </table>



    </div>






    <div style="border: solid thin #000;margin-top:5px;padding-bottom: 30px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: solid thin #000;">
            <tr>
                <td height="60px" style="vertical-align: top;font-size: 12px;font-weight:700;padding:2px;border-bottom:solid thin #000;">Comment : <span style="font-weight:400;"> {{$comments}} </span></td>
            </tr>
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
        <div style="page-break-inside: avoid;">
            <table cellspacing="0" cellpadding="0" width="100%" style="border-top: solid 2px #000;">
                <tr>
                    <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Technician
                    </td>
                    <td width="40%" style="vertical-align:bottom;"><p
                                style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img
                                    src="{{asset('users/signature/thumb/'.$uploaded_signature)}}"></p></td>
                    <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Date</td>
                    <td width="40%" style="vertical-align:bottom;"><p
                                style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;">{{date('d-M-Y')}}</p>
                    </td>
                </tr>

                    <tr>
                        <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">QC Designee
                        </td>
                        <td width="40%" style="vertical-align:bottom;"><p
                                    style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img src="{{asset('users/signature/thumb/'.$admin_signature)}}"></p></td>
                        <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Review
                            Date
                        </td>
                        <td width="40%" style="vertical-align:bottom;"><p
                                    style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;">{{date('M/d/Y',strtotime(str_replace('-','/',$admin_date)))}}</p>
                        </td>
                    </tr>
                <tr>
                    <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Customer
                    </td>
                    <td width="40%" style="vertical-align:bottom;"><p
                                style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;"><img src="{{asset('users/signature/thumb/'.$customer_signature)}}"></p></td>
                    <td width="10%" style="padding:2px 5px;font-size:12px;font-weight:700;text-align: right;">Review Date
                    </td>
                    <td width="40%" style="vertical-align:bottom;"><p
                                style="width: 60%;padding:10px 3px 7px;border-bottom:solid thin #000;">{{date('M/d/Y')}}</p></td>
                </tr>
            </table>
        </div>
    </div>


</div>

</body>

</html>
