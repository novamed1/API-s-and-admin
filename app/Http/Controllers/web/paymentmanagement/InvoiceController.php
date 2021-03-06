<?php
namespace App\Http\Controllers\web\paymentmanagement;


use App\Http\Controllers\Controller;
use App\Models\Devicemodel;
use App\Models\Sentinel\User;
use App\Models\Payment;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use DateInterval;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use Fpdf;
use PDF;
use Mail;
//use App\Classes\fpdf17\fpdf;

//class PDF extends Fpdf {
//
//// Page header
//    var $left = 10;
//    var $right = 10;
//    var $top = 10;
//    var $bottom = 10;
//
//
//
//// Page footer
//
//
//    // Create Table
//    function WriteTable($tcolums) {
//        // go through all colums
//        for ($i = 0; $i < sizeof($tcolums); $i++) {
//            $current_col = $tcolums[$i];
//            $height = 0;
//
//            // get max height of current col
//            $nb = 0;
//            for ($b = 0; $b < sizeof($current_col); $b++) {
//                // set style
//               Fpdf::SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
//                $color = explode(",", $current_col[$b]['fillcolor']);
//                Fpdf::SetFillColor($color[0], $color[1], $color[2]);
//                $color = explode(",", $current_col[$b]['textcolor']);
//                Fpdf::SetTextColor($color[0], $color[1], $color[2]);
//                $color = explode(",", $current_col[$b]['drawcolor']);
//                Fpdf::SetDrawColor($color[0], $color[1], $color[2]);
//                Fpdf::SetLineWidth($current_col[$b]['linewidth']);
//
//                $nb = max($nb, Fpdf::NbLines($current_col[$b]['width'], $current_col[$b]['text']));
//                $height = $current_col[$b]['height'];
//            }
//            $h = $height * $nb;
//
//            // Issue a page break first if needed
//            Fpdf::CheckPageBreak($h);
//
//            // Draw the cells of the row
//            for ($b = 0; $b < sizeof($current_col); $b++) {
//                $w = $current_col[$b]['width'];
//                $a = $current_col[$b]['align'];
//
//                // Save the current position
//                $x = Fpdf::GetX();
//                $y = Fpdf::GetY();
//
//                // set style
//                Fpdf::SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
//                $color = explode(",", $current_col[$b]['fillcolor']);
//                Fpdf::SetFillColor($color[0], $color[1], $color[2]);
//                $color = explode(",", $current_col[$b]['textcolor']);
//                Fpdf::SetTextColor($color[0], $color[1], $color[2]);
//                $color = explode(",", $current_col[$b]['drawcolor']);
//                Fpdf::SetDrawColor($color[0], $color[1], $color[2]);
//                Fpdf::SetLineWidth($current_col[$b]['linewidth']);
//                $color = explode(",", $current_col[$b]['fillcolor']);
//                Fpdf::SetDrawColor($color[0], $color[1], $color[2]);
//
//                // Draw Cell Background
//                Fpdf::Rect($x, $y, $w, $h, 'FD');
//
//                $color = explode(",", $current_col[$b]['drawcolor']);
//                Fpdf::SetDrawColor($color[0], $color[1], $color[2]);
//
//                // Draw Cell Border
//                if (substr_count($current_col[$b]['linearea'], "T") > 0) {
//                    Fpdf::Line($x, $y, $x + $w, $y);
//                }
//
//                if (substr_count($current_col[$b]['linearea'], "B") > 0) {
//                    Fpdf::Line($x, $y + $h, $x + $w, $y + $h);
//                }
//
//                if (substr_count($current_col[$b]['linearea'], "L") > 0) {
//                    Fpdf::Line($x, $y, $x, $y + $h);
//                }
//
//                if (substr_count($current_col[$b]['linearea'], "R") > 0) {
//                    Fpdf::Line($x + $w, $y, $x + $w, $y + $h);
//                }
//
//                // Print the text
//                Fpdf::MultiCell($w, $current_col[$b]['height'], $current_col[$b]['text'], 0, $a, 0);
//
//                // Put the position to the right of the cell
//                Fpdf::SetXY($x + $w, $y);
//            }
//
//            // Go to the next line
//            Fpdf::Ln($h);
//        }
//    }
//
//    // Computes the number of lines a MultiCell of width w will take
//
//
//    // If the height h would cause an overflow, add a new page immediately
//
//
//
//
//
//
//    function AutoPrintToPrinter($server, $printer, $dialog = false) {
//        //Print on a shared printer (requires at least Acrobat 6)
//        $script = "var pp = getPrintParams();";
//        if ($dialog)
//            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
//        else
//            $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
//        $script .= "pp.printerName = '\\\\\\\\" . $server . "\\\\" . $printer . "';";
//        $script .= "print(pp);";
//        $this->IncludeJS($script);
//    }
//
//}

