<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title></title>


    <style type="text/css">
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
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
<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Helvetica","Arial","sans-serif");
        {{--$pdf->page_text(480, 720, "Certificate# {{$certificateNo}}", $font, 7, array(0, 0, 0));--}}
    {{--$pdf->page_text(480, 720, "Certificate# {{$certificateNo}}", $font, 7, array(0, 0, 0));--}}
    {{--$pdf->page_text(480, 740, "Page {PAGE_NUM}/{PAGE_COUNT}", $font, 7, array(0, 0, 0));--}}
    $pdf->page_text(70,720,"Novamed Inc, 8136 N Lawndale Ave, Skokie,IL 60076 USA Tel: 847-675-3350, email: support@novamed1.com",$font,10,array(0,0,0));
    $pdf->page_text(270, 750, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));
      }
</script>
<div class="wrapper">

    <table>
        <tr style="background: #f5f5f5;">
            <td><img src="{{asset('img/logo.png')}}" width="100px"></td>
            <td style="font-size: 14px;color:#000;text-align:center;font-weight: 600">
                INVOICE
            </td>
            <td style="padding-right: 100px"></td>
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
                <p style="font-size: 11px;color:#333;margin: 0;padding: 0;">EMAIl:
                    support@novamed1.com</p>
            </td>
            <td style="text-align: left;vertical-align: top;">
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">Invoice date:</span> {{date('m-d-Y',strtotime(str_replace('/','-',$data['purchase']->payment_date)))}}
                </p>
                <p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span
                            style="color:#999;display: inline-block;width: 120px;">PO#:</span> {{$data['purchase']->order_number}}
                </p>
                {{--<p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span--}}
                            {{--style="color:#999;display: inline-block;width: 120px;">Payment Terms:</span></p>--}}
                {{--<p style="font-size: 11px;color:#000;margin: 0;padding: 0 0 2px;"><span--}}
                            {{--style="color:#999;display: inline-block;width: 120px;">Payment Due By:</span></p>--}}
            </td>
        </tr>
    </table>
    {{--<table style="background: #f5f5f5;">--}}
        {{--<tr>--}}
            {{--<td style="font-size: 14px;color:#000;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 600;">--}}
                {{--INVOICE--}}
            {{--</td>--}}
        {{--</tr>--}}
    {{--</table>--}}
    <table>
        <tr>
            <td width="70%">
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Bill To :</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['billing']->billing_contact}}
                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['billing']->address1}}
                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['billing']->city}}</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['billing']->state}}</p>
            </td>
            <td style="text-align: left;vertical-align: top;">
                <p style="font-size: 13px;color:#000;text-transform: uppercase;margin: 0;padding: 0;font-weight: 700;">
                    Ship To :</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['shipping']->customer_name}}
                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['shipping']->address1}}
                    ,</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['shipping']->city}}</p>
                <p style="font-size: 13px;color:#000;margin: 0;padding: 1px 0;font-weight: 500;">{{$data['shipping']->state}}</p>
            </td>
        </tr>
    </table>
    <table style="border-top: solid thin #ddd;border-bottom: solid thin #ddd;">
        <tr>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Customer Name</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">{{(isset($data['purchase']->unique_id)&&$data['purchase']->unique_id)?$data['purchase']->unique_id:''}}</h3>
            </td>
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Customer Name</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">{{$data['purchase']->customer_name}}</h3>
            </td>

            {{--<td style="border-right: solid thin #ddd;">--}}
                {{--<h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">--}}
                    {{--Work Order</h5>--}}
                {{--<h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">--}}
                    {{--#254563</h3>--}}
            {{--</td>--}}
            <td style="border-right: solid thin #ddd;">
                <h5 style="font-size: 14px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Purchase Order</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">{{$data['purchase']->order_number}}</h3>
            </td>
            <td >
                <h5 style="font-size: 12px;color:#666;text-align:center;text-transform: uppercase;text-align:center;margin: 0;padding: 5px 0;font-weight: 400;">
                    Total Instruments</h5>
                <h3 style="font-size: 13px;color:#000;text-align:center;text-transform: capitalize;text-align:center;margin: 0;padding: 0px 0;font-weight: 700;">{{(isset($orderItems) && $orderItems) ?  count($orderItems) : '' }}
                </h3>
            </td>
        </tr>

    </table>

    <div style="padding: 10px;">
        <table style="border: solid thin #ddd;border-bottom: 0px">
            <tr style="background: #cecece;">
                <th style="border-right:solid thin #ddd;" width="4%"></th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="25%">Model
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Type
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Qty
                </th>
                {{--<th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"--}}
                    {{--width="31%">Description--}}
                {{--</th>--}}
                {{--<th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"--}}
                    {{--width="5%">Price--}}
                {{--</th>--}}
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid thin #ddd;font-weight:500;"
                    width="20%">Price
                </th>
                <th style="font-size: 13px;color:#333;padding: 3px;border-right:solid 0px #ddd;font-weight:500;"
                    width="21%">Amount
                </th>
            </tr>

            @if(count($orderItems))
                <?php $i = 1; ?>
                @foreach($orderItems as $key=>$row)
                    <tr style="border-bottom: solid thin #ddd;">
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">{{$i}}</td>
                        <td style="font-family: 'DejaVu Sans';font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">{{$row->name}}</td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">{{$row->type}}</td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">{{$row->quantity}}</td>
                        {{--<td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">{{$row->quantity}}</td>--}}
                        {{--<td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">--}}
                            {{--1--}}
                        {{--</td>--}}
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">
                            $ {{number_format($row->price,2)}}</td>
                        <td style="font-size:12px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;">
                            $ {{number_format($row->total_price,2)}}</td>
                    </tr>
                    {{--<tr style="border-bottom: solid 0px #ddd;background: #eceaea;">--}}
                        {{--<td style="font-size:12px;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;"></td>--}}
                        {{--<td colspan="7"--}}
                            {{--style="font-size:12px;text-transform:uppercase;font-weight:700;color:#000;padding:3px;border-right:solid thin #ddd;vertical-align: top;">--}}
                            {{--Spare Parts / Accessories for Above Item--}}
                        {{--</td>--}}
                    {{--</tr>--}}

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
                @endforeach
            @endif


            <tr style="border-top: solid thin #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="4" rowspan="4"
                    style="font-size:12px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;">
                    {{--<p style="font-size: 11px;color:#666;">Comments</p>--}}
                    {{--<p style="font-size: 11px;color:#999;">{{$comments}}</p>--}}
                </td>
                <td colspan="1"
                    style="font-size:11px;color:#000;padding:10px 5px 5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Sub Total
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    $ {{number_format($totalCost,2)}}
                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="1"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Shipping &amp; Handling
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    {{--@if($data['shipping_price'])--}}
                        {{--$ {{number_format($data['shipping_price'],2)}}--}}
                    {{--@else--}}
                        $ 0.00
                    {{--@endif--}}

                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">
                <td colspan="1"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;">
                    Service Tax
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;">
                    {{--@if($data['sales_tax_price'])--}}
                        {{--$ {{number_format($data['sales_tax_price'],2)}}--}}
                    {{--@else--}}
                        $ 0.00
                    {{--@endif--}}

                </td>
            </tr>
            <tr style="border-top: solid 0px #ddd;border-left: solid 1px #fff;border-right: solid 0px #fff;">

                <td colspan="1"
                    style="font-size:11px;color:#000;padding:5px;border-right:solid 0px #ddd;vertical-align: top;text-align:left;font-weight: 700;">
                    Total
                </td>
                <td style="font-size:11px;color:#000;padding:3px;border-right:solid 0px #ddd;vertical-align: top;text-align:right;font-weight: 700;">
                    {{--@if($data['grand_total'])--}}
                        {{--$ {{number_format($data['grand_total'],2)}}--}}
                    {{--@else--}}
                        $ {{number_format($totalCost,2)}}
                    {{--@endif--}}
                </td>
            </tr>
        </table>
    </div>

    {{--<div class="footer">--}}
        {{--<table cellspacing="0" cellpadding="0" width="100%">--}}
            {{--<tr>--}}
                {{--<td colspan="3" style="text-align:center;font-weight:700;font-size:12px;">Novamed Inc., 8136 Lawndale--}}
                    {{--Ave., Skokie, IL 60076 USA, Tel: 800-354-6676, Fax: 847-675-3322, email: support@novamed1.com--}}
                {{--</td>--}}
            {{--</tr>--}}

        {{--</table>--}}
    {{--</div>--}}

</div>

</body>

</html>
