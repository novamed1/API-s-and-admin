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
use App\PHPMailer;
use Illuminate\Pagination\LengthAwarePaginator;
use Mail;
use App\Servicerequest;

class OrderController extends Controller
{

    public function __construct()
    {

        $this->equipment = new Equipment();
        $this->frequency = new Devicemodel();
        $this->payment = new Payment();
        $this->mail = new PHPMailer();
        $this->serviceModel = new Servicerequest();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Order Request';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';

        $invoice_status = array('' => '', 1 => 'Ready', 2 => 'Not Ready');
        $payment_status = array('' => '', 1 => 'Paid', 2 => 'Not Paid');
        $payment_type = array('' => '', 1 => 'PO', 2 => 'NON-PO');
        $customer = DB::table('tbl_customer')->pluck('customer_name', 'customer_name');
        $customer->prepend('');
        return view('paymentmanagement.orderrequests')->with('title', $title)->with('keyword', $keyword)->with('invoice_status', $invoice_status)->with('payment_status', $payment_status)->with('payment_type', $payment_type)->with('customer', $customer);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['C.customer_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['PO.order_number'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['SR.request_no'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['PO.total_items'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['PO.order_amt'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['PO.created_on'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['PO.order_flg'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['PO.payment_flg'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';
        $search['PO.invoice_generation'] = isset($input['sSearch_8']) ? $input['sSearch_8'] : '';


        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('PO.id', 'PO.order_number', 'PO.total_items',
            'PO.invoice_generation', 'PO.po_document', 'PO.order_amt','PO.grand_total',
            'SR.request_no', 'U.name', 'PO.created_on', 'C.customer_name', 'PO.payment_flg', 'PO.order_flg');
        $data = $this->payment->getAllPurchaseOrdersGrid($param['limit'], $param['offset'], 'PO.id', 'DESC', array('select' => $select, 'search' => $search, 'admin' => 1),
            false);

        $count = $this->payment->getAllPurchaseOrdersGrid($param['limit'], $param['offset'], 'PO.id', 'DESC', array('select' => $select, 'search' => $search, 'admin' => 1, 'count' => true),
            true);
//        echo '<pre>';print_r($data);exit;
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $getEquipments = $this->serviceModel->serviceItemEquipments(0, 0, array('request_id' => $row->request_no));
                $spare_amount[$key] = 0;
                if ($getEquipments) {
                    foreach ($getEquipments as $keySub => $rowSub) {

                        $spares = DB::table('tbl_workorder_maintenance_log as log')->
                        join('tbl_workorder_status_move as sm', 'sm.id', '=', 'log.workorder_status_id')->
                        join('tbl_work_order_items as oi', 'oi.id', '=', 'sm.workorder_item_id')->
                        select('log.workorder_spares')->
                        where('oi.request_item_id', $rowSub->request_item_id)->get()->first();

                        if ($spares) {
                            $spares_used = json_decode($spares->workorder_spares);

                            if ($spares_used) {
                                foreach ($spares_used as $sparekey => $sparerow) {
                                    $spare_amount[$key] += $sparerow->amount;
                                }

                            } //echo'<pre>';print_r($spare_amount[$key]);'</pre>';die;
                        }
                    }
                }
                if ($row->invoice_generation == 1) {
                    $invoice_generation = '<span class="label label-success">Ready</span>';
                } else {
                    $invoice_generation = '<span class="label label-danger">Not Ready</span>';
                }
                if ($row->payment_flg == 1) {
                    $payment_field = '<span class="label label-success">Paid</span>';
                } else {
                    $payment_field = '<a href="javascript:void(0);"
                                                                   class="btn btn-space btn-primary payClick"
                                                                   data-attr=' . $row->id . '
                                                                   data-invoice=' . $row->invoice_generation . '><i
                                                                            class="fa fa-money"
                                                                            aria-hidden="true"> Pay</i></a>';
                }
                $values[$key]['0'] = $row->customer_name;
                $values[$key]['1'] = $row->order_number;
                $values[$key]['2'] = $row->request_no;
                $values[$key]['3'] = $row->total_items;
                if($row->grand_total){
                    $order_amount = $row->grand_total;
                }else{
                    $order_amount =  ($row->order_amt + $spare_amount[$key]);
                }
//                $values[$key]['4'] = '<span id="amt_' . $row->id . '">$' . ($row->order_amt + $spare_amount[$key]) . '</span>';;

                $values[$key]['4'] = '<span id="amt_' . $row->id . '">$' . ($order_amount) . '</span>';;
                $values[$key]['5'] =date('m-d-Y',strtotime(str_replace('/','-',$row->created_on)));
                if ($row->order_flg == 1) {
                    $values[$key]['6'] = '<span class="">PO</span>';
                } else {
                    $values[$key]['6'] = '<span class="">NON-PO</span>';
                }


                $values[$key]['7'] = '<div class="payment_field" id="payment_' . $row->id . '">' . $payment_field . '</div>';
                $values[$key]['8'] = $invoice_generation;
                $values[$key]['9'] = "<a href=" . url('admin/orderrequestview/' . $row->id) . " class='btn btn-space btn-primary'><i class='fa fa-eye'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function orderRequestDetail(Request $request, $order_id)
    {
        if ($order_id) {
            $orderDetails = $this->payment->orderDetails($order_id);
            $customerDetails = $this->payment->customerDetails($orderDetails->customer_id);
            $billingDetails = $this->payment->billingDetails($orderDetails->customer_id);
            $shippingDetails = $this->payment->shippingDetails($orderDetails->customer_id);
            $contactDetails = $this->payment->contactDetails($orderDetails->customer_id);
//            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
//                'E.pref_tel', 'E.pref_email', 'E.location',
//                'EM.model_description', 'OI.request_item_id');
            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
                'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'OI.request_item_id as servicerequestitemid',
                'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
                'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid','OI.request_item_id'
            );
            $orderItems = $this->payment->orderItems($order_id, array('select' => $select));
            $orderItemsArray = array();
            $request = $this->serviceModel->getServiceRequest($orderDetails->request_id);

            if ($customerDetails) {
                $shipping_id = DB::table('tbl_customer_setups')->where('customer_id', '=', $orderDetails->customer_id)->first();
                if ($shipping_id) {
                    $shipping_cost = DB::table('tbl_shipping')->where('id', '=', $shipping_id->shipping)->first();
                    if($request->on_site==2)
                    {
                        $Shipping_Charge = isset($shipping_cost->shipping_charge) ? $shipping_cost->shipping_charge : 0;
                    }
                    else
                    {
                        $Shipping_Charge = 0;
                    }

                }
            }


//            $totalAmount = 0;
//            if ($orderItems) {
//                foreach ($orderItems as $key => $row) {
//                    $orderItemsArray[$key] = $row;
//                    $spare_amount = 0;
//                    $spares = DB::table('tbl_workorder_maintenance_log as log')->
//                    join('tbl_workorder_status_move as sm', 'sm.id', '=', 'log.workorder_status_id')->
//                    join('tbl_work_order_items as oi', 'oi.id', '=', 'sm.workorder_item_id')->
//                    select('log.workorder_spares')->
//                    where('oi.request_item_id', $row->request_item_id)->get()->first();
//
//                    if ($spares) {
//                        $spares_used = json_decode($spares->workorder_spares);
//
//                        if ($spares_used) {
//                            foreach ($spares_used as $sparekey => $sparerow) {
//                                $spare_amount += $sparerow->amount;
//                            }
//
//                        } //echo'<pre>';print_r($spare_amount[$key]);'</pre>';die;
//                    }
//                    $orderItemsArray[$key]->subtotal = $spare_amount;
//                    $totalAmount += $row->order_item_amt + $spare_amount ;
//                }
//            }
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
                $shipping_price = $Shipping_Charge;
                $discount  = isset($orderDetails->discount)?$orderDetails->discount :0;
//            $data['sales_tax_price'] =$get['service_tax'];
                if($orderDetails->grand_total){
                    $grand_total = $orderDetails->grand_total;
                }else{
                    $grand_total = $totalAmount + $Shipping_Charge;
                }

            }

//            echo '<pre>';print_r($temp);exit;
            //echo'<pre>';print_r($totalAmount);exit;
//            return view('paymentmanagement.orderdetail')->with('order', $orderDetails)
//                ->with('customer', $customerDetails)
//                ->with('billing', $billingDetails)
//                ->with('shipping', $shippingDetails)
//                ->with('contact', $contactDetails)
//                ->with('orderItems', $orderItemsArray)
//                ->with('orderDetails', $orderDetails)
//                ->with('discount', $discount)
//                ->with('title', 'Order - '.$orderDetails->order_number)
//                ->with('totalAmount',$totalamount)->with('shipping_charge',$Shipping_Charge);
//        }
        return view('paymentmanagement.orderdetail')->with('order', $orderDetails)
            ->with('customer', $customerDetails)
            ->with('billing', $billingDetails)
            ->with('shipping', $shippingDetails)
            ->with('contact', $contactDetails)
            ->with('orderItems', $temp)->with('totalAmount',$totalAmount)->with('discount',$discount)
            ->with('orderDetails', $orderDetails)->with('shipping_price',$shipping_price)->with('grand_total',$grand_total)
            ->with('title', 'Order - '.$orderDetails->order_number)
            ->with('shipping_charge',$Shipping_Charge);
    }

    }