class InvoiceController extends controller
{
    public function __construct()
    {

        $this->equipment = new Equipment();
        $this->frequency = new Devicemodel();
        $this->payment = new Payment();
    }

//    public function index(Request $request,$id)
//    {
//
//        $get = Input::all();
//        $comments['comments'] = $get['comments'];
//
//        DB::table('tbl_purchase_order')->where('id', $get['id'])->update($comments);
//
//       $purchase = $this->payment->orderDetails($id);
//      // print_r($purchase);die;
//       if($purchase)
//       {
//           $billingAddress = $this->payment->serviceBillAddress($purchase->request_id);
//           $shippingAddress = $this->payment->serviceShippingAddress($purchase->request_id);
//           $totalReceived = $this->payment->totalRequestItems($purchase->request_id);
//           $totalReturned = $this->payment->totalOrderItems($id);
//           $setups = $this->payment->setups($purchase->customer_id);
//           $select = array('OI.order_item_amt','E.asset_no', 'E.serial_no', 'E.pref_contact',
//               'E.pref_tel', 'E.pref_email', 'E.location',
//               'EM.model_name');
//           $orderItems = $this->payment->orderItems($id,array('select' => $select));
//       }
//
//        $pdf = new pdf('P', 'ms', 'A4');
//        Fpdf::AliasNbPages();
//        Fpdf::SetMargins(10, 10, 10);
//        Fpdf::AddPage();
//
//        Fpdf::SetFont('Arial', 'B', 7);
//        Fpdf::SetDrawColor(255, 255,255);
//        Fpdf::Rect(18, 25, 47, 35);
//
//
//        Fpdf::SetXY(18, 25);
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, 'NOVAMED INC,', 0, 1, 'L');
//        Fpdf::SetFont('Arial', '', 7);
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, '8136 LAWNDALE AVE., SKOKIE,', 0, 1, 'L');
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, 'IL 60076-3413,', 0, 1, 'L');
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, 'TEL: 1-800-354-6676,', 0, 1, 'L');
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, 'FAX:1-847-675-3322', 0, 1, 'L');
//        Fpdf::SetX(18);
//        Fpdf::Cell(47, 5, 'email: support@novamed1.com', 0, 1, 'L');
//
//        $fill_col = "0,0,0";
//        $fontSize = "7";
//        $fontName = "Arial";
//        $textColor = "0,0,0";
//        $drawColor = "0,0,0";
//        $fillColor = "255,255,255";
//
//        Fpdf::SetXY(88, 25);
//        $table = array();
//
//        //$pdf->SetXY(78, 25);
//        $cols = array();
//        $cols[] = array('text' => 'Total Pipettes', 'width' => '20', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => $totalReturned, 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        Fpdf::SetLeftMargin(88);
//
//        $pdf->WriteTable($table);
//
//        $cityState = isset($billingAddress->city)?$billingAddress->city:'' . ',' . isset($billingAddress->state)?$billingAddress->state:'' . ',' . isset($billingAddress->zip_code)?$billingAddress->zip_code:'';
//
//        Fpdf::SetFont('Arial', 'B', 7);
//        //   $pdf->SetDrawColor(255, 255,255);
//        Fpdf::Rect(138, 25, 67, 35);
//        Fpdf::SetXY(138, 25);
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, 'BILL TO:', 0, 1, 'L');
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, ucwords(isset($billingAddress->billing_contact)?$billingAddress->billing_contact:''), 0, 1, 'L');
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, isset($billingAddress->address1)?$billingAddress->address1:'', 0, 1, 'L');
//        Fpdf::SetFont('Arial', '', 7);
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, isset($billingAddress->address2)?$billingAddress->address2:'', 0, 1, 'L');
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, $cityState, 0, 1, 'L');
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, 'TEL:'.isset($billingAddress->phone)?$billingAddress->phone:'', 0, 1, 'L');
//        Fpdf::SetX(138);
//        Fpdf::Cell(67, 5, 'FAX:'.isset($billingAddress->fax)?$billingAddress->fax:'', 0, 1, 'L');
//
//        $cityState1 = isset($shippingAddress->city)?$shippingAddress->city:'' . ',' . isset($shippingAddress->state)?$shippingAddress->state:''. ',' .isset($shippingAddress->zip_code)?$shippingAddress->zip_code:'';
//
//        Fpdf::SetFont('Arial', 'B', 7);
//        //   $pdf->SetDrawColor(255, 255,255);
//        Fpdf::Rect(218, 25, 67, 35);
//        Fpdf::SetXY(218, 25);
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, 'SHIP TO:', 0, 1, 'L');
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, ucwords($shippingAddress->customer_name), 0, 1, 'L');
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, $shippingAddress->address1, 0, 1, 'L');
//        Fpdf::SetFont('Arial', '', 7);
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, $shippingAddress->address2, 0, 1, 'L');
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, $cityState1, 0, 1, 'L');
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, '', 0, 1, 'L');
//        Fpdf::SetX(218);
//        Fpdf::Cell(67, 5, 'TEL:'.$shippingAddress->phone_num, 0, 1, 'L');
//
//        $Y =Fpdf::GetY() + 5; //print_r($Y);die;
//
//        Fpdf::SetXY(18, $Y);
//        $table = array();
//        $cols = array();
//        $cols[] = array('text' => 'Customer ID', 'width' => '25', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => '', 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => 'Payment Type', 'width' => '20', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => ucfirst($setups->name), 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => 'Term', 'width' => '17', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => $setups->pay_terms, 'width' => '20', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        //$pdf->SetXY(78, 25);
//        $cols = array();
//        $cols[] = array('text' => 'Service Location', 'width' => '25', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => '', 'width' => '50', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => 'Ship Date', 'width' => '17', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => date('M-d-Y', strtotime(str_replace('/', '-', $billingAddress->service_schedule_date))), 'width' => '20', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        Fpdf::SetLeftMargin(18);
//        $pdf->WriteTable($table);
//
//        Fpdf::SetXY(138, $Y);
//        $table = array();
//        $cols = array();
//        $cols[] = array('text' => 'WORK ORDER', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => '', 'width' => '37', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        //$pdf->SetXY(78, 25);
//        $cols = array();
//        $cols[] = array('text' => 'PURCHASE ORDER', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => '', 'width' => '37', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        Fpdf::SetLeftMargin(138);
//        $pdf->WriteTable($table);
//
//        Fpdf::SetXY(218, $Y);
//        $table = array();
//        $cols = array();
//        $cols[] = array('text' => 'SERVICE REQUEST', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => $billingAddress->request_no, 'width' => '37', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        //$pdf->SetXY(78, 25);
//        $cols = array();
//        $cols[] = array('text' => 'SERVICE DATE', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $cols[] = array('text' => date('M-d-Y', strtotime(str_replace('/', '-', $billingAddress->service_schedule_date))), 'width' => '37', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTBR');
//        $table[] = $cols;
//        Fpdf::SetLeftMargin(218);
//        $pdf->WriteTable($table);
//
//        Fpdf::SetXY(18, Fpdf::GetY()+4);
//
//        $table = array();
//        $cols = array();
//        $cols[] = array('text' => 'Line#', 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Catalogue#', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Item#', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Serial#', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Description', 'width' => '90', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Qty', 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Price', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $cols[] = array('text' => 'Ext.Amount', 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => 'B', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'LTR');
//        $table[] = $cols;
//        $cols = array();
//        $cols[] = array('text' => '', 'width' => '270', 'height' => '1', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'T');
//        $table[] = $cols;
//
//
//        $i = 0;
//
//        $totalPrice = 0;
//
//
//        foreach ($orderItems as $value) {
//
//
//            $cols = array();
//            $cols[] = array('text' => $i + 1, 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => $value->asset_no, 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => $value->asset_no, 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => $value->serial_no, 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => $value->model_name, 'width' => '90', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => '1', 'width' => '15', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => '$ '.number_format($value->order_item_amt,2), 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $cols[] = array('text' => '$ '.number_format($value->order_item_amt,2), 'width' => '30', 'height' => '5', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => '');
//            $table[] = $cols;
//
//            $totalPrice += $value->order_item_amt;
//
//            $i++;
//        }
//
//        Fpdf::SetLeftMargin(18);
//        $pdf->WriteTable($table);
//
//        Fpdf::SetXY(18, Fpdf::GetY()+1);
//        $table = array();
//        $cols = array();
//        $cols[] = array('text' => '', 'width' => '270', 'height' => '1', 'align' => 'L', 'font_name' => $fontName, 'font_size' => $fontSize, 'font_style' => '', 'fillcolor' => $fillColor, 'textcolor' => '0,0,0', 'drawcolor' => $drawColor, 'linewidth' => '0.1', 'linearea' => 'T');
//
//        $table[] = $cols;
//        Fpdf::SetLeftMargin(18);
//
//        $pdf->WriteTable($table);
//
//        $footer = Fpdf::GetY();
//
//        $note = $purchase->comments;
//
//        // $pdf->Rect(18, $footer, 67, $footer);
//        Fpdf::SetFont('Arial', '', 7);
//        Fpdf::SetXY(18, $footer);
//        Fpdf::SetX(18);
//        Fpdf::MultiCell(157, 5, 'Comments', 0, 'L');
//        Fpdf::SetX(18);
//        Fpdf::MultiCell(157, 5, $note, 0, 'L');
//
//        Fpdf::SetXY(208, $footer);
//        Fpdf::SetX(208);
//        Fpdf::MultiCell(30, 5, 'Sub Total', 0, 'L');
//        Fpdf::SetXY(238,$footer);
//        Fpdf::MultiCell(30, 5, '$ '.number_format($totalPrice, 2), 0,  'R');
//        Fpdf::SetXY(208, $footer+7);
//        Fpdf::SetX(208);
//        Fpdf::MultiCell(30, 5, 'Shipping & Handling', 0, 'L');
//        Fpdf::SetXY(238,$footer+7);
//        Fpdf::MultiCell(30, 5, '$ 0.00', 0, 'R');
//        Fpdf::SetXY(208, $footer+14);
//        Fpdf::SetX(208);
//        Fpdf::MultiCell(30, 5, 'Sales Tax', 0, 'L');
//        Fpdf::SetXY(238,$footer+14);
//        Fpdf::MultiCell(30, 5, '$ 0.00', 0, 'R');
//        Fpdf::SetXY(208, $footer+21);
//        Fpdf::SetX(208);
//        Fpdf::MultiCell(30, 5, 'TOTAL', 0, 'L');
//        Fpdf::SetXY(238,$footer+21);
//        Fpdf::SetFont('Arial', 'B', 7);
//        Fpdf::MultiCell(30, 5, '$ '.number_format($totalPrice, 2), 0, 'R');
//        $path = base_path().'/public/purchaseorder/invoices';
//        Fpdf::Output($path.'/'.$purchase->order_number.'.pdf','F');
//
//        $save['id'] = $purchase->id;
//        $save['invoice_generation'] = 1;
//        $save['invoice_file'] = $purchase->order_number.'.pdf';
//        $this->payment->saveorder($save);
//
//
//
//        Fpdf::Output();die;
//    }

    public function index(Request $request, $id)
    {

        $get = Input::all();
//echo '<pre>';print_r($get);exit;
        $comments['comments'] = $get['comments'];

        DB::table('tbl_purchase_order')->where('id', $get['id'])->update($comments);



        $purchase = $this->payment->orderDetails($id);
//        echo '<pre>';print_r($purchase);exit;
        $customer =  DB::table('tbl_customer')->where('tbl_customer.id', $purchase->customer_id)
            ->join('tbl_customer_setups as cs','cs.customer_id','=','tbl_customer.id','left')
            ->join('tbl_pay_method as pm','pm.id','=','cs.pay_method','left')
            ->first();
        $primaryContact = DB::table('tbl_users')->where('tbl_users.id', $customer->user_id)->orderby('tbl_users.id','ASC')->first();
        $prefContact = DB::table('tbl_customer_contacts')->where('tbl_customer_contacts.customer_id', $purchase->customer_id)->orderby('tbl_customer_contacts.id','ASC')->first();
        $primaryContactName = (isset($primaryContact->name)&&$primaryContact->name)?$primaryContact->name:'';
        $prefContactName = (isset($prefContact->contact_name)&&$prefContact->contact_name)?$prefContact->contact_name:'';
        $serviceRequestNo = '';
        $workOrderNo = '';
        $serviceDate='';
        $shipDate='';
        $orderNo = '';
        $totalSpareAmt = 0;
        $serviceRequest = DB::table('tbl_service_request')->where('id', $purchase->request_id)->first();
        if ($customer) {
            $shipping_id = DB::table('tbl_customer_setups')->where('customer_id', '=', $purchase->customer_id)->first();
            if ($shipping_id) {
                $shipping_cost = DB::table('tbl_shipping')->where('id', '=', $shipping_id->shipping)->first();
                if($serviceRequest->on_site==2)
                {
                    $Shipping_Charge = isset($shipping_cost->shipping_charge) ? $shipping_cost->shipping_charge : 0;
                }
                else
                {
                    $Shipping_Charge = 0;
                }

            }
        }


        if($purchase)
        {

            $serviceRequestNo = (isset($serviceRequest->request_no)&&$serviceRequest->request_no)?$serviceRequest->request_no:'';
            $workOrder = DB::table('tbl_work_order')->where('request_id', $purchase->request_id)->first();
            $workOrderNo = (isset($workOrder->work_order_number)&&$workOrder->work_order_number)?$workOrder->work_order_number:'';
            $orderNo = (isset($purchase->order_number)&&$purchase->order_number)?$purchase->order_number:'';
            $serviceDate = (isset($serviceRequest->service_schedule_date)&&$serviceRequest->service_schedule_date)?date('m-d-Y',strtotime(str_replace('/','-',$serviceRequest->service_schedule_date))):'';
            $shipDate = (isset($serviceRequest->service_schedule_date)&&$serviceRequest->service_schedule_date)?date('m-d-Y',strtotime(str_replace('/','-',$serviceRequest->service_schedule_date.' +5 days'))):'';
        }



        //echo '<pre>';print_r($customer);'</pre>';die;
        if ($purchase) {
            $billingAddress = $this->payment->serviceBillAddress($purchase->request_id);
            //echo '<pre>';print_r($billingAddress);'</pre>';die;
            $shippingAddress = $this->payment->serviceShippingAddress($purchase->request_id);

            $totalReceived = $this->payment->totalRequestItems($purchase->request_id);

            $totalReturned = $this->payment->totalOrderItems($id);

            $setups = $this->payment->setups($purchase->customer_id);

            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
                'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'OI.request_item_id as servicerequestitemid',
                'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
                'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid','SP.service_plan_name'
            );
            $orderItems = $this->payment->orderItems($id, array('select' => $select));

            $order = $this->payment->orderDetailsView($purchase->id);

        }

        $order->invoice_date = isset($order->invoice_date) ? $order->invoice_date : date('Y-m-d') ;
        $data['billing'] = $billingAddress;
        $data['shipping'] = $shippingAddress;
        $data['order'] = $order;
        $data['customer'] = $customer;

        $data['serviceRequestNo'] = $serviceRequestNo;
        $data['workOrderNo'] = $workOrderNo;
        $data['serviceDate'] = $serviceDate;
        $data['shipDate'] = $shipDate;
        $data['orderNo'] = $orderNo;


        $temp = array();
        if ($orderItems) {
            $i = 1;
            $orderItemAmount = 0;
            foreach ($orderItems as $orderkey => $orderval) {
                $orderItemAmount += $orderval->order_item_amt;
                $serviceReqItemId = $orderval->serviceReqItemId;
                $param = array();
                if ($serviceReqItemId) {
                    $getSpareParts = $this->payment->getParts($serviceReqItemId);


                    $y = 1;
                    $partAmount[$orderkey] = 0;
                    if ($getSpareParts) {
                        $workOrderSpares = json_decode($getSpareParts->workorder_spares);
                        $workOrderchecklist = explode(',',$getSpareParts->workorder_checklists);
//                        echo '<pre>';print_r($workOrderchecklist);exit;
                        if($workOrderchecklist){
                            foreach($workOrderchecklist as $row){
                                $ID = str_replace('~','',$row);
                                $getchecklist = $this->payment->getChecklist($ID);
                                $checklist[$orderkey][] =   $getchecklist->title ;
                            }
                        }

//                        $checklistName[$orderkey][] = implode(',',$checklist);
//                        echo '<pre>';print_r($checklist);exit;
                        if ($workOrderSpares) {
                            foreach ($workOrderSpares as $sparekey => $spareval) {
                                $partAmount[$orderkey] += $spareval->amount;
                                $partId = $spareval->id;
                                $getPartDetails = $this->payment->getPartDetails($partId);
                                if ($getPartDetails) {
                                    $param[$sparekey]['spareId'] = $getPartDetails->id;
                                    $param[$sparekey]['model_id'] = $getPartDetails->model_id;
                                    $param[$sparekey]['SKU'] = $getPartDetails->sku_number;
                                    $param[$sparekey]['spareMode'] = $getPartDetails->mode_name;
                                    $param[$sparekey]['partName'] = $getPartDetails->part_name;
                                    $param[$sparekey]['partPrice'] = $getPartDetails->service_price;
                                    $param[$sparekey]['totalAmount'] = $spareval->amount;
                                    $param[$sparekey]['totalQuantity'] = $spareval->quantity;
                                    $param[$sparekey]['serialNumber'] = $i . '.' . $y;
                                    $y++;
                                }
                            }
                        }
                    }
                }
                foreach($checklist as $row){
                    $temp[$orderkey]['checklistName'] = implode(',',$row);
                }
                $temp[$orderkey]['order_item_amt'] = $orderval->order_item_amt;
                $temp[$orderkey]['asset_no'] = $orderval->asset_no;
                $temp[$orderkey]['serial_no'] = $orderval->serial_no;
                $temp[$orderkey]['service_plan'] = $orderval->service_plan_name;
                $temp[$orderkey]['pref_contact'] = $orderval->pref_contact;
                $temp[$orderkey]['pref_tel'] = $orderval->pref_tel;
                $temp[$orderkey]['pref_email'] = $orderval->pref_email;
                $temp[$orderkey]['location'] = $orderval->location;
                $temp[$orderkey]['serviceReqItemId'] = $orderval->serviceReqItemId;
                $temp[$orderkey]['servicerequestitemid'] = $orderval->servicerequestitemid;
                $temp[$orderkey]['dueequipmentid'] = $orderval->dueequipmentid;
                $temp[$orderkey]['model_description'] = $orderval->model_description;
                $temp[$orderkey]['dueEquipmentId'] = $orderval->dueEquipmentId;
                $temp[$orderkey]['equipmentId'] = $orderval->equipmentId;
                $temp[$orderkey]['equipmentid'] = $orderval->equipmentid;
                $temp[$orderkey]['equipmentModelId'] = $orderval->equipmentModelId;
                $temp[$orderkey]['equipmentmodelid'] = $orderval->equipmentmodelid;
                $temp[$orderkey]['partdetails'] = $param;
                $i++;
            }
            $totalSpareAmt = 0;
            if($partAmount)
            {
                foreach ($partAmount as $pkey=>$prow)
                {
                    $totalSpareAmt+=$prow;
                }

            }
            $totalAmount = $totalSpareAmt + $orderItemAmount ;
            $data['orderItems'] = $temp;
            $data['totalAmount'] = $totalAmount;
            $data['shipping_price'] = $Shipping_Charge;
            $data['discount'] = $purchase->discount;
//            $data['sales_tax_price'] =$get['service_tax'];
            if($purchase->grand_total){
                $data['grand_total'] = $purchase->grand_total;
            }else{
                $data['grand_total'] = $totalAmount + $Shipping_Charge;
            }
            $data['comments'] = $comments['comments'];

        }
        $data['primaryContactName'] = $primaryContactName;
        $data['prefContactName'] = $prefContactName;


//echo '<pre>';print_r($data);exit;
//        return view('paymentmanagement.invoice')
//            ->with($data);die;
        $path = base_path() . '/public/purchaseorder/invoices';
        $invoiceFile = 'invoice-' . uniqid();

        $invoiceCode = date('mdY');
        $invoiceNum = str_pad($purchase->id, 3, '0', STR_PAD_LEFT).$invoiceCode;
        $data['invoiceNum'] = $invoiceNum;
        $data['customerPoNum'] = $purchase->customer_po_num;

        view()->share($data);

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('paymentmanagement.invoice')->save($path . '/' . $invoiceFile . '.pdf', 'F');


//echo '<pre>';print_r($pdf);exit;

        $save['id'] = $purchase->id;
        $save['invoice_generation'] = 1;
        $save['shipping_price'] = $Shipping_Charge;
//        $save['sales_tax_price'] =isset($get['service_tax']) ? $get['service_tax'] : null;

        $save['invoice_file'] = $invoiceFile . '.pdf';

        //invoice number
        $invoiceCode = date('mdY');
        $invoiceNum = str_pad($purchase->id, 3, '0', STR_PAD_LEFT).$invoiceCode;


        $save['invoice_date'] = date('Y-m-d');
        $save['invoice_num'] = $invoiceNum;
        $save['spares_amount'] = $totalSpareAmt;
        $save['total_amount'] = $totalSpareAmt + $orderItemAmount;
        $save['grand_total'] =   $data['grand_total'] ;
//        echo '<pre>';print_r($save);exit;
        $this->payment->saveorder($save);
        $pathToFile = base_path() . '/public/purchaseorder/invoices/' . $invoiceFile . '.pdf';
        $query = DB::table('tbl_email_template');
        $query->where('tplid', '=', 2);
        $result = $query->first();

        $result->tplmessage = str_replace('{name}', $billingAddress->billing_contact, $result->tplmessage);

        $param['message'] = $result->tplmessage;
        $param['name'] = $billingAddress->billing_contact;
        $param['title'] = $result->tplsubject;

        $data = array('data'=>$param);
        if($billingAddress->email)
        {
            Mail::send(['html' => 'email/template'], ['data' => $param], function($message) use ($param,$billingAddress,$pathToFile) {
                $message->to($billingAddress->email)->subject
                ($param['title']);
                $message->attach($pathToFile,['as' => 'Serviceinvoice.pdf', 'mime' => 'pdf']);
            });
        }


        return redirect()->back()->with('message','Invoice Generated Successfully');
//        return response()->file($pathToFile);


    }

    function openinvoice(Request $request,$id)
    {
        if($id)
        {
            $order = $this->payment->orderDetails($id);
            if($order)
            {
                $pathToFile = base_path().'/public/purchaseorder/invoices/'.$order->invoice_file;
                return response()->file($pathToFile);
            }
        }
    }

    function downloadCustomerPO(Request $request,$id)
    {
        if($id)
        {
            $order = $this->payment->orderDetails($id);
            $realPath = 'http://54.188.82.17/novamed/';
            if($order)
            {
//                $pathToFile = base_path().'/public/purchaseorder/customerdocuments/'.$order->po_document;
                $pathToFile = $realPath.'/public/purchaseorder/customerdocuments/'.$order->po_document;
//
                return Response::make(file_get_contents($pathToFile), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'download; filename="'.$order->po_document.'"'
                ]);
                return response()->file($pathToFile);
            }
        }
    }


}