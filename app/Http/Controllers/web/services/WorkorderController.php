<?php

namespace App\Http\Controllers\web\services;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;

use App\Models\ServicePlan;
use App\Models\Technician;
use App\Models\Workorder;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Customer;
use Session;
use DateInterval;
use DB;
use Input;
use Response;
use Validator;
use View;
use Excel;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Servicerequest;


//use Request;

class WorkorderController extends Controller
{

    public function __construct()
    {
        $this->equipment = new Equipment();
        $this->customer = new Customer();
        $this->servicerequest = new Servicerequest();
        $this->workorder = new Workorder();
        $this->servicePlan = new ServicePlan();
        $this->technician = new Technician();
    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment Creation';

        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';

//        echo '<pre>';print_r($input);die;
        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');

        $technician = DB::table('tbl_technician')->pluck('first_name', 'id');
        $technician->prepend('Please Choose Technician', '');


        $requests = array('' => 'Select Request');
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'planName' => isset($input['planName']) ? $input['planName'] : '',
            'customerId' => isset($input['customerId']) ? $input['customerId'] : '',
            'requestNum' => isset($input['requestNum']) ? $input['requestNum'] : '',
            'maintanenceTo' => isset($input['maintanenceTo']) ? $input['maintanenceTo'] : '',
            'calibrationTo' => isset($input['calibrationTo']) ? $input['calibrationTo'] : '',
        ];
        if ($id) {
            $getequipmet = $this->dueequipments->getequipments($data['id']);
            $getvalue = $this->dueequipments->getvalues($data['id']);

            if (!$getequipmet) {
                return redirect('admin/viewlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getequipmet->id;
                $data['name'] = $getequipmet->name;
                $data['description'] = $getequipmet->description;
                $data['assetno'] = $getequipmet->asset_no;
                $data['serial_no'] = $getequipmet->serial_no;
                $data['modelId'] = $getequipmet->equipment_model_id;
                $data['customerId'] = $getequipmet->customer_id;
                $data['location'] = $getequipmet->location;
                $data['pref_contact'] = $getequipmet->pref_contact;
                $data['pref_tel'] = $getequipmet->pref_tel;
                $data['pref_email'] = $getequipmet->pref_email;
                $data['is_active'] = $getequipmet->is_active;
                $data['as_found'] = $getvalue->as_found;
                $data['as_calibrate'] = $getvalue->as_calibrate;
                $data['frequencyId'] = $getvalue->frequency_id;
                $data['lastDate'] = Carbon::parse($getvalue->last_cal_date)->format('d-m-Y');
                $data['nextDate'] = Carbon::parse($getvalue->next_due_date)->format('d-m-Y');
            }
        }

        $rules = [
            'customerId' => 'required',
            'requestNum' => 'required',
            'maintanenceTo' => 'required',
        ];
        $messages = [
            'customerId.required' => 'Please Choose Customer',
            'requestNum.required' => 'Please Choose Service Request',
            'maintanenceTo.required' => 'Please Choose Technician for Maintanence',
            'calibrationTo.required' => 'Please Choose Technician for Calibration',
        ];
        $error = array();
        $equipmentDetail = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($data, $rules, $messages);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        if ($checkStatus) {
            return view('workorder.workorderform')->with('equipmentDetail', $equipmentDetail)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid)->with('title', $title)->with('technician', $technician)->with('input', $data)->with('requests', $requests)->with('customer', $customer);

        } else {
            $post = Input::all();
            $loginuserId = Sentinel::getUser()->id;

            if ($post) {

                $temp['id'] = false;
                $temp['work_order_number'] = 'workOrder-' . uniqid();
                $temp['request_id'] = $post['requestNum'];
                $temp['maintanence_to'] = $post['maintanenceTo'];
                $temp['calibration_to'] = $post['calibrationTo'];
                $temp['created_by'] = $loginuserId;
                $saveWorkOrder = $this->workorder->saveWorkOrder($temp);
                if ($saveWorkOrder) {
                    foreach ($post['requestItemDetail'] as $row) {
                        $param['id'] = false;
                        $param['work_order_id'] = $saveWorkOrder;
                        $param['request_item_id'] = $row['reuestItemId'];
                        $saveWorkOrderItem = $this->workorder->saveWorkOrderItem($param);
                    }
                }
            }

            die(json_encode(array('result' => true, 'message' => 'Successfully Created Work Order')));


        }
    }


    function getServiceRequestCustomer()
    {
        $data = Input::all();
        $customerId = $data['customerId'];
//        echo '<pre>';print_r($customerId);die;

        $requests = DB::table('tbl_service_request')->where('customer_id', '=', $customerId)->select('request_no', 'id')->get();
//        echo '<pre>';print_r($requests);die;
        $data = '';
        $element = '<option value="">Please Select Request</option>';
        foreach ($requests as $val) {
            $element .= '<option value="' . $val->id . '">' . $val->request_no . '</option>';
        }
        $data = $element;
        die(json_encode(array('result' => true, 'data' => $data)));

    }

    function getServiceRequestItems()
    {
        $data = Input::all();
        $requestNum = $data['requestNum'];
        $items = $this->servicerequest->serviceItemEquipments(0, 0, array('request_id' => $requestNum, 'workorder' => true));
//        echo '<pre>';print_r($items);die;

        die(json_encode(array('result' => true, 'data' => $items)));
    }

    public function addWorkOrder(Request $request)
    {
        $input = Input::all();

        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Details are not found')));

        }

//        echo '<pre>';print_r($input);exit;
        $requestId = $input['requestId'];
        $maintanenceTo = $input['maintanenceTo'];
        $calibrationTo = $input['calibrationTo'];
        $loginuserId = Sentinel::getUser()->id;

        if ($input['instrumentDetails']) {
            foreach ($input['instrumentDetails'] as $workkey => $workval) {
                $postArray = array();
                foreach ($workval as $subkey => $subval) {

                    $requestItemId = isset($subval['requestItemId']) ? $subval['requestItemId'] : '';
                    if ($requestItemId) {

                        if (!in_array($subval['planId'], $postArray)) {
                            $temp['id'] = false;
//                            $temp['work_order_number'] = 'workOrder-' . uniqid();

                            $temp['work_order_number'] = "NOVWO-".str_pad($input['requestId'], 4, "0", STR_PAD_LEFT);
                            $temp['request_id'] = $requestId;
                            $temp['plan_id'] = $subval['planId'];
                            $getPlan = $this->servicePlan->getPlan($subval['planId']);

                            if ($getPlan) {
                                $temp['as_found'] = $getPlan->as_found;
                                $temp['as_calibrated'] = $getPlan->as_calibrate == 1 ? 1 : 0;
                                if ($temp['as_found'] == 1 && $temp['as_calibrated'] == 1) {
                                    $temp['status'] = 1;
                                } elseif ($temp['as_found'] == 2 && $temp['as_calibrated'] == 1) {
                                    $temp['status'] = 2;
                                } elseif ($temp['as_found'] == 1 && $temp['as_calibrated'] == 2) {
                                    $temp['status'] = 1;
                                } elseif ($temp['as_found'] == 2 && $temp['as_calibrated'] == 2) {
                                    $temp['status'] = 2;
                                } else {
                                    $temp['status'] = '';
                                }
                            } else {
                                $temp['as_found'] = '';
                                $temp['as_calibrated'] = '';
                            } //echo'<pre>';print_r($temp);'</pre>';die;
                            $temp['maintanence_to'] = $maintanenceTo;
                            $temp['calibration_to'] = $calibrationTo;
                            $temp['created_by'] = $loginuserId;
                            $temp['work_progress'] = 1;
                            $temp['workorder_date'] = Carbon::now()->toDateTimeString();

                            $saveWorkOrder = $this->workorder->saveWorkOrder($temp);

                        }
                        $param['id'] = false;
                        $param['work_order_id'] = $saveWorkOrder;
                        $param['request_item_id'] = $subval['requestItemId'];


                        if ($temp['as_found'] == 1 && $temp['as_calibrated'] == 1) {
                            $param['as_found_status'] = 'progress';
                        } elseif ($temp['as_found'] == 0 && $temp['as_calibrated'] == 1) {
                            $param['maintenance_status'] = 'progress';
                        } elseif ($temp['as_found'] == 1 && $temp['as_calibrated'] == 0) {
                            $param['as_found_status'] = 'progress';
                        } elseif ($temp['as_found'] == 0 && $temp['as_calibrated'] == 0) {
                            $param['maintenance_status'] = 'progress';
                        } else {
                            $param['maintenance_status'] = 'progress';
                        }


                        $saveWorkOrderItem = $this->workorder->saveWorkOrderItem($param);
                        $postArray['planId'] = $subval['planId'];

                    }
                }

            }


            die(json_encode(array('result' => true, 'message' => 'Successfully Added')));
        } else {
            die(json_encode(array('result' => false, 'message' => 'Not Successfully Added')));

        }
    }

    public function index(Request $request)
    {

        $input = Input::all();
        $title = 'Novamed-Work Order';
        $requests = DB::table('tbl_service_request')->pluck('request_no', 'request_no');
        $requests->prepend('', '');
        $plans = DB::table('tbl_service_plan')->pluck('service_plan_name', 'service_plan_name');
        $plans->prepend('', '');

        return view('workorder.workorderlist')->with('title', $title)->with('requests', $requests)->with('plans', $plans);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['C.customer_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['SR.request_no'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['W.work_order_number'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['totalInstruments'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['SR.service_schedule_date'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['S.service_plan_name'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['mTech.first_name'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';
        $search['cTech.first_name'] = isset($input['sSearch_8']) ? $input['sSearch_8'] : '';


        $select = array('W.id as workOrderId', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName',
            'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
            'SR.service_schedule_date as serviceSchedule', 'SR.customer_id as customerId',
            'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo',
            'W.status as workOrderStatus', 'W.workorder_date as workOrderDate', 'mTech.first_name as mName',
            'cTech.first_name as cName', DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
            JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
            JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
            JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=W.id) as totalInstruments"));
        //echo'<pre>';print_r($select);'</pre>';die;
        $data = $this->workorder->assignedworkordersGrid($param['limit'], $param['offset'], 'W.id', 'DESC', array('select' => $select, 'groupBy' => true, 'search' => $search,'totalInstruments'=>$input['sSearch_4']),
            false);

        $count = $this->workorder->assignedworkordersGrid($param['limit'], $param['offset'], 'W.id', 'DESC', array('select' => $select, 'groupBy' => true, 'search' => $search,'totalInstruments'=>$input['sSearch_4'], 'count' => true),
            true);
        if ($data) {

            $values = array();
            $i = 0;
             //echo'<pre>';print_r($data);'</pre>';die;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = '<span class="lead_numbers" data-id=' . $row->workOrderId . '>
                                                   <a href="javascript:void(0)"
                                                      id="workOrderItems"
                                                      rel=' . $row->workOrderId . '
                                                      data-toggle="collapse"
                                                      data-target="#workOrderDetail' . $row->workOrderId . '"
                                                      data-id=' . $row->workOrderId . '
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true" data-attr=' . $row->workOrderId . '></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>';
                $values[$key]['1'] = $row->customer_name;
                $values[$key]['2'] = $row->reqNumber;
                $values[$key]['3'] = $row->workOrderNumber;
                $values[$key]['4'] = "<span id=".$row->workOrderId.'_count'.">".$row->totalInstruments."</span>";
                $values[$key]['5'] = date('d-M-Y', strtotime(str_replace('/', '-', $row->serviceSchedule)));
                $values[$key]['6'] = $row->planName;
                $values[$key]['7'] = $row->mName;
                $values[$key]['8'] = $row->cName;
                $values[$key]['9'] = "<a href=" . url("admin/workOrderDetails/" . $row->workOrderId . "/" . $row->customerId) . "><i class='fa fa-eye'></a>";
                $values[$key]['10'] = " <a href='javascript:void(0)' data-src=" . url('admin/deleteWorkOrder/' . $row->workOrderId) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";

                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    function workordersublists(Request $request)
    {
        $input = Input::all();
        $workorderId = $input['id'];
        $items = $this->workorder->workOrderItems($workorderId);
//        echo '<pre>';print_r($items);exit;

        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {
                $planId = $itemval->test_plan_id;
                $workDetails[$itemkey]['planId'] = $planId;
                $workDetails[$itemkey]['planName'] = $itemval->service_plan_name;
                $workDetails[$itemkey]['equipmentId'] = $itemval->equipmentId;
                $workDetails[$itemkey]['equipmentName'] = $itemval->equipmentName;
                $workDetails[$itemkey]['request_item_id'] = $itemval->request_item_id;
                $workDetails[$itemkey]['assetNumber'] = $itemval->asset_no;
                $workDetails[$itemkey]['serialNumber'] = $itemval->serial_no;
                $workDetails[$itemkey]['modelName'] = $itemval->model_description;
                $workDetails[$itemkey]['contact'] = $itemval->pref_contact;
                $workDetails[$itemkey]['location'] = $itemval->location;
                $workDetails[$itemkey]['tel'] = $itemval->pref_tel;
                $workDetails[$itemkey]['status'] = $itemval->as_calibrated_status;
                $workDetails[$itemkey]['id'] = $itemval->work_order_items_id;
            }
        }

        $view = view('workorder.workorderSublistAjax', ['items' => $workDetails])->render();

        echo json_encode(array('result' => true, 'data' => $view));
    }


    public function workorderReport(Request $request)
    {

        $input = Input::all();
        $title = 'Novamed-Work Order Report';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $maintanto = isset($input['maintananceTo']) ? $input['maintananceTo'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $status = isset($input['status']) ? $input['status'] : '';


        $statuses = array('' => 'Please Select Status', '1' => 'As Found', '2' => 'Maintanance', '3' => 'Calibrated', '4' => 'Dispatched');

        $maintananceTo = DB::table('tbl_technician')->pluck('first_name', 'id');
        $maintananceTo->prepend('Choose Technician', '');

        $calibrationTo = DB::table('tbl_technician')->pluck('first_name', 'id');
        $calibrationTo->prepend('Choose Technician', '');

        if ($keyword != "" || $startdate != '' || $enddate != '' || $maintanto != '' || $status != '') {

            $data['search']['keyword'] = $keyword;
            $data['search']['start'] = $startdate;
            $data['search']['end'] = $enddate;
            $select = array('W.id as workOrderId', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName', 'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
                'SR.service_schedule_date as serviceSchedule', 'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo', 'W.status as workOrderStatus', 'W.workorder_date as workOrderDate');
            $data = $this->workorder->assignedworkorders('', '', array('select' => $select, 'search' => $data['search'], 'maintananceTo' => $maintanto, 'status' => $status), 'DESC', 'W.id', false, array('W.work_order_number', 'S.service_plan_name', 'SR.request_no',
                'SR.service_schedule_date', 'C.customer_name', 'W.workorder_date'));
        } else {
            $select = array('W.id as workOrderId', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName', 'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
                'SR.service_schedule_date as serviceSchedule', 'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo', 'W.status as workOrderStatus', 'W.workorder_date as workOrderDate');
            $data = $this->workorder->assignedworkorders('', '', array('select' => $select), 'DESC', 'W.id');
        }
        $temp = array();

        if (!$data->isEmpty()) {

            foreach ($data as $key => $val) {
                $temp[$key]['workOrderId'] = $val->workOrderId;
                $temp[$key]['workOrderNumber'] = $val->workOrderNumber;
                $temp[$key]['AsFound'] = $val->workAsFound;
                if ($temp[$key]['AsFound'] == 1) {
                    $temp[$key]['workAsFound'] = 'Yes';
                } else {
                    $temp[$key]['workAsFound'] = 'N/A';
                }
                $temp[$key]['AsCalibrated'] = $val->workAsCalibrated;
                if ($temp[$key]['AsCalibrated'] == 1) {
                    $temp[$key]['workAsCalibrated'] = 'Yes';
                } else {
                    $temp[$key]['workAsCalibrated'] = 'N/A';
                }
                $temp[$key]['planName'] = $val->planName;
                $temp[$key]['planAsFound'] = $val->planAsFound;
                $temp[$key]['planAsCalibrate'] = $val->planAsCalibrate;
                $temp[$key]['reqNumber'] = $val->reqNumber;
                $temp[$key]['serviceSchedule'] = $val->serviceSchedule;
                $temp[$key]['customer_name'] = $val->customer_name;
                $temp[$key]['maintanenceTo'] = $val->maintanenceTo;
                $temp[$key]['calibrationTo'] = $val->calibrationTo;
                $findMaintanence = $this->technician->getTechnician($temp[$key]['maintanenceTo']);
                $findCalibarte = $this->technician->getTechnician($temp[$key]['calibrationTo']);
                if ($findMaintanence) {
                    $temp[$key]['maintaainedBy'] = $findMaintanence->first_name;
                } else {
                    $temp[$key]['maintaainedBy'] = '';
                }
                if ($findCalibarte) {
                    $temp[$key]['calibratedBy'] = $findMaintanence->first_name;
                } else {
                    $temp[$key]['calibratedBy'] = '';
                }
                $temp[$key]['workOrderDate'] = $val->workOrderDate;
                $temp[$key]['workOrderStatus'] = $val->workOrderStatus;
            }
        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($temp, count($temp), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        if ($request->ajax()) {
            return view('workorder.workOrderReportAjax', ['data' => $paginatedItems])->render();
        }
        return view('workorder.workOrderReport')->with('data', $paginatedItems)->with('maintananceTo', $maintananceTo)->with('statuses', $statuses)->with('startdate', $startdate)->with('status', $status)->with('enddate', $enddate)->with('maintanto', $maintanto)->with('title', $title)->with('keyword', $keyword);
    }

    //for search
    public function workOrderSearchAjax()
    {

        $input = Input::all();

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $maintanto = isset($input['maintananceTo']) ? $input['maintananceTo'] : '';
//        $calibrateTo = isset($input['calibrationTo']) ? $input['calibrationTo'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $status = isset($input['status']) ? $input['status'] : '';

        $search['keyword'] = $keyword;
        $search['maintananceTo'] = $maintanto;
        $search['startdate'] = $startdate;
        $search['enddate'] = $enddate;
        $search['status'] = $status;


        $statuses = array('' => 'Please Select', '1' => 'As Found', '2' => 'Maintanance', '3' => 'Calibrated', '4' => 'Dispatched');

        $maintananceTo = DB::table('tbl_technician')->pluck('first_name', 'id');
        $maintananceTo->prepend('Choose Technician', '');

        if ($keyword != "" || $startdate != '' || $enddate != '' || $maintanto != '' || $status != '') {

            $data['search']['keyword'] = $keyword;
            $data['search']['start'] = $startdate;
            $data['search']['end'] = $enddate;
            $select = array('W.id as workOrderId', 'W.status', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName', 'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
                'SR.service_schedule_date as serviceSchedule', 'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo', 'W.status as workOrderStatus', 'W.workorder_date as workOrderDate');
            $data = $this->workorder->assignedworkorders('', '', array('select' => $select, 'search' => $data['search'], 'maintananceTo' => $maintanto, 'status' => $status), 'DESC', 'W.id', false, array('W.work_order_number', 'S.service_plan_name', 'SR.request_no',
                'SR.service_schedule_date', 'C.customer_name', 'W.workorder_date'));

            $followupsArray = [];

            // Define the Excel spreadsheet headers
            $followupsArray[] = ['Work Order Name', 'Plan Name', 'As Found', 'As Calibrate', 'Request Number', 'Maintanence To', 'Calibrated To', 'Work Order Date'];

            $temp = array();

            if (!$data->isEmpty()) {

                foreach ($data as $key => $val) {
                    $temp[$key]['workOrderId'] = $val->workOrderId;
                    $temp[$key]['workOrderNumber'] = $val->workOrderNumber;
                    $temp[$key]['AsFound'] = $val->workAsFound;
                    $temp[$key]['status'] = $val->status;
                    if ($temp[$key]['AsFound'] == 1) {
                        $temp[$key]['workAsFound'] = 'Yes';
                    } else {
                        $temp[$key]['workAsFound'] = 'N/A';
                    }
                    $temp[$key]['AsCalibrated'] = $val->workAsCalibrated;
                    if ($temp[$key]['AsCalibrated'] == 1) {
                        $temp[$key]['workAsCalibrated'] = 'Yes';
                    } else {
                        $temp[$key]['workAsCalibrated'] = 'N/A';
                    }

                    $temp[$key]['planName'] = $val->planName;
                    $temp[$key]['planAsFound'] = $val->planAsFound;
                    $temp[$key]['planAsCalibrate'] = $val->planAsCalibrate;
                    $temp[$key]['reqNumber'] = $val->reqNumber;
                    $temp[$key]['serviceSchedule'] = $val->serviceSchedule;
                    $temp[$key]['customer_name'] = $val->customer_name;
                    $temp[$key]['maintanenceTo'] = $val->maintanenceTo;
                    $temp[$key]['calibrationTo'] = $val->calibrationTo;
                    $findMaintanence = $this->technician->getTechnician($temp[$key]['maintanenceTo']);
                    $findCalibarte = $this->technician->getTechnician($temp[$key]['calibrationTo']);
                    if ($findMaintanence) {
                        $temp[$key]['maintaainedBy'] = $findMaintanence->first_name;
                    } else {
                        $temp[$key]['maintaainedBy'] = '';
                    }
                    if ($findCalibarte) {
                        $temp[$key]['calibratedBy'] = $findMaintanence->first_name;
                    } else {
                        $temp[$key]['calibratedBy'] = '';
                    }
                    $temp[$key]['workOrderDate'] = $val->workOrderDate;
                    $temp[$key]['workOrderStatus'] = $val->workOrderStatus;
                }

                $view = View::make('workorder.workOrderSearchAjax', ['data' => $temp], ['search' => $search]);

                $formData = $view->render();
                die(json_encode(array("result" => true, "formData" => trim($formData))));
            } else {
                die(json_encode(array("result" => false)));
            }


        } else {
            die(json_encode(array("result" => false)));

        }

    }

    public function exportWorkOrderSearch()
    {

        $input = Input::all();


        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $maintanto = isset($input['maintananceTo']) ? $input['maintananceTo'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $status = isset($input['status']) ? $input['status'] : '';


        if ($keyword != "" || $startdate != '' || $enddate != '' || $maintanto != '' || $status != '') {

            $data['search']['keyword'] = $keyword;
            $data['search']['start'] = $startdate;
            $data['search']['end'] = $enddate;
            $select = array('W.id as workOrderId', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName', 'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
                'SR.service_schedule_date as serviceSchedule', 'W.status as status', 'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo', 'W.status as workOrderStatus', 'W.workorder_date as workOrderDate');
            $followupsdetails = $this->workorder->assignedworkorders('', '', array('select' => $select, 'search' => $data['search'], 'maintananceTo' => $maintanto, 'status' => $status), 'DESC', 'W.id', false, array('W.work_order_number', 'S.service_plan_name', 'SR.request_no',
                'SR.service_schedule_date', 'C.customer_name', 'W.workorder_date'));


            // Initialize the array which will be passed into the Excel
            // generator.
            $followupsArray = [];

            // Define the Excel spreadsheet headers
            $followupsArray[] = ['Work Order Name', 'Plan Name', 'As Found', 'As Calibrate', 'Request Number', 'Maintanence To', 'Calibrated To', 'Work Order Date'];


            $temp = array();

            if (!$followupsdetails->isEmpty()) {

                foreach ($followupsdetails as $key => $val) {
                    $temp['workOrderNumber'] = $val->workOrderNumber;
                    $temp['planName'] = $val->planName;
                    $status = $val->status;
                    $AsFound = $val->workAsFound;


                    if ($AsFound == 1) {
                        $temp['workAsFound'] = 'Yes';
                    } else {
                        $temp['workAsFound'] = 'N/A';
                    }
                    $AsCalibrated = $val->workAsCalibrated;
                    if ($AsCalibrated == 1) {
                        $temp['workAsCalibrated'] = 'Yes';
                    } else {
                        $temp['workAsCalibrated'] = 'N/A';
                    }
                    $temp['reqNumber'] = $val->reqNumber;
                    $maintanenceTo = $val->maintanenceTo;
                    $calibrationTo = $val->calibrationTo;
                    $findMaintanence = $this->technician->getTechnician($maintanenceTo);
                    $findCalibarte = $this->technician->getTechnician($calibrationTo);
                    if ($findMaintanence) {
                        $temp['maintaainedBy'] = $findMaintanence->first_name;
                    } else {
                        $temp['maintaainedBy'] = '';
                    }
                    if ($findCalibarte) {
                        $temp['calibratedBy'] = $findMaintanence->first_name;
                    } else {
                        $temp['calibratedBy'] = '';
                    }

                    $temp['workOrderDate'] = Carbon::parse($val->workOrderDate)->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('d-M-y h:i:s A');
                    $followupsArray[] = $temp;
                }


                // Generate and return the spreadsheet
                $value = \Excel::create("Novamed Work Orders", function ($excel) use ($followupsArray) {

                    // Set the spreadsheet title, creator, and description
                    $excel->setTitle("Novamed Work Orders");
                    $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
                    $excel->setDescription('Work orders in novamed');

                    // Build the spreadsheet, passing in the payments array
                    $excel->sheet('sheet1', function ($sheet) use ($followupsArray) {
                        $sheet->fromArray($followupsArray, null, 'A1', false, false);
                    });


                })->download('csv');
                die(json_encode(array('result' => true, 'message' => '')));

            }


        }
    }

//    show workOrder detail in work order page

    public function getWorkOrderItems(Request $request)
    {
        $input = Input::all();
        $workOrderId = $input['workOrderId'];
        $getWorkOrder = $this->workorder->getParticularWorkOrder($workOrderId);
        if (!$getWorkOrder) {
            die(json_encode(array('result' => false, 'message' => 'Work Order details are not found')));
        }

        $requestId = $getWorkOrder->request_id;
        $planId = $getWorkOrder->plan_id;
        $items = $this->workorder->workOrderItems($getWorkOrder->id);
        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {
                $planId = $itemval->test_plan_id;
                $workDetails[$itemkey]['planId'] = $planId;
                $workDetails[$itemkey]['planName'] = $itemval->service_plan_name;
                $workDetails[$itemkey]['equipmentId'] = $itemval->equipmentId;
                $workDetails[$itemkey]['equipmentName'] = $itemval->equipmentName;
                $workDetails[$itemkey]['request_item_id'] = $itemval->request_item_id;
                $workDetails[$itemkey]['assetNumber'] = $itemval->asset_no;
                $workDetails[$itemkey]['serialNumber'] = $itemval->serial_no;
                $workDetails[$itemkey]['modelName'] = $itemval->model_name;
                $workDetails[$itemkey]['contact'] = $itemval->pref_contact;
                $workDetails[$itemkey]['location'] = $itemval->location;
                $workDetails[$itemkey]['tel'] = $itemval->pref_tel;
                $workDetails[$itemkey]['status'] = $itemval->as_calibrated_status;
            }
            die(json_encode(array('result' => true, 'data' => $workDetails)));
        } else {
            die(json_encode(array('result' => true, 'message' => 'Sorry! Something went wrong')));
        }


    }

    public function workOrderItemDetails(Request $request, $workOrderId, $customerId)
    {
        $title = 'Work Order';
        if ($customerId) {
//            for customer info
            $data['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
            $data['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
//            for customer shipping details
            $data['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);

        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;

        $getWorkOrder = $this->workorder->getParticularWorkOrder($workOrderId);

        if ($getWorkOrder) {
            $requestId = $getWorkOrder->request_id;
            $maintanencyBy = $getWorkOrder->maintanence_to;
            $calibrationBy = $getWorkOrder->calibration_to;
            $planId = $getWorkOrder->plan_id;
            $items = $this->servicerequest->serviceItemEquipments($perPage, $offset, array('request_id' => $requestId));
            $dataCount = $this->servicerequest->serviceItemEquipments($perPage, $offset, array('request_id' => $requestId), true);
            $workDetails = array();
            if (!$items->isEmpty()) {
                foreach ($items as $itemkey => $itemval) {
                    $planId = $itemval->test_plan_id;
                    $workDetails[$itemkey]['planId'] = $planId;
                    $workDetails[$itemkey]['planName'] = $itemval->service_plan_name;
                    $workDetails[$itemkey]['equipmentId'] = $itemval->equipmentId;
                    $workDetails[$itemkey]['equipmentName'] = $itemval->equipmentName;
                    $workDetails[$itemkey]['request_item_id'] = $itemval->request_item_id;
                    $workDetails[$itemkey]['assetNumber'] = $itemval->asset_no;
                    $workDetails[$itemkey]['serialNumber'] = $itemval->serial_no;
                    $workDetails[$itemkey]['modelName'] = $itemval->model_description;
                    $workDetails[$itemkey]['contact'] = $itemval->pref_contact;
                    $workDetails[$itemkey]['location'] = $itemval->location;
                }
            }
        }
        //        echo '<pre>';print_r($planDetails);die;

//        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
//        $customer->prepend('Please Choose Customer', '');

        $technician = DB::table('tbl_technician')->pluck('first_name', 'id');
        $technician->prepend('Please Choose Technician', '');

        $itemCollection = collect($workDetails);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

//        echo '<pre>';print_r($workDetails);die;
        return view('workorder.workOrderDetails')->with('customerDetails', $data)->with('maintanencyBy', $maintanencyBy)->with('calibrationBy', $calibrationBy)->with('getWorkOrder', $getWorkOrder)->with('technician', $technician)->with('workOrderDetails', $paginatedItems)->with('title', $title)->with('workOrderId', $workOrderId);


    }

    public function changeTechnician(Request $request)
    {
        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get properly')));
        }
        $data['id'] = $input['workOrderId'];
        $data['maintanence_to'] = $input['maintanenceBy'];
        $data['calibration_to'] = $input['calibrationBy'];

        if ($data['id'] != '' && $data['maintanence_to'] != '' && $data['calibration_to'] != '') {
            $saveWorkOrder = $this->workorder->saveWorkOrder($data);
            if ($saveWorkOrder) {
                die(json_encode(array('result' => true, 'message' => 'Successfully Added')));
            }
        } else {
            die(json_encode(array('result' => false, 'message' => 'Sorry! WorkOrder not found')));
        }
    }

    //for deleting user management

    public function delete($id)
    {

        $getdetail = $this->workorder->getParticularWorkOrder($id);

//        echo '<pre>';print_r($getdetail);die;

        if ($getdetail) {


            if ($getdetail->work_progress != 1) {
                $message = Session::flash('error', "You can't able to delete this workorder");
                return redirect('admin/workorderlist')->with(['data', $message], ['message', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully!');
            $member = $this->workorder->deleteWorkorder($id);
            $member = $this->workorder->deleteWorkorderItems($id);

            return redirect('admin/workorderlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/workorderlist')->with('data', $error);
        }
    }

    function updateProperty(Request $request)
    {
        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
        $data = array();
//        if ($input['keyattr'] == 'billing') {
//            $save['id'] = $input['workOrderId'];
//            $save['billing_address_id'] = $input['id'];
//        } elseif ($input['keyattr'] == 'shipping') {
//            $save['id'] = $input['workOrderId'];
//            $save['shipping_address_id'] = $input['id'];
//        }
//        $id = $this->workorder->saveRequest($save);
        if ($input['keyattr'] == 'billing') {
            $property = $this->customer->getBilling($input['id']);
            $msg = 'Billing address has been updated';
        } elseif ($input['keyattr'] == 'shipping') {
            $property = $this->customer->getShipping($input['id']);
            $msg = 'Shipping address has been updated';
        }
        $keyattr = $input['keyattr'];

        $temp['id'] = $property->id;
        $temp['name'] = $keyattr == 'billing' ? $property->billing_contact : $property->customer_name;
        $temp['phone'] = $keyattr == 'billing' ? $property->phone : $property->phone_num;
        $temp['email'] = $keyattr == 'billing' ? $property->email : $property->email;
        $temp['address1'] = $keyattr == 'billing' ? $property->address1 : $property->address1;
        $temp['address2'] = $keyattr == 'billing' ? $property->address2 : $property->address2;
        $temp['city'] = $keyattr == 'billing' ? $property->city : $property->city;
        $temp['state'] = $keyattr == 'billing' ? $property->state : $property->state;
        $temp['zip'] = $keyattr == 'billing' ? $property->zip_code : $property->zip_code;
        echo json_encode(array('result' => true, 'data' => $temp, 'keyattr' => $keyattr, 'id' => $input['id'], 'msg' => $msg));

    }

    function deleteworkorderiem()
    {
        $post = Input::all();
        $workorderItemId = $post['workoderItemId'];
        $work_order_item = DB::table('tbl_work_order_items');
        $work_order_item->where('id','=',$workorderItemId);
        $work_order_item->select('work_order_id');
        $result = $work_order_item->get()->first();
        $workOrderId = (isset($result->work_order_id) && $result->work_order_id)?$result->work_order_id:'';
        $this->workorder->removeWorkorderItem($workorderItemId);
        die(json_encode(array('result' => true, 'workOrderId' => $workOrderId)));
    }


}