    public function payment(Request $request)
    {
        $input = Input::all();
        $title = 'Payment';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $select = array('PO.id', 'PO.order_number', 'PO.total_items',
            'PO.invoice_generation', 'PO.po_document', 'PO.order_amt',
            'SR.request_no', 'U.name', 'PO.created_on', 'C.customer_name');
        if ($keyword != "") {
            $data['search']['keyword'] = $keyword;
            $data = $this->payment->getAllPurchaseOrders('', '', 'PO.id', 'DESC', array('select' => $select, 'search' => $data['search'], 'admin' => 1, 'payment' => 1));
        } else {
            $data = $this->payment->getAllPurchaseOrders('', '', 'PO.id', 'DESC', array('select' => $select, 'admin' => 1, 'payment' => 1));
        }

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        if ($request->ajax()) {
            return view('serviceRequest.servicerequestAjax', ['data' => $paginatedItems])->render();
        }
        return view('paymentmanagement.payment')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function paymentlistData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['C.customer_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['SR.request_no'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['PO.total_items'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['PO.grand_total'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['PO.created_on'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';

        $select = array('PO.id', 'PO.order_number', 'PO.total_items',
            'PO.invoice_generation', 'PO.po_document', 'PO.order_amt',
            'SR.request_no', 'U.name', 'PO.created_on', 'C.customer_name','PO.grand_total','PO.total_amount');
        $data = $this->payment->getAllPurchaseOrdersGrid($param['limit'], $param['offset'], 'PO.id', 'DESC', array('select' => $select, 'admin' => 1, 'payment' => 1, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->payment->getAllPurchaseOrdersGrid('', '', 'PO.id', 'DESC', array('select' => $select, 'search' => $search, 'admin' => 1, 'payment' => 1), true, array('tc.name', 'tc.description'));


//        echo '<pre>';print_r($count);die;
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->customer_name;
                $values[$key]['1'] = $row->request_no;
                $values[$key]['2'] = $row->total_items;
                if($row->grand_total){
                    $values[$key]['3']= '$' . ' ' .$row->grand_total. '';
                }else{
                    $values[$key]['3']=  '$' . ' ' .$row->total_amount. '';
                }
//                $values[$key]['4'] = Carbon::parse($row->created_on)->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y');
                $values[$key]['4'] =date('m-d-Y',strtotime(str_replace('/','-',$row->created_on)));
                $values[$key]['5'] = "<a href=" . url("admin/paymentview/" . $row->id) . "><i class='fa fa-eye'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function paymentDetail(Request $request, $order_id)
    {
        if ($order_id) {
            $orderDetails = $this->payment->orderDetails($order_id);
            $customerDetails = $this->payment->customerDetails($orderDetails->customer_id);
            $billingDetails = $this->payment->billingDetails($orderDetails->customer_id);
            $shippingDetails = $this->payment->shippingDetails($orderDetails->customer_id);
            $contactDetails = $this->payment->contactDetails($orderDetails->customer_id);
//            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
//                'E.pref_tel', 'E.pref_email', 'E.location',
//                'EM.model_description');

            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
                'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'OI.request_item_id as servicerequestitemid',
                'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
                'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid','OI.request_item_id'
            );
            $orderItems = $this->payment->orderItems($order_id, array('select' => $select));
            $request = $this->serviceModel->getServiceRequest($orderDetails->request_id);
            if ($customerDetails) {
                $shipping_id = DB::table('tbl_customer_setups')->where('customer_id', '=', $orderDetails->customer_id)->first();
                if ($shipping_id) {
                    $shipping_cost = DB::table('tbl_shipping')->where('id', '=', $shipping_id->shipping)->first();
                    if($request->on_site==2)
                    {
                        $Shipping_Charge = isset($shipping_cost->shipping_charge) ? $shipping_cost->shipping_charge : 0;
                    }
                    else
                    {
                        $Shipping_Charge =  0;
                    }

                }
            }
//            $purchase_order = DB::table('tbl_purchase_order')->where('id',$order_id)->first();
//            $discount= $purchase_order->discount;
//            $grand_total = $purchase_order->grand_total;
//            $shipping_price= $purchase_order->shipping_price;
//            $total_amount= $purchase_order->total_amount;
//            return view('paymentmanagement.paymentdetail')->with('order', $orderDetails)
//                ->with('customer', $customerDetails)->with('discount',$discount)
//                ->with('billing', $billingDetails)->with('grand_total',$grand_total)
//                ->with('shipping', $shippingDetails)->with('shipping_price',$shipping_price)
//                ->with('contact', $contactDetails)->with('total_amount',$total_amount)
//                ->with('orderItems', $orderItems)
//                ->with('title', 'Order - ' . $orderDetails->order_number);
//        }
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
                $shipping_price = $Shipping_Charge;
                $discount  = isset($orderDetails->discount)?$orderDetails->discount :0;
//            $data['sales_tax_price'] =$get['service_tax'];
                if($orderDetails->grand_total){
                    $grand_total = $orderDetails->grand_total;
                }else{
                    $grand_total = $totalAmount + $Shipping_Charge;
                }

            }
            return view('paymentmanagement.paymentdetail')->with('order', $orderDetails)
                ->with('customer', $customerDetails)
                ->with('billing', $billingDetails)
                ->with('shipping', $shippingDetails)
                ->with('contact', $contactDetails)
                ->with('orderItems', $temp)->with('totalAmount',$totalAmount)->with('discount',$discount)
                ->with('orderDetails', $orderDetails)->with('shipping_price',$shipping_price)->with('grand_total',$grand_total)
                ->with('title', 'Order - '.$orderDetails->order_number)
                ->with('shipping_charge',$Shipping_Charge);
        }

    }

    public function addPay(Request $request)
    {
        $input = Input::all();
        $save['id'] = $input['order_id'];
        $save['payment_flg'] = 1;
        $order_id = $this->payment->saveorder($save);
        if ($order_id) {
            $savelog['id'] = '';
            $savelog['order_id'] = $order_id;
            $savelog['payment_amt'] = $input['amount'];
            $savelog['payment_mode'] = $input['mode'];
            $savelog['bank_name'] = $input['bankname'];
            $savelog['account_number'] = $input['accountno'];
            $savelog['cheque_number'] = $input['chequeno'];
            $savelog['dd_number'] = isset($input['ddno']) ? $input['ddno'] : '';
            $this->payment->savepaymentlog($savelog);
        }
        die(json_encode(array('result' => true, 'message' => 'saved')));

    }


}