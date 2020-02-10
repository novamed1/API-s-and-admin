<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Technicianuser;
use JWTAuthException;
use DB;
use Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Workorder;
use App\Models\Devicemodel;
use App\Models\Device;
use Validator;
use Carbon\Carbon;
use App\Workorderprocessupdate;
use PDF;

class TechnicianDashboardController extends Controller
{
    private $user;
    public $uid;
    public $cid;
    public $roleid;

    public function __construct(Technicianuser $user)
    {

        $this->user = $user;
        $this->workorder = new Workorder();
        $this->technicianuser = new Technicianuser();
        $this->workorderProcess = new Workorderprocessupdate();
        $this->device = new Devicemodel();
        $this->device_tech = new Device();
        $this->payment = new Payment();

    }

    public function assignedWorkorders(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $technician = $this->technicianuser->getUserTechnician($user['id']);
        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['week_or_month'] = isset($reqInputs['week_or_month']) ? $reqInputs['week_or_month'] : '';
        $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date', 'W.as_found', 'W.as_calibrated', 'W.status', 'cc.location', 'cc.contact_name', 'cc.email_id', 'cc.phone_no');
        $workorders = $this->workorder->assignedworkorders($fParams['limit'], $fParams['offset'], array('select' => $select, 'search' => $fParams, 'week_or_month' => $fParams['week_or_month'], 'tid' => $this->tid,'assigned_workorders'=>1));

        $temp = array();
        if ($workorders) {
            foreach ($workorders as $key => $row) {
                // $temp[$key] = (array)$row;
                $temp[$key]['id'] = $row->id;
                $temp[$key]['workorder_number'] = $row->work_order_number;
                $temp[$key]['customer_name'] = $row->customer_name;
                $temp[$key]['plan_name'] = $row->service_plan_name;
                $temp[$key]['plan_id'] = $row->plan_id;
                $temp[$key]['job_assigned_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $row->workorder_date)));
                $instruments = $this->workorder->totalInstruments($row->id);
                $temp[$key]['total_instruments'] = $instruments;
                $temp[$key]['customer_name'] = $row->customer_name;
                $temp[$key]['location'] = $row->location;
                $temp[$key]['contact_person'] = $row->contact_name;
                $temp[$key]['email'] = $row->email_id;
                $temp[$key]['phone_no'] = $row->phone_no;
                if ($row->status) {
                    switch ($row->status) {
                        case 1:
                            $status = 'As Found';
                            break;
                        case 2:
                            $status = 'Maintenance';
                            break;
                        case 3:
                            $status = 'Calibration';
                            break;
                        case 4:
                            $status = 'Ready for Review';
                            break;
                        default:
                            $status = 'Ready for Review';

                    }
                } else {
                    $status = 'Maintenance';
                }
                $temp[$key]['status'] = $status;

            }
        }
        $asFound = $this->workorder->workordercounts(array('tid' => $this->tid, 'as_found' => 1));
        $maintenance = $this->workorder->workordercounts(array('tid' => $this->tid, 'maintenance' => 1));
        $calibrated = $this->workorder->workordercounts(array('tid' => $this->tid, 'calibrated' => 1));
        $dispatched = $this->workorder->workordercounts(array('tid' => $this->tid, 'dispatched' => 1));
        $thisWeekCount = $this->workorder->workordercounts(array('weekly' => 1, 'tid' => $this->tid, 'search' => $fParams['keyword'],'pending'=>1));
        $thisMonthlyCount = $this->workorder->workordercounts(array('tid' => $this->tid, 'search' => $fParams['keyword'],'pending'=>1));
        $piegraph = array('as_found' => $asFound, 'maintenance' => $maintenance, 'calibrated' => $calibrated, 'dispatch' => $dispatched);
        $totalProcess = $this->workorder->totalWorkoders($this->tid,false);
        $calibratedProcess = $this->workorder->totalWorkoders($this->tid,'calibrated');
        $calibratedGraph['total'] = $totalProcess;
        $calibratedGraph['calibrated'] = $calibratedProcess;
        return Response::json([
            'status' => 1,
            'data' => $temp,
            'this_week_count' => $thisWeekCount,
            'this_month_count' => $thisMonthlyCount,
            'piegraph' => $piegraph,
            'calibratedGraph'=>$calibratedGraph
        ], 200);
    }

    public function startworkorder(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $technician = $this->technicianuser->getUserTechnician($user['id']);
        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();
        $input = [
            'work_order_id' => $reqInputs['work_order_id']
        ];
        $rules = array(

            'work_order_id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }
        $workorderid = $input['work_order_id'];
        $workorder = $this->technicianuser->getWorkorder($workorderid);
        if (!$workorder) {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }
        $save['id'] = $workorderid;
        $save['work_progress'] = 2;
        $this->workorder->updateWorkorder($save);
        $details = $this->technicianuser->getWorkorderDetails($workorderid);

        $labels = $this->technicianuser->getCustomerLabels($details->customer_id);
        $instruments = $this->workorder->totalInstruments($workorderid);
        $instrumentsLists = $this->technicianuser->instrumentLists($workorderid);
        //echo'<pre>';print_r($instrumentsLists);'</pre>';die;
        $billing = $this->technicianuser->getBilling($details->billing_address_id);
        $shipping = $this->technicianuser->getShipping($details->shipping_address_id);
        $servicePlanDetails = DB::table('tbl_service_plan')->where('id',$workorder->plan_id)->first();
        $instrumentsarr = array();
        $currentprocesssarr = array();
        $normalprocessarr = array();
        if ($details) {
            if ($details->status) {
                switch ($details->status) {
                    case 1:
                        $status = 'As Found';
                        break;
                    case 2:
                        $status = 'Maintenance';
                        break;
                    case 3:
                        $status = 'Calibration';
                        break;
                    default:
                        $status = 'Ready for Review';
                        break;
                }
            } else {
                $status = 'Maintenance';
            }
        }
        if ($instrumentsLists) {

            $statusArray = array('completed','progress','none');

            foreach ($instrumentsLists as $inskey => $insrow) {
                $instrument_status = array();
                $as_found_status = array();
                $maintenance_status = array();
                $as_calibrated_status = array();
                $despatched_status = array();
                $instrumentsarr[$inskey]['id'] = $insrow->work_order_item_id;
                $instrumentsarr[$inskey]['equipment_id'] = $insrow->id;
                $instrumentsarr[$inskey]['asset_no'] = $insrow->asset_no;
                $instrumentsarr[$inskey]['serial_no'] = $insrow->serial_no;
                $instrumentsarr[$inskey]['model'] = $insrow->model_name;
                $instrumentsarr[$inskey]['model_id'] = $insrow->model_id;
                $instrumentsarr[$inskey]['description'] = $insrow->model_description;
                $instrumentsarr[$inskey]['plan_name'] = $insrow->service_plan_name;
                $instrumentsarr[$inskey]['intrument_name'] = $insrow->product_type_name;
                if($servicePlanDetails->as_found==1)
                {
                    $instrumentsarr[$inskey]['as_found'] = $insrow->as_found_status;
                }
                else
                {
                    $instrumentsarr[$inskey]['as_found'] = 'not applicable';
                }

                $instrumentsarr[$inskey]['as_cal'] = $insrow->as_calibrated_status;
                $instrumentsarr[$inskey]['last_cal_date'] = $insrow->last_cal_date;
                $instrumentsarr[$inskey]['frequency'] = $insrow->frequency_name?$insrow->frequency_name:'Others';
                //$processUpdation = $this->workorder->checkProcessUpdation($insrow->work_order_item_id, $details->status);
                $processUpdation = $this->workorder->checkProcessUpdation($insrow->work_order_item_id, 3);
                if ($processUpdation) {
                    $currentprocesssarr[$inskey] = $processUpdation->workorder_item_id;
                }
                $normalprocessarr[$inskey] = $insrow->work_order_item_id;


                $equipment_id = (isset($insrow->id)&&$insrow->id)?$insrow->id:'';
                $temp_request = array();
                $temp_request_dates = array();
                if($equipment_id)
                {
                    $service_request = $this->workorder->workorderItemsServiceRequest($equipment_id,1); //print_r($service_request);die;
                    if($service_request)
                    {

                        foreach($service_request as $skey=>$srow)
                        {
                            if(!in_array($temp_request,array_column($temp_request, 'request_date')))
                            {
                                $temp_request[$skey] = (array)$srow;
                                $temp_request[$skey]['request_date'] = date('Y-m-d',strtotime($srow->service_schedule_date));
                            }

                        }
                        $instrumentsarr[$inskey]['history_dates'] = array_unique(array_column($temp_request, 'request_date'));



                    }
                }
//as_found process
                if($insrow->as_found_status && $insrow->maintenance_status && $insrow->as_calibrated_status && $insrow->despatched_status)
                {
                    switch ($insrow->as_found_status)
                    {
                           case $statusArray[0]:
                               $instrument_status['as_found_status']['completed'] = true;
                               $instrument_status['as_found_status']['progress'] = false;
                            break;
                        case $statusArray[1]:
                            $instrument_status['as_found_status']['completed'] = false;
                            $instrument_status['as_found_status']['progress'] = true;
                            break;
                        default:
                            $instrument_status['as_found_status']['completed'] = false;
                            $instrument_status['as_found_status']['progress'] = false;

                    }
                    switch ($insrow->maintenance_status)
                    {
                        case $statusArray[0]:
                            $instrument_status['maintenance_status']['completed'] = true;
                            $instrument_status['maintenance_status']['progress'] = false;
                            break;
                        case $statusArray[1]:
                            $instrument_status['maintenance_status']['completed'] = false;
                            $instrument_status['maintenance_status']['progress'] = true;
                            break;
                        default:
                            $instrument_status['maintenance_status']['completed'] = false;
                            $instrument_status['maintenance_status']['progress'] = false;
                    }
                    switch ($insrow->as_calibrated_status)
                    {
                        case $statusArray[0]:
                            $instrument_status['as_calibrated_status']['completed'] = true;
                            $instrument_status['as_calibrated_status']['progress'] = false;
                            break;
                        case $statusArray[1]:
                            $instrument_status['as_calibrated_status']['completed'] = false;
                            $instrument_status['as_calibrated_status']['progress'] = true;
                            break;
                        default:
                            $instrument_status['as_calibrated_status']['completed'] = false;
                            $instrument_status['as_calibrated_status']['progress'] = false;
                    }
                    switch ($insrow->despatched_status)
                    {
                        case $statusArray[0]:
                            $instrument_status['despatched_status']['completed'] = true;
                            $instrument_status['despatched_status']['progress'] = false;
                            break;
                        case $statusArray[1]:
                            $instrument_status['despatched_status']['completed'] = false;
                            $instrument_status['despatched_status']['progress'] = true;
                            break;
                        default:
                            $instrument_status['despatched_status']['completed'] = false;
                            $instrument_status['despatched_status']['progress'] = false;
                    }
                }
                $instrumentsarr[$inskey]['instrument_status'] = $instrument_status;
            }
        }
        //print_r($currentprocesssarr);die;
        $getCurrentProcess = array_diff($normalprocessarr, $currentprocesssarr);
        $current_work_order_item = reset($getCurrentProcess) ? reset($getCurrentProcess) : reset($normalprocessarr);
        if ($current_work_order_item) {
            $current_work_order_item_detail = $this->workorder->workorderitemdetails($current_work_order_item);
        }
        $current['customer_comments'] = (isset($current_work_order_item_detail->comments) && $current_work_order_item_detail->comments) ? $current_work_order_item_detail->comments : '';
        $current['instument_asset_no'] = (isset($current_work_order_item_detail->asset_no) && $current_work_order_item_detail->asset_no) ? $current_work_order_item_detail->asset_no : '';
        $current['instument_serial_no'] = (isset($current_work_order_item_detail->serial_no) && $current_work_order_item_detail->serial_no) ? $current_work_order_item_detail->serial_no : '';
        $current['work_order_items_id'] = (isset($current_work_order_item_detail->work_order_items_id) && $current_work_order_item_detail->work_order_items_id) ? $current_work_order_item_detail->work_order_items_id : '';
        $current['last_cal_date'] = (isset($current_work_order_item_detail->last_cal_date) && $current_work_order_item_detail->last_cal_date) ? $current_work_order_item_detail->last_cal_date : '';
        $current['frequency'] = (isset($current_work_order_item_detail->name) && $current_work_order_item_detail->name) ? $current_work_order_item_detail->name : 'Others';
        $current['completed'] = count($currentprocesssarr);
        $current['total'] = count($normalprocessarr);
        $workorderdetailsarr = array();
        $customerarr = array();
        $billingarr = array();
        $shippingarr = array();
        if ($details) {
            $workorderdetailsarr['workorder_number'] = $details->work_order_number;
            $workorderdetailsarr['totalinstruments'] = $instruments;
            $workorderdetailsarr['plan_name'] = $details->service_plan_name;

            $workorderdetailsarr['current_step'] = $status;
            $workorderdetailsarr['labeling_criteria'] = $labels;
            $asFound = $details->as_founded == 1 ? 1 : 0;
            $asCalibrated = $details->as_calibrated == 1 ? 1 : 0;
            if ($asFound && $asCalibrated) {
                $steps = array('As Found', 'Maintenance', 'Calibration', 'Ready for Review');
            } elseif ($asFound && $asCalibrated == 0) {
                $steps = array('As Found', 'Maintenance', 'Ready for Review');
            } elseif ($asFound == 0 && $asCalibrated) {
                $steps = array('Maintenance', 'Calibration', 'Ready for Review');
            } else {
                $steps = array('Maintenance', 'Ready for Review');
            }

            $customerarr['customer_name'] = $details->customer_name;
            $customerarr['address'] = $details->address1;
            $customerarr['contact_person'] = $details->contact_name;
            $customerarr['location'] = $details->location;
            $customerarr['email'] = $details->email_id;
            $customerarr['phone'] = $details->phone_no;

            $billingarr['name'] = $billing->billing_contact;
            $billingarr['street'] = $billing->address1;
            $billingarr['buildingname'] = $billing->address2;
            $billingarr['city'] = $billing->city;
            $billingarr['state'] = $billing->state;
            $billingarr['zip_code'] = $billing->zip_code;
            $billingarr['fax'] = $billing->fax;
            $billingarr['phone'] = $billing->phone;
            $billingarr['customer_type'] = (isset($details->customer_type_id)&&$details->customer_type_id)?$details->customer_type_id:0;

            $shippingarr['name'] = (isset($shipping->customer_name)&&$shipping->customer_name)?$shipping->customer_name:'---';
            $shippingarr['street'] = (isset($shipping->address1)&&$shipping->address1)?$shipping->address1:'---';
            $shippingarr['buildingname'] = (isset($shipping->address2)&&$shipping->address2)?$shipping->address2:'---';
            $shippingarr['city'] = (isset($shipping->city)&&$shipping->city)?$shipping->city:'';
            $shippingarr['state'] =  (isset($shipping->state)&&$shipping->state)?$shipping->state:'---';
            $shippingarr['zip_code'] = (isset($shipping->zip_code)&&$shipping->zip_code)?$shipping->zip_code:'---';
            $shippingarr['fax'] =  (isset($shipping->fax)&&$shipping->fax)?$shipping->fax:'---';
            $shippingarr['phone'] = (isset($shipping->phone_num)&&$shipping->phone_num)?$shipping->phone_num:'---';
            $shippingarr['customer_type'] = (isset($details->customer_type_id)&&$details->customer_type_id)?$details->customer_type_id:0;

            //check for maintenance and calibration technician

            if($workorder->maintanence_to==$this->tid && $workorder->calibration_to==$this->tid)
            {
                $move = true;
            }
            else
            {
                $move = false;
            }


            return Response::json([
                'status' => 1,
                'data' => $workorderdetailsarr,
                'count'=>count($instrumentsLists),
                'steps' => $steps,
                'instruments' => $instrumentsarr,
                'customer' => $customerarr,
                'billing' => $billingarr,
                'shipping' => $shippingarr,
                'currrentprocess' => $current,
                'instrument_move' =>$move,
                'outside_calibration' =>$details->calibration_outside
            ], 200);


        }
    }

    public function ongoingworkorder(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $technician = $this->technicianuser->getUserTechnician($user['id']);

        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['week_or_month'] = isset($reqInputs['week_or_month']) ? $reqInputs['week_or_month'] : '';
        $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date', 'W.as_found', 'W.as_calibrated', 'W.status', 'cc.location', 'cc.contact_name', 'cc.email_id', 'cc.phone_no', 'W.modified_date');
        $workorders = $this->workorder->assignedworkorders($fParams['limit'], $fParams['offset'], array('select' => $select, 'search' => $fParams['keyword'], 'week_or_month' => $fParams['week_or_month'], 'tid' => $this->tid, 'work_progress' => 2,'assigned_workorders'=>1),'DESC','W.id',false);
        $count_workorders = $this->workorder->assignedworkorders('', '', array('select' => $select, 'search' => $fParams['keyword'], 'week_or_month' => $fParams['week_or_month'], 'tid' => $this->tid, 'work_progress' => 2,'assigned_workorders'=>1),'DESC','W.id',true);

        $temp = array();
        if ($workorders) {
            foreach ($workorders as $key => $row) {
                // $temp[$key] = (array)$row;
                $temp[$key]['id'] = $row->id;
                $temp[$key]['workorder_number'] = $row->work_order_number;
                $temp[$key]['customer_name'] = $row->customer_name;
                $temp[$key]['plan_name'] = $row->service_plan_name;
                $temp[$key]['plan_id'] = $row->plan_id;
                $temp[$key]['job_assigned_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $row->workorder_date)));
                $temp[$key]['last_modified_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $row->modified_date)));
                $instruments = $this->workorder->totalInstruments($row->id);
                $temp[$key]['total_instruments'] = $instruments;
                $temp[$key]['customer_name'] = $row->customer_name;

                if ($row->status) {
                    switch ($row->status) {
                        case 1:
                            $status = 'As Found';
                            break;
                        case 2:
                            $status = 'Maintenance';
                            break;
                        case 3:
                            $status = 'Calibration';
                            break;
                        default:
                            $status = 'Ready for Review';

                    }
                } else {
                    $status = 'Maintenance';
                }
                $temp[$key]['status'] = $status;

            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count'=> $count_workorders
        ], 200);
    }

    public function workorderitemdetails(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $technician = $this->technicianuser->getUserTechnician($user['id']);

        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();

        $input = [
            'request_item_id' => $reqInputs['request_item_id']
        ];
        $rules = array(

            'request_item_id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }

        $request_item_id = $reqInputs['request_item_id'];
        // $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date','W.as_found','W.as_calibrated','W.status','cc.location','cc.contact_name','cc.email_id','cc.phone_no','W.modified_date');
        $workordersitem = $this->workorder->workorderitemdetails($request_item_id);

        $checklist = $this->workorder->checklist(2);
        $data['channel'] = isset($workordersitem->channel_name) ? $workordersitem->channel_name : '';
        $data['opertaion'] = isset($workordersitem->operation_name) ? $workordersitem->operation_name : '';
        $total = 0;
        // print_r($checklist);die;
        $temp = array();

//         $checkProcess = $this->workorderProcess->getWorkOrderProcess($request_item_id);
//         $workorder_status_id = (isset($checkProcess->id) && $checkProcess->id)?$checkProcess->id:'';
        if ($request_item_id) {
            $getoperations = $this->workorderProcess->getWorkOrderMaintenanceOperation($request_item_id);
        } else {
            $getoperations = array();
        }
        $data['water_temperature'] = isset($getoperations->water_temperature) ? $getoperations->water_temperature : '';
        $data['relevent_humidity'] = isset($getoperations->relevent_humidity) ? $getoperations->relevent_humidity : '';
        $data['barometric_pressure'] = isset($getoperations->barometric_pressure) ? $getoperations->barometric_pressure : '';
        $data['air_dencity'] = isset($getoperations->air_dencity) ? $getoperations->air_dencity : '';
        $data['z_factor'] = isset($getoperations->z_factor) ? $getoperations->z_factor : '';
        $data['liquid_dencity'] = isset($getoperations->liquid_dencity) ? $getoperations->liquid_dencity : '';
        $processChecklist = array();
        $addedSpares = array();
        if ($getoperations) {
            $processChecklist = explode(',', str_replace('~', '', $getoperations->workorder_checklists));

            $spares = json_decode($getoperations->workorder_spares);

            if ($spares) {
                foreach ($spares as $skey => $srow) {
                    $getspare = $this->workorderProcess->getSpare($srow->id);
                    $addedSpares[$skey] = (array)$srow;
                    $addedSpares[$skey]['name'] = $getspare->sku_number;
                    $addedSpares[$skey]['number'] = $getspare->part_name;
                    $total+=$srow->amount;

                }
            }

        }


        if ($checklist) {
            foreach ($checklist as $key => $row) {
                $item = $this->workorder->checklistItem($row->id);
                $temp[$key]['title'] = $row->title;
                $tempitem = array();
                foreach ($item as $ikey => $irow) {
                    $selected = in_array($irow->id, $processChecklist) ? 'yes' : 'no';
                    if ($irow->id == 2) {
                        $checked = in_array($irow->id, $processChecklist) ? true : false;
                        $tempitem[$ikey]['id'] = $irow->id;
                        $tempitem[$ikey]['title'] = 'Yes/No';
                        $tempitem[$ikey]['type'] = $irow->type;
                        $tempitem[$ikey]['selected'] = $checked;
                        $tempitem[$ikey]['add_parts'] = $irow->add_parts==1?'yes':'no';
                    } else {
                        $tempitem[$ikey]['id'] = $irow->id;
                        $tempitem[$ikey]['title'] = $irow->title;
                        $tempitem[$ikey]['type'] = $irow->type;
                        $tempitem[$ikey]['selected'] = $selected;
                        $tempitem[$ikey]['add_parts'] = $irow->add_parts==1?'yes':'no';
                    }

                    $itemsub = $this->workorder->checklistItemSub($irow->id);
                    $tempitemsub = array();
                    foreach ($itemsub as $iskey => $isrow) {
                        $selectedsub = in_array($isrow->id, $processChecklist) ? 'yes' : 'no';

                        if ($isrow->id == 2) {
                            $checkedsub = in_array($isrow->id, $processChecklist) ? true : false;
                            $tempitemsub[$iskey]['id'] = $isrow->id;
                            $tempitemsub[$iskey]['title'] = 'Yes/No';
                            $tempitemsub[$iskey]['type'] = $isrow->type;
                            $tempitemsub[$iskey]['selected'] = $checkedsub;
                            $tempitemsub[$iskey]['add_parts'] = $isrow->add_parts==1?'yes':'no';
                        } else {
                            $tempitemsub[$iskey]['id'] = $isrow->id;
                            $tempitemsub[$iskey]['title'] = $isrow->title;
                            $tempitemsub[$iskey]['type'] = $isrow->type;
                            $tempitemsub[$iskey]['selected'] = $selectedsub;
                            $tempitemsub[$iskey]['add_parts'] = $isrow->add_parts==1?'yes':'no';
                        }


                    }
                    $tempitem[$ikey]['sub_checklist'] = $tempitemsub;
                }
                $temp[$key]['checklists'] = $tempitem;
            }


        }

        $equipment = $this->workorder->workorderItemsEquipment($request_item_id);
        $equipment_id = (isset($equipment->id)&&$equipment->id)?$equipment->id:'';
        $temp_request = array();
        $temp_request_dates = array();
        if($equipment_id)
        {
            $service_request = $this->workorder->workorderItemsServiceRequest($equipment_id,1); //print_r($service_request);die;
            if($service_request)
            {

                 foreach($service_request as $skey=>$srow)
                 {
                     if(!in_array($temp_request,array_column($temp_request, 'request_date')))
                     {
                         $temp_request[$skey] = (array)$srow;
                         $temp_request[$skey]['request_date'] = date('Y-m-d',strtotime($srow->service_schedule_date));
                     }

                 }
                 $temp_request_dates = array_unique(array_column($temp_request, 'request_date'));



            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'added_spares' => $addedSpares,
            'fields' => $data,
            'totalSpareAmount' => $total,
            'history_dates'=>$temp_request_dates
        ], 200);
    }

    function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }



    public function workorderitemdetailsasfound(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $technician = $this->technicianuser->getUserTechnician($user['id']);

        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();

        $input = [
            'request_item_id' => $reqInputs['request_item_id']
        ];
        $rules = array(

            'request_item_id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }

        $request_item_id = $reqInputs['request_item_id'];
        $work_order = $this->workorderProcess->getWorkorder($request_item_id);
        $workordersitem = $this->workorder->workorderitemdetails($request_item_id);

        //check for outside calibration
        $service_plan_id = (isset($work_order->plan_id) && $work_order->plan_id) ? $work_order->plan_id : '0';
        if($service_plan_id)
        {
            $service_plan = DB::table('tbl_service_plan')->select('calibration_outside')->where('id',$service_plan_id)->first();
            if($service_plan)
            {
                if($service_plan->calibration_outside==1)
                {
                    return Response::json([
                        'status' => 1,
                        'data' => array(),
                        'environmental_conditions' => array(),
                        'environmental_standards' => array(),
                        'history_dates'=>array(),
                        'outside_calibration'=>1
                    ], 200);

                }
            }


        }

        $work_order_item_id = $workordersitem->work_order_items_id;

        $query = DB::table('tbl_work_order_items');
        $query->join('tbl_service_request_item','tbl_service_request_item.id','=','tbl_work_order_items.request_item_id');
        $query->join('tbl_due_equipments','tbl_due_equipments.id','=','tbl_service_request_item.due_equipments_id');
        $query->join('tbl_equipment','tbl_equipment.id','=','tbl_due_equipments.equipment_id');
        $query->join('tbl_equipment_model','tbl_equipment_model.id','=','tbl_equipment.equipment_model_id');
        $query->join('tbl_customer_setups','tbl_customer_setups.customer_id','=','tbl_equipment.customer_id');
        $query->where('tbl_work_order_items.id',$work_order_item_id);
        $query->select('tbl_customer_setups.cal_spec','tbl_equipment.equipment_model_id','tbl_equipment_model.brand_operation','tbl_equipment_model.brand_operation','tbl_equipment_model.channel','tbl_equipment_model.volume','tbl_equipment_model.volume_value');
        $result = $query->get()->first();
        //print_r($result);die;
        $volume_id = (isset($workordersitem->volume) && $workordersitem->volume) ? $workordersitem->volume : '0';
        switch($result->cal_spec)
        {
            case 1:
                if($volume_id==1)
                {
                    $spec = DB::table('tbl_iso_limit_tolerance');
                    $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
                    $spec->where([
                        ['channel_id', '=', $result->channel],
                        ['operation_id', '=', $result->brand_operation],
                        ['volume_id', '=', $result->volume],
                        ['tbl_iso_specifications.volume_value', '=', $result->volume_value],

                    ]);
                    $spec->select('tbl_iso_limit_tolerance.*');
                    $tolerance = $spec->get();
                }
                else
                {
                    $spec = DB::table('tbl_iso_limit_tolerance');
                    $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
                    $spec->where([
                        ['channel_id', '=', $result->channel],
                        ['operation_id', '=', $result->brand_operation],
                        ['volume_id', '=', $result->volume],
                        ['tbl_iso_specifications.volume_value', '=', $result->volume_value],

                    ]);
                    $spec->select('tbl_iso_limit_tolerance.*');
                    $tolerance = $spec->get();
                }

                break;
            case 2:
                $tolerance = $this->workorder->getTolerances($workordersitem->emodel_id);
                break;
            default:
                $tolerance = $this->workorder->getTolerances($workordersitem->emodel_id);
                break;
        }

       // echo '<pre>';print_r($tolerance);die;
        $service_plan_id = (isset($workordersitem->test_plan_id) && $workordersitem->test_plan_id) ? $workordersitem->test_plan_id : '0';
        //print_r($service_plan_id);die;
        $operation_id = (isset($workordersitem->brand_operation) && $workordersitem->brand_operation) ? $workordersitem->brand_operation : '0';

        $channel_number_id = (isset($workordersitem->channel_number_id) && $workordersitem->channel_number_id) ? $workordersitem->channel_number_id : '0';

        $pricingChannelPoint = $this->workorder->servicePlanPricing($service_plan_id, $operation_id, $volume_id, $channel_number_id);
        //echo'<pre>';print_r($pricingChannelPoint);'</pre>';die;

        $channel_number = (isset($pricingChannelPoint->point_channel) && $pricingChannelPoint->point_channel) ? $pricingChannelPoint->point_channel : '0';
       // print_r($channel_number);die;
        //$channel_number = (isset($workordersitem->channel_number) && $workordersitem->channel_number) ? $workordersitem->channel_number : '0';
        $channel_test_point = array();
        if ($service_plan_id) {
            $service_plan = $this->workorder->service_plan($service_plan_id);
            if($volume_id==1)
            {
                $test_point_array = (isset($service_plan->as_found_TP) && $service_plan->as_found_TP) ? explode(',', $service_plan->as_found_TP) : array();
            }
            else
            {
                $test_point_array = array('TP1');
            }

            //print_r($test_point_array);die;
            $test_point_array_id = (isset($service_plan->as_found_value) && $service_plan->as_found_value) ? explode(',', $service_plan->as_found_value) : array();
           // print_r($test_point_array_id);die;
            $workOrderAsFound = $this->workorderProcess->getWorkOrderAsFoundOperation($request_item_id);

            $channelBasedArray = array();
            $channelunique = array();
            $common_environmental_conditions = array();

            $temp = array();
            $temp1 = array();
            $temp2 = array();
            $test_point = array();
            if ($test_point_array) {

                foreach($test_point_array as $key=>$row)
                {
                    $temp[$key]['id'] = $test_point_array_id[$key];
                    $temp[$key]['test_point'] = $row;
                }
//                for ($a = 0; $a < $channel_number; $a++) {
//                    $channel_value = $a + 1;
//                    foreach ($temp as $key=>$row)
//                    {
//                        $temp1[$a][$key] = $row;
//                        $temp1[$a][$key]['channel'] =$channel_value;
//
//                    }
//
//                }
                //echo '<pre>';print_r($temp);die;
                foreach($temp as $key=>$row)
                {
                    for($a=0;$a<$channel_number;$a++)
                    {
                        $channel_value = $a+1;
                        $temp1[$key][$a]=$row;
                        $temp1[$key][$a]['channel'] =$channel_value;

                    }
                }

                if($temp1)
                {
                    foreach($temp1 as $k=>$r)
                    {
                        $temp2 = array_merge($temp2, $r);
                    }
                }
              // echo '<pre>';print_r($temp2);die;

                  $no_of_samples = $service_plan->name;
                    $channel_value = $a + 1;
                   $volumeValueForFixed = (isset($workordersitem->volume_value)&&$workordersitem->volume_value)?str_replace('-','',$workordersitem->volume_value):0;
                    foreach ($temp2 as $key => $testpointrow) {
                        $getChannelBased = $this->workorderProcess->getWorkOrderAsFoundOperationChannel($request_item_id, $testpointrow['channel'],$testpointrow['id']);
                        //print_r($tolerance[0]); die;
                        if ($getChannelBased) {
                            $test_point[$key]['test_point'] = $testpointrow['test_point'];
                            $test_point[$key]['channel'] = $testpointrow['channel'];
                            $test_point[$key]['test_point_id'] = $testpointrow['id'];
                            if($testpointrow['id']==1)
                            {

                                $test_point[$key]['target'] = $volume_id==1?$tolerance[0]->target_value:$volumeValueForFixed;
                                $test_point[$key]['observed_accuracy'] = $volume_id==1?$tolerance[0]->accuracy:$tolerance[0]->accuracy;
                                $test_point[$key]['observed_precision'] = $volume_id==1?$tolerance[0]->precision:$tolerance[0]->precision;
                            }
                            elseif($testpointrow['id']==2)
                            {
                                $test_point[$key]['target'] = $tolerance[1]->target_value;
                                $test_point[$key]['observed_accuracy'] = $tolerance[1]->accuracy;
                                $test_point[$key]['observed_precision'] = $tolerance[1]->precision;
                            }
                            else
                            {
                                $test_point[$key]['target'] = $tolerance[2]->target_value;
                                $test_point[$key]['observed_accuracy'] = $tolerance[2]->accuracy;
                                $test_point[$key]['observed_precision'] = $tolerance[2]->precision;
                            }
                            $samples = (isset($getChannelBased->sample_readings) && $getChannelBased->sample_readings) ? json_decode($getChannelBased->sample_readings) : '';

                            for ($i = 0; $i < $no_of_samples; $i++) {
                                //print_r($samples[$i]);die;
                                $samples_array[$i]['sample_weight'] = (isset($samples[$i]->sample_weight) && $samples[$i]->sample_weight) ? json_decode($samples[$i]->sample_weight) : '';
                                $samples_array[$i]['sample_volume'] = (isset($samples[$i]->sample_volume) && $samples[$i]->sample_volume) ? json_decode($samples[$i]->sample_volume) : '';
                                $samples_array[$i]['status'] = (isset($samples[$i]->status) && $samples[$i]->status) ? $samples[$i]->status : '';
                            }
                            $test_point[$key]['samples'] = $samples_array;
                            $test_point[$key]['mean'] = (isset($getChannelBased->reading_mean) && $getChannelBased->reading_mean) ? $getChannelBased->reading_mean : '';
                            $test_point[$key]['mean_volume'] = (isset($getChannelBased->reading_mean_volume) && $getChannelBased->reading_mean_volume) ? $getChannelBased->reading_mean_volume : '';
                            $test_point[$key]['sd'] = (isset($getChannelBased->reading_sd) && $getChannelBased->reading_sd) ? $getChannelBased->reading_sd : '';
                            $test_point[$key]['unc'] = (isset($getChannelBased->reading_unc) && $getChannelBased->reading_unc) ? $getChannelBased->reading_unc : '';
                            $test_point[$key]['accuracy'] = (isset($getChannelBased->reading_accuracy) && $getChannelBased->reading_accuracy) ? $getChannelBased->reading_accuracy : '';
                            $test_point[$key]['precision'] = (isset($getChannelBased->reading_precision) && $getChannelBased->reading_precision) ? $getChannelBased->reading_precision : '';
                            $test_point[$key]['status'] = (isset($getChannelBased->reading_status) && $getChannelBased->reading_status==1) ? 'pass' : 'fail';

                            $documentroot = $_SERVER['DOCUMENT_ROOT'] . 'novamed/public/technician/as_found/' . $getChannelBased->reading_document;
                            if(file_exists($documentroot))
                            {
                                $filepath = 'public/technician/as_found/';
                                $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filepath . $getChannelBased->reading_document;
                            }
                            else
                            {
                                $documentPath = '';
                            }

                            $test_point[$key]['document'] = (isset($getChannelBased->reading_document) && $getChannelBased->reading_document) ? $getChannelBased->reading_document : '';
                            $test_point[$key]['documentpath'] = $documentPath;

                        } else {
                            $test_point[$key]['test_point'] = $testpointrow['test_point'];
                            $test_point[$key]['channel'] = $testpointrow['channel'];
                            $test_point[$key]['test_point_id'] = $testpointrow['id'];
                            if($testpointrow['id']==1)
                            {
                                $test_point[$key]['target'] = $volume_id==1?$tolerance[0]->target_value:$volumeValueForFixed;
                                $test_point[$key]['observed_accuracy'] = $volume_id==1?$tolerance[0]->accuracy:$tolerance[0]->accuracy;
                                $test_point[$key]['observed_precision'] = $volume_id==1?$tolerance[0]->precision:$tolerance[0]->precision;
                            }
                            elseif($testpointrow['id']==2)
                            {
                                $test_point[$key]['target'] = $tolerance[1]->target_value;
                                $test_point[$key]['observed_accuracy'] = $tolerance[1]->accuracy;
                                $test_point[$key]['observed_precision'] = $tolerance[1]->precision;
                            }
                            else
                            {
                                $test_point[$key]['target'] = $tolerance[2]->target_value;
                                $test_point[$key]['observed_accuracy'] = $tolerance[2]->accuracy;
                                $test_point[$key]['observed_precision'] = $tolerance[2]->precision;
                            }

                            $samples = '';
                            for ($i = 0; $i < $no_of_samples; $i++) {
                                $samples_array[$i]['sample_weight'] = '';
                                $samples_array[$i]['sample_volume'] = '';
                                $samples_array[$i]['status'] = '';
                            }
                            $test_point[$key]['samples'] = $samples_array;
                            $test_point[$key]['mean'] = '';
                            $test_point[$key]['mean_volume'] = '';
                            $test_point[$key]['sd'] = '';
                            $test_point[$key]['unc'] = '';
                            $test_point[$key]['accuracy'] = '';
                            $test_point[$key]['precision'] = '';
                            $test_point[$key]['status'] = '';
                            $test_point[$key]['document'] = '';
                        }




                    }


            }
        }
       $environmental_conditions = $this->workorderProcess->getWorkorderStatus($request_item_id);
        //print_r($environmental_conditions);die;
       $environmental_conditions_values = array();
       if($environmental_conditions)
       {
           $environmental_conditions_values['water_temperature'] = $environmental_conditions->water_temperature;
           $environmental_conditions_values['relevent_humidity'] = $environmental_conditions->relevent_humidity;
           $environmental_conditions_values['barometric_pressure'] = $environmental_conditions->barometric_pressure;
           $environmental_conditions_values['air_dencity'] = $environmental_conditions->air_dencity;
           $environmental_conditions_values['z_factor'] = $environmental_conditions->z_factor;
           $environmental_conditions_values['liquid_dencity'] = $environmental_conditions->liquid_dencity;
       }
       else{

           $environmental_conditions_values['water_temperature'] = $work_order->water_temperature;
           $environmental_conditions_values['relevent_humidity'] = $work_order->relevent_humidity;
           $environmental_conditions_values['barometric_pressure'] = $work_order->barometric_pressure;
           $environmental_conditions_values['air_dencity'] = $work_order->air_dencity;
           $environmental_conditions_values['z_factor'] = $work_order->z_factor;
           $environmental_conditions_values['liquid_dencity'] = $work_order->liquid_dencity;
       }

        $device_model = $this->device->Alldevicemodel();
        $techDevices = array();
        if($device_model)
        {
            foreach ($device_model as $devicekey=>$devicerow)
            {
                $deviceName = str_replace(' ','',$devicerow->name);
                $techDevices[$devicekey]['device_model'] = $devicerow->name;
                $technician_devices = $this->workorder->get_technician_model_device($this->tid,$devicerow->id);
                $devices = array();
                $device_model_name = '';
                $select_device_model_name = '';
                switch($devicerow->id)
                {
                    case 1:
                        $device_model_name = 'balance_device_id';
                        $select_device_model_name = 'balance_device_id as device_id';
                        break;
                    case 2:
                        $device_model_name = 'barometer_device_id';
                        $select_device_model_name = 'barometer_device_id as device_id';
                        break;
                    case 3:
                        $device_model_name = 'thermometer_device_id';
                        $select_device_model_name = 'thermometer_device_id as device_id';
                        break;
                    default:
                        $device_model_name = 'thermocouple_device_id';
                        $select_device_model_name = 'thermocouple_device_id as device_id';
                }
                foreach ($technician_devices as $techkey=>$techrow)
                {
                    $work_order_id = (isset($work_order->work_order_id) && $work_order->work_order_id) ? $work_order->work_order_id : '0';
                    $used_device = $this->workorder->get_used_device_by_workorder($work_order_id,$techrow->device_id,$device_model_name,$select_device_model_name);
                    if(isset($used_device->device_id) && $used_device->device_id==$techrow->device_id)
                    {
                        $selected = true;
                    }
                    else
                    {
                        $selected = false;
                    }
                    if($devicerow->id==1)
                    {
                        $devices[$techkey]['model_name'] = $techrow->model_name;
                        $devices[$techkey]['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':$techrow->serial_no;
                        $devices[$techkey]['sensitivity'] = $techrow->sensitivity;
                        $devices[$techkey]['unit'] = $techrow->unit;
                        $devices[$techkey]['device_id'] = $techrow->device_id;
                        $devices[$techkey]['selected'] = $selected;
                    }
                    else
                    {
                        $devices['model_name'] = $techrow->model_name;
                        $devices['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':$techrow->serial_no;
                        $devices['sensitivity'] = $techrow->sensitivity;
                        $devices['unit'] = $techrow->unit;
                        $devices['device_id'] = $techrow->device_id;
                        $devices['selected'] = $selected;
                    }

                }
                $environmental_conditions_values[$deviceName] = $devices;
            }

        }

        $equipment = $this->workorder->workorderItemsEquipment($request_item_id);
        $equipment_id = (isset($equipment->id)&&$equipment->id)?$equipment->id:'';
        $temp_request = array();
        $temp_request_dates = array();
        if($equipment_id)
        {
            //$service_request = $this->workorder->workorderItemsServiceRequest($equipment_id,1); //print_r($service_request);die;

            //get cal dates
            $found_cal = DB::table('tbl_workorder_asfound_log as fl');
            $found_cal->join('tbl_workorder_status_move as Sm','Sm.id','=','fl.workorder_status_id');
            $found_cal->join('tbl_work_order_items as oi','oi.id','=','Sm.workorder_item_id');
            $found_cal->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
            $found_cal->join('tbl_service_request as SR','SR.id','=','ri.service_request_id');
            $found_cal->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
            $found_cal->join('tbl_equipment as e','e.id','=','de.equipment_id');
            $found_cal->where('e.id',$equipment_id);
            $found_cal->orderby('fl.id','DESC');
            $found_cal->limit(1);
            $service_request= $found_cal->select('fl.*')->get();
            if($service_request)
            {

                foreach($service_request as $skey=>$srow)
                {
                    if(!in_array($temp_request,array_column($temp_request, 'request_date')))
                    {
                        $temp_request[$skey] = (array)$srow;
                        $temp_request[$skey]['request_date'] = date('Y-m-d',strtotime($srow->cali_date));
                    }

                }
                $temp_request_dates = array_unique(array_column($temp_request, 'request_date'));



            }
        }

        $device_model = $this->device->Alldevicemodel();
        $techDevices = array();
        if($device_model)
        {
            foreach ($device_model as $devicekey=>$devicerow)
            {
                $techDevices[$devicekey]['device_model'] = $devicerow->name;
                $techDevices[$devicekey]['device_model_id'] = $devicerow->id;
                $technician_devices = $this->workorder->get_technician_model_device($this->tid,$devicerow->id);
                $devices = array();
                $device_model_name = '';
                $select_device_model_name = '';
                switch($devicerow->id)
                {
                    case 1:
                        $device_model_name = 'balance_device_id';
                        $select_device_model_name = 'balance_device_id as device_id';
                        break;
                    case 2:
                        $device_model_name = 'barometer_device_id';
                        $select_device_model_name = 'barometer_device_id as device_id';
                        break;
                    case 3:
                        $device_model_name = 'thermometer_device_id';
                        $select_device_model_name = 'thermometer_device_id as device_id';
                        break;
                    default:
                        $device_model_name = 'thermocouple_device_id';
                        $select_device_model_name = 'thermocouple_device_id as device_id';
                }
                foreach ($technician_devices as $techkey=>$techrow)
                {

                    $used_device = $this->workorder->get_used_device($request_item_id,1,$techrow->device_id,$device_model_name,$select_device_model_name);
                    if(isset($used_device->device_id) && $used_device->device_id==$techrow->device_id)
                    {
                       $selected = true;
                    }
                    else
                    {
                        $selected = false;
                    }
                    $devices[$techkey]['model_name'] = $techrow->model_name;
                    $devices[$techkey]['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':$techrow->serial_no;
                    $devices[$techkey]['sensitivity'] = $techrow->sensitivity;
                    $devices[$techkey]['unit'] = $techrow->unit;
                    $devices[$techkey]['device_id'] = $techrow->device_id;
                    $devices[$techkey]['selected'] = $selected;
                }
                $techDevices[$devicekey]['devices'] = $devices;
            }

        }


        return Response::json([
            'status' => 1,
            'data' => $test_point,
            'environmental_conditions' => $environmental_conditions_values,
            'environmental_standards' => $techDevices,
            'history_dates'=>$temp_request_dates,
            'outside_calibration'=>0
        ], 200);
    }


    public function workorderitemdetailsascalibrated(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $technician = $this->technicianuser->getUserTechnician($user['id']);

        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();

        $input = [
            'request_item_id' => $reqInputs['request_item_id']
        ];
        $rules = array(

            'request_item_id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }

        $request_item_id = $reqInputs['request_item_id'];
        $work_order = $this->workorderProcess->getWorkorder($request_item_id);
        // $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date','W.as_found','W.as_calibrated','W.status','cc.location','cc.contact_name','cc.email_id','cc.phone_no','W.modified_date');
        $workordersitem = $this->workorder->workorderitemdetails($request_item_id);

        $work_order_item_id = $workordersitem->work_order_items_id;

        $service_plan_id = (isset($work_order->plan_id) && $work_order->plan_id) ? $work_order->plan_id : '0';
        if($service_plan_id)
        {
            $service_plan = DB::table('tbl_service_plan')->select('calibration_outside')->where('id',$service_plan_id)->first();
            if($service_plan)
            {
                if($service_plan->calibration_outside==1)
                {
                    return Response::json([
                        'status' => 1,
                        'data' => array(),
                        'environmental_conditions' => array(),
                        'environmental_standards' => array(),
                        'history_dates'=>array(),
                        'outside_calibration'=>1
                    ], 200);

                }
            }


        }


        $query = DB::table('tbl_work_order_items');
        $query->join('tbl_service_request_item','tbl_service_request_item.id','=','tbl_work_order_items.request_item_id');
        $query->join('tbl_due_equipments','tbl_due_equipments.id','=','tbl_service_request_item.due_equipments_id');
        $query->join('tbl_equipment','tbl_equipment.id','=','tbl_due_equipments.equipment_id');
        $query->join('tbl_equipment_model','tbl_equipment_model.id','=','tbl_equipment.equipment_model_id');
        $query->join('tbl_customer_setups','tbl_customer_setups.customer_id','=','tbl_equipment.customer_id');
        $query->where('tbl_work_order_items.id',$work_order_item_id);
        $query->select('tbl_customer_setups.cal_spec','tbl_equipment.equipment_model_id','tbl_equipment_model.brand_operation','tbl_equipment_model.brand_operation','tbl_equipment_model.channel','tbl_equipment_model.volume','tbl_equipment_model.volume_value');
        $result = $query->get()->first();
        $volume_id = (isset($workordersitem->volume) && $workordersitem->volume) ? $workordersitem->volume : '0';

        switch($result->cal_spec)
        {
            case 1:
                if($volume_id==1)
                {
                    $spec = DB::table('tbl_iso_limit_tolerance');
                    $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
                    $spec->where([
                        ['channel_id', '=', $result->channel],
                        ['operation_id', '=', $result->brand_operation],
                        ['volume_id', '=', $result->volume],
                        ['tbl_iso_specifications.volume_value', '=', $result->volume_value],
                    ]);
                    $spec->select('tbl_iso_limit_tolerance.*');
                    $tolerance = $spec->get();
                }
                else
                {
                    $spec = DB::table('tbl_iso_limit_tolerance');
                    $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
                    $spec->where([
                        ['channel_id', '=', $result->channel],
                        ['operation_id', '=', $result->brand_operation],
                        ['volume_id', '=', $result->volume],
                        ['tbl_iso_specifications.volume_value', '=', $result->volume_value],
                    ]);
                    $spec->select('tbl_iso_limit_tolerance.*');
                    $tolerance = $spec->get();
                }

                break;
            case 2:
                $tolerance = $this->workorder->getTolerances($workordersitem->emodel_id);
                break;
            default:
                $tolerance = $this->workorder->getTolerances($workordersitem->emodel_id);
                break;
        }

        //echo '<pre>';print_r($tolerance);die;
        $service_plan_id = (isset($workordersitem->test_plan_id) && $workordersitem->test_plan_id) ? $workordersitem->test_plan_id : '0';
        $operation_id = (isset($workordersitem->brand_operation) && $workordersitem->brand_operation) ? $workordersitem->brand_operation : '0';

        $channel_number_id = (isset($workordersitem->channel_number_id) && $workordersitem->channel_number_id) ? $workordersitem->channel_number_id : '0';

        $pricingChannelPoint = $this->workorder->servicePlanPricing($service_plan_id, $operation_id, $volume_id, $channel_number_id); //print_r($pricingChannelPoint);die;


        $channel_number = (isset($pricingChannelPoint->point_channel) && $pricingChannelPoint->point_channel) ? $pricingChannelPoint->point_channel : '0';
        //$channel_number = (isset($workordersitem->channel_number) && $workordersitem->channel_number) ? $workordersitem->channel_number : '0';
        //echo '<pre>';print_r($workordersitem->channel_number);die;
        $channel_test_point = array();
        if ($service_plan_id) {
            $service_plan = $this->workorder->service_plan_calibrated($service_plan_id);

            if($volume_id==1)
            {
                $test_point_array = (isset($service_plan->as_calibrate_TP) && $service_plan->as_calibrate_TP) ? explode(',', $service_plan->as_calibrate_TP) : array();
            }
            else
            {
                $test_point_array = array('TP1');
            }
            //echo '<pre>';print_r($service_plan);die;
            $test_point_array_id = (isset($service_plan->as_calibrate_value) && $service_plan->as_calibrate_value) ? explode(',', $service_plan->as_calibrate_value) : array();

            $workOrderAsFound = $this->workorderProcess->getWorkOrderAsCalibratedOperation($request_item_id);

            $channelBasedArray = array();
            $channelunique = array();
            $common_environmental_conditions = array();
            $test_point = array();
            $temp = array();
            $temp1 = array();
            $temp2 = array();
            if ($test_point_array) {

                foreach($test_point_array as $key=>$row)
                {
                    $temp[$key]['id'] = $test_point_array_id[$key];
                    $temp[$key]['test_point'] = $row;
                }
//                for ($a = 0; $a < $channel_number; $a++) {
//                    $channel_value = $a + 1;
//                    foreach ($temp as $key=>$row)
//                    {
//                        $temp1[$a][$key] = $row;
//                        $temp1[$a][$key]['channel'] =$channel_value;
//
//                    }
//
//                }

                foreach($temp as $key=>$row)
                {
                    for($a=0;$a<$channel_number;$a++)
                    {
                        $channel_value = $a+1;
                        $temp1[$key][$a]=$row;
                        $temp1[$key][$a]['channel'] =$channel_value;

                    }
                }

                if($temp1)
                {
                    foreach($temp1 as $k=>$r)
                    {
                        $temp2 = array_merge($temp2, $r);
                    }
                }


                $no_of_samples = $service_plan->name;

                $channel_value = $a + 1;

                $volumeValueForFixed = (isset($workordersitem->volume_value)&&$workordersitem->volume_value)?str_replace('-','',$workordersitem->volume_value):0;
                //print_r($volumeValueForFixed);die;

                foreach ($temp2 as $key => $testpointrow) {
                    $getChannelBased = $this->workorderProcess->getWorkOrderAsCalibratedOperationChannel($request_item_id, $testpointrow['channel'],$testpointrow['id']);
                    // print_r($getChannelBased); die;
                    if ($getChannelBased) {
                        $test_point[$key]['test_point'] = $testpointrow['test_point'];
                        $test_point[$key]['channel'] = $testpointrow['channel'];
                        $test_point[$key]['test_point_id'] = $testpointrow['id'];
                        if($testpointrow['id']==1)
                        {
                            $test_point[$key]['target'] = $volume_id==1?$tolerance[0]->target_value:$volumeValueForFixed;
                            $test_point[$key]['observed_accuracy'] = $volume_id==1?$tolerance[0]->accuracy:$tolerance[0]->accuracy;
                            $test_point[$key]['observed_precision'] = $volume_id==1?$tolerance[0]->precision:$tolerance[0]->precision;
                        }
                        elseif($testpointrow['id']==2)
                        {
                            $test_point[$key]['target'] = $tolerance[1]->target_value;
                            $test_point[$key]['observed_accuracy'] =$tolerance[1]->accuracy;
                            $test_point[$key]['observed_precision'] = $tolerance[1]->precision;
                        }
                        else
                        {
                            $test_point[$key]['target'] = $tolerance[2]->target_value;
                            $test_point[$key]['observed_accuracy'] =$tolerance[2]->accuracy;
                            $test_point[$key]['observed_precision'] = $tolerance[2]->precision;
                        }
                        $samples = (isset($getChannelBased->sample_readings) && $getChannelBased->sample_readings) ? json_decode($getChannelBased->sample_readings) : '';
                        for ($i = 0; $i < $no_of_samples; $i++) {
                            $samples_array[$i]['sample_weight'] = (isset($samples[$i]->sample_weight) && $samples[$i]->sample_weight) ? json_decode($samples[$i]->sample_weight) : '';
                            $samples_array[$i]['sample_volume'] = (isset($samples[$i]->sample_volume) && $samples[$i]->sample_volume) ? json_decode($samples[$i]->sample_volume) : '';
                            $samples_array[$i]['status'] = (isset($samples[$i]->status) && $samples[$i]->status) ? $samples[$i]->status : '';
                        }
                        $test_point[$key]['samples'] = $samples_array;
                        $test_point[$key]['mean'] = (isset($getChannelBased->reading_mean) && $getChannelBased->reading_mean) ? $getChannelBased->reading_mean : '';
                        $test_point[$key]['mean_volume'] = (isset($getChannelBased->reading_mean_volume) && $getChannelBased->reading_mean_volume) ? $getChannelBased->reading_mean_volume : '';
                        $test_point[$key]['sd'] = (isset($getChannelBased->reading_sd) && $getChannelBased->reading_sd) ? $getChannelBased->reading_sd : '';
                        $test_point[$key]['unc'] = (isset($getChannelBased->reading_unc) && $getChannelBased->reading_unc) ? $getChannelBased->reading_unc : '';
                        $test_point[$key]['accuracy'] = (isset($getChannelBased->reading_accuracy) && $getChannelBased->reading_accuracy) ? $getChannelBased->reading_accuracy : '';
                        $test_point[$key]['precision'] = (isset($getChannelBased->reading_precision) && $getChannelBased->reading_precision) ? $getChannelBased->reading_precision : '';
                        $test_point[$key]['status'] = (isset($getChannelBased->reading_status) && $getChannelBased->reading_status==1) ? 'pass' : 'fail';

                        $documentroot = $_SERVER['DOCUMENT_ROOT'] . 'novamed/public/technician/as_calibrated/' . $getChannelBased->reading_document;
                        if(file_exists($documentroot))
                        {
                            $filepath = 'public/technician/as_calibrated/';
                            $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filepath . $getChannelBased->reading_document;
                        }
                        else
                        {
                            $documentPath = '';
                        }

                        $test_point[$key]['document'] = (isset($getChannelBased->reading_document) && $getChannelBased->reading_document) ? $getChannelBased->reading_document : '';
                        $test_point[$key]['documentpath'] = $documentPath;

                    } else {
                        $test_point[$key]['test_point'] = $testpointrow['test_point'];
                        $test_point[$key]['channel'] = $testpointrow['channel'];
                        $test_point[$key]['test_point_id'] = $testpointrow['id'];
                        if($testpointrow['id']==1)
                        {
                            $test_point[$key]['target'] = $volume_id==1?$tolerance[0]->target_value:$volumeValueForFixed;
                            $test_point[$key]['observed_accuracy'] = $volume_id==1?$tolerance[0]->accuracy:$tolerance[0]->accuracy;
                            $test_point[$key]['observed_precision'] = $volume_id==1?$tolerance[0]->precision:$tolerance[0]->precision;
                        }
                        elseif($testpointrow['id']==2)
                        {
                            $test_point[$key]['target'] = $tolerance[1]->target_value;
                            $test_point[$key]['observed_accuracy'] =$tolerance[1]->accuracy;
                            $test_point[$key]['observed_precision'] = $tolerance[1]->precision;
                        }
                        else
                        {
                            $test_point[$key]['target'] = $tolerance[2]->target_value;
                            $test_point[$key]['observed_accuracy'] =$tolerance[2]->accuracy;
                            $test_point[$key]['observed_precision'] = $tolerance[2]->precision;
                        }

                        $samples = '';
                        for ($i = 0; $i < $no_of_samples; $i++) {
                            $samples_array[$i]['sample_weight'] = '';
                            $samples_array[$i]['sample_volume'] = '';
                        }
                        $test_point[$key]['samples'] = $samples_array;
                        $test_point[$key]['mean'] = '';
                        $test_point[$key]['mean_volume'] = '';
                        $test_point[$key]['sd'] = '';
                        $test_point[$key]['unc'] = '';
                        $test_point[$key]['accuracy'] = '';
                        $test_point[$key]['precision'] = '';
                        $test_point[$key]['status'] = '';
                        $test_point[$key]['document'] = '';
                    }




                }


            }
        } //echo '<pre>';print_r($test_point);die;
        $environmental_conditions = $this->workorderProcess->getWorkorderStatusCalibrated($request_item_id);
        $environmental_conditions_asfound = $this->workorderProcess->getWorkOrderProcessStatus($request_item_id,1);
        $environmental_conditions_values = array();
        if($environmental_conditions)
        {
            $environmental_conditions_values['water_temperature'] = $environmental_conditions->water_temperature;
            $environmental_conditions_values['relevent_humidity'] = $environmental_conditions->relevent_humidity;
            $environmental_conditions_values['barometric_pressure'] = $environmental_conditions->barometric_pressure;
            $environmental_conditions_values['air_dencity'] = $environmental_conditions->air_dencity;
            $environmental_conditions_values['z_factor'] = $environmental_conditions->z_factor;
            $environmental_conditions_values['liquid_dencity'] = $environmental_conditions->liquid_dencity;
        }
        elseif($environmental_conditions_asfound)
        {
            $environmental_conditions_values['water_temperature'] = $environmental_conditions_asfound->water_temperature;
            $environmental_conditions_values['relevent_humidity'] = $environmental_conditions_asfound->relevent_humidity;
            $environmental_conditions_values['barometric_pressure'] = $environmental_conditions_asfound->barometric_pressure;
            $environmental_conditions_values['air_dencity'] = $environmental_conditions_asfound->air_dencity;
            $environmental_conditions_values['z_factor'] = $environmental_conditions_asfound->z_factor;
            $environmental_conditions_values['liquid_dencity'] = $environmental_conditions_asfound->liquid_dencity;
        }
        else{

            $environmental_conditions_values['water_temperature'] = $work_order->water_temperature_calibrated;
            $environmental_conditions_values['relevent_humidity'] = $work_order->relevent_humidity_calibrated;
            $environmental_conditions_values['barometric_pressure'] = $work_order->barometric_pressure_calibrated;
            $environmental_conditions_values['air_dencity'] = $work_order->air_dencity_calibrated;
            $environmental_conditions_values['z_factor'] = $work_order->z_factor_calibrated;
            $environmental_conditions_values['liquid_dencity'] = $work_order->liquid_dencity_calibrated;
        }

        $techDevices = array();
        $asFoundBalance = '';

        //check for as found balance
        $getBalance = $this->workorder->getBalanceAsFound($request_item_id,1);
        if($getBalance && $getBalance->balance_device_id)
        {
            $asFoundBalance = $getBalance->balance_device_id;
            $device_model = $this->device->Alldevicemodel(0,0,'tm.id','ASC',array('except_balance_id'=>1));
            $device_details = $this->device_tech->getdeviceAsFound($asFoundBalance);
            $devices_value['model_name'] = $device_details->model_name;
            $devices_value['serial_no'] = $device_details->sensitivity?$device_details->serial_no.'('.$device_details->sensitivity.')':$device_details->serial_no;
            $devices_value['sensitivity'] = $device_details->sensitivity;
            $devices_value['unit'] = $device_details->unit;
            $devices_value['device_id'] = $device_details->device_id;
            $devices_value['selected'] = true;
            $environmental_conditions_values[str_replace(' ','',$device_details->model_name)] = $devices_value;

        }
        else
        {
            $device_model = $this->device->Alldevicemodel();
        }
        //print_r($device_model);die;

        if($device_model)
        {
            foreach ($device_model as $devicekey=>$devicerow)
            {
                $deviceName = str_replace(' ','',$devicerow->name);

                        $techDevices[$devicekey]['device_model'] = $devicerow->name;
                        $technician_devices = $this->workorder->get_technician_model_device($this->tid,$devicerow->id);
                        $devices = array();
                        $device_model_name = '';
                        $select_device_model_name = '';
                        switch($devicerow->id)
                        {
                            case 1:
                                $device_model_name = 'balance_device_id';
                                $select_device_model_name = 'balance_device_id as device_id';
                                break;
                            case 2:
                                $device_model_name = 'barometer_device_id';
                                $select_device_model_name = 'barometer_device_id as device_id';
                                break;
                            case 3:
                                $device_model_name = 'thermometer_device_id';
                                $select_device_model_name = 'thermometer_device_id as device_id';
                                break;
                            default:
                                $device_model_name = 'thermocouple_device_id';
                                $select_device_model_name = 'thermocouple_device_id as device_id';
                        }
                        foreach ($technician_devices as $techkey=>$techrow)
                        {
                            $work_order_id = (isset($work_order->work_order_id) && $work_order->work_order_id) ? $work_order->work_order_id : '0';
                            $used_device = $this->workorder->get_used_device_by_workorder($work_order_id,$techrow->device_id,$device_model_name,$select_device_model_name);

                            if(isset($used_device->device_id) && $used_device->device_id==$techrow->device_id)
                            {
                                $selected = true;
                            }
                            else
                            {
                                $selected = false;
                            }
                            if($devicerow->id==1)
                            {
                                $devices[$techkey]['model_name'] = $techrow->model_name;
                                $devices[$techkey]['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':$techrow->serial_no;
                                $devices[$techkey]['sensitivity'] = $techrow->sensitivity;
                                $devices[$techkey]['unit'] = $techrow->unit;
                                $devices[$techkey]['device_id'] = $techrow->device_id;
                                $devices[$techkey]['selected'] = $selected;
                            }
                            else
                            {
                                $devices['model_name'] = $techrow->model_name;
                                $devices['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':$techrow->serial_no;
                                $devices['sensitivity'] = $techrow->sensitivity;
                                $devices['unit'] = $techrow->unit;
                                $devices['device_id'] = $techrow->device_id;
                                $devices['selected'] = $selected;
                            }

                        }
                        $environmental_conditions_values[$deviceName] = $devices;

            }

        }


        $equipment = $this->workorder->workorderItemsEquipment($request_item_id);
        $equipment_id = (isset($equipment->id)&&$equipment->id)?$equipment->id:'';
        $temp_request = array();
        $temp_request_dates = array();
        if($equipment_id)
        {
            //$service_request = $this->workorder->workorderItemsServiceRequest($equipment_id,1); //print_r($service_request);die;

            $cali_cal = DB::table('tbl_workorder_ascalibrated_log as cl');
            $cali_cal->join('tbl_workorder_status_move as Sm','Sm.id','=','cl.workorder_status_id');
            $cali_cal->join('tbl_work_order_items as oi','oi.id','=','Sm.workorder_item_id');
            $cali_cal->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
            $cali_cal->join('tbl_service_request as SR','SR.id','=','ri.service_request_id');
            $cali_cal->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
            $cali_cal->join('tbl_equipment as e','e.id','=','de.equipment_id');
            $cali_cal->where('e.id',$equipment_id);


            $cali_cal->orderby('cl.id','DESC');
            $cali_cal->limit(1);
            $service_request= $cali_cal->select('cl.*')->get();
            if($service_request)
            {

                foreach($service_request as $skey=>$srow)
                {
                    if(!in_array($temp_request,array_column($temp_request, 'request_date')))
                    {
                        $temp_request[$skey] = (array)$srow;
                        $temp_request[$skey]['request_date'] = date('Y-m-d',strtotime($srow->cali_date));
                    }

                }
                $temp_request_dates = array_unique(array_column($temp_request, 'request_date'));



            }
        }

        $device_model = $this->device->Alldevicemodel();
        $techDevices = array();
        if($device_model)
        {
            foreach ($device_model as $devicekey=>$devicerow)
            {
                $techDevices[$devicekey]['device_model'] = $devicerow->name;
                $techDevices[$devicekey]['device_model_id'] = $devicerow->id;
                $technician_devices = $this->workorder->get_technician_model_device($this->tid,$devicerow->id);
                $devices = array();
                $device_model_name = '';
                $select_device_model_name = '';
                switch($devicerow->id)
                {
                    case 1:
                        $device_model_name = 'balance_device_id';
                        $select_device_model_name = 'balance_device_id as device_id';
                        break;
                    case 2:
                        $device_model_name = 'barometer_device_id';
                        $select_device_model_name = 'barometer_device_id as device_id';
                        break;
                    case 3:
                        $device_model_name = 'thermometer_device_id';
                        $select_device_model_name = 'thermometer_device_id as device_id';
                        break;
                    default:
                        $device_model_name = 'thermocouple_device_id';
                        $select_device_model_name = 'thermocouple_device_id as device_id';
                }
                foreach ($technician_devices as $techkey=>$techrow)
                {

                    $used_device = $this->workorder->get_used_device($request_item_id,3,$techrow->device_id,$device_model_name,$select_device_model_name);
                    if(isset($used_device->device_id) && $used_device->device_id==$techrow->device_id)
                    {
                        $selected = true;
                    }
                    else
                    {
                        $selected = false;
                    }
                    $devices[$techkey]['model_name'] = $techrow->model_name;
                    $devices[$techkey]['serial_no'] = $techrow->sensitivity?$techrow->serial_no.'('.$techrow->sensitivity.')':'';
                    $devices[$techkey]['sensitivity'] = $techrow->sensitivity;
                    $devices[$techkey]['unit'] = $techrow->unit;
                    $devices[$techkey]['device_id'] = $techrow->device_id;
                    $devices[$techkey]['selected'] = $selected;
                }
                $techDevices[$devicekey]['devices'] = $devices;
            }

        }

        return Response::json([
            'status' => 1,
            'data' => $test_point,
            'environmental_conditions' => $environmental_conditions_values,
            'environmental_standards' => $techDevices,
            'history_dates'=>$temp_request_dates,
            'outside_calibration'=>0
        ], 200);
    }

    public function workorderdetail(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $technician = $this->technicianuser->getUserTechnician($user['id']);
        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();
        $input = [
            'id' => $reqInputs['id']
        ];
        $rules = array(

            'id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }
        $workorderid = $input['id'];
        $workorder = $this->technicianuser->getWorkorder($workorderid);
        if (!$workorder) {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }

        $instrumentsLists = $this->technicianuser->instrumentLists($workorderid);
        $instrumentsarr = array();
        if ($instrumentsLists) {
            foreach ($instrumentsLists as $inskey => $insrow) {
                $status = DB::table('tbl_workorder_status_move')
                    ->where('workorder_item_id','=',$insrow->work_order_item_id)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->select('workorder_status')->get()->first();
                if($status)
                {
                    if($status->workorder_status==1)
                    {
                        $status_flg = 'As found';
                    }
                    elseif($status->workorder_status==2)
                    {
                        $status_flg = 'Maintenance';
                    }
                    elseif($status->workorder_status==3)
                    {
                        $status_flg = 'Calibration';
                    }
                    elseif($status->workorder_status==4)
                    {
                        $status_flg = 'Despatch';
                    }
                    else

                    {
                        $status_flg = 'Completed';
                    }
                }
                else
                {
                    $status_flg = 'As found';
                }
                $instrumentsarr[$inskey]['id'] = $insrow->work_order_item_id;
                $instrumentsarr[$inskey]['asset_no'] = $insrow->asset_no;
                $instrumentsarr[$inskey]['serial_no'] = $insrow->serial_no;
                $instrumentsarr[$inskey]['model'] = $insrow->model_description;
                $instrumentsarr[$inskey]['model_id'] = $insrow->model_id;
                $instrumentsarr[$inskey]['status'] = $status_flg;

            }

            return Response::json([
                'status' => 1,
                'data' => $instrumentsarr
            ], 200);
        }


    }

    public function reports(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $technician = $this->technicianuser->getUserTechnician($user['id']);

        $this->uid = $user['id'];
        $this->tid = $technician->id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['status'] = ((isset($reqInputs['status'])) && !empty($reqInputs['status'])) ? implode(',',$reqInputs['status']) : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
       // print_r($fParams);die;
        $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date', 'W.as_found', 'W.as_calibrated', 'W.status', 'cc.location', 'cc.contact_name', 'cc.email_id', 'cc.phone_no', 'W.modified_date','W.report');
        $workorders = $this->workorder->assignedworkorders($fParams['limit'], $fParams['offset'], array('select' => $select, 'search' => $fParams['keyword'], 'tid' => $this->tid,'report_status'=>$fParams['status']),'DESC','W.id',false);
        $count_workorders = $this->workorder->assignedworkorders('', '', array('select' => $select, 'search' => $fParams['keyword'], 'tid' => $this->tid,'report_status'=>$fParams['status']),'DESC','W.id',true);

        $temp = array();
        if ($workorders) {
            foreach ($workorders as $key => $row) {
                // $temp[$key] = (array)$row;
                $temp[$key]['id'] = $row->id;
                $temp[$key]['workorder_number'] = $row->work_order_number;
                $temp[$key]['customer_name'] = $row->customer_name;
                $temp[$key]['plan_name'] = $row->service_plan_name;
                $temp[$key]['plan_id'] = $row->plan_id;
                $temp[$key]['job_assigned_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $row->workorder_date)));
                $temp[$key]['last_modified_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $row->modified_date)));
                $instruments = $this->workorder->totalInstruments($row->id);
                $temp[$key]['total_instruments'] = $instruments;
                $temp[$key]['customer_name'] = $row->customer_name;

                if ($row->status) {
                    switch ($row->status) {
                        case 1:
                            $status = 'As Found';
                            break;
                        case 2:
                            $status = 'Maintenance';
                            break;
                        case 3:
                            $status = 'Calibration';
                            break;
                        case 4:
                            $status = 'Despatched';
                            break;
                        default:
                            $status = 'Completed';

                    }
                } else {
                    $status = 'Maintenance';
                }
                $temp[$key]['status'] = $status;
                if(isset($row->report)&&$row->report)
                {

                    $documentroot = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/consolidate/technicianreview/' . $row->report;
                    if(file_exists($documentroot))
                    {
                        $filepath = 'public/report/consolidate/technicianreview/';
                        $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filepath . $row->report;
                    }
                    else
                    {
                        $documentPath = '';
                    }
                }
                else
                {
                    $documentPath = '';
                }

                $temp[$key]['consolidate_report'] = $documentPath;
            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count'=> $count_workorders
        ], 200);
    }

    //View Summary:
    public function viewSummary(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
//        if (count($user) < 0) {
//            return Response::json([
//                'status' => 0,
//                'message' => 'User not found'
//            ], 422);
//        }
        $reqInputs = $request->json()->all();
        $input = [
            'workorder_id' => $reqInputs['work_order_id']
        ];
        $workorderid = $input['workorder_id'];
        $workorder = $this->technicianuser->getWorkorder($workorderid);
        if (!$workorder) {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }
        if (isset($workorder->view_summary) && $workorder->view_summary) {

            $documentroot = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/technician/viewsummary/' . $workorder->view_summary;
            if (file_exists($documentroot)) {
                $filepath = 'public/technician/viewsummary/';
                $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filepath . $workorder->view_summary;
            } else {
                $details = $this->technicianuser->getWorkorderDetails($workorderid);
                $customer = DB::table('tbl_customer')->where('tbl_customer.id', $details->customer_id)->first();
                $prefContact = DB::table('tbl_customer_contacts')->where('tbl_customer_contacts.customer_id', $details->customer_id)->orderby('tbl_customer_contacts.id','ASC')->first();
                $prefContactName = (isset($prefContact->contact_name)&&$prefContact->contact_name)?$prefContact->contact_name:'';
                //            $getworkorderitem = $this->technicianuser->getWorkorderItemDetails($workorderid);
                $select = array( 'E.asset_no', 'E.serial_no', 'E.pref_contact','S.request_no','S.service_schedule_date',
                    'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'WOI.request_item_id as servicerequestitemid',
                    'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
                    'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid','SP.service_plan_name'
                );
                $orderItems = $this->payment->workorderItem($workorderid, array('select' => $select));

                $billing = $this->technicianuser->getBilling($details->billing_address_id);
                $shipping = $this->technicianuser->getShipping($details->shipping_address_id);

                $temp = array();
                if ($orderItems) {
                    $i = 1;
                    $orderItemAmount = 0;
                    foreach ($orderItems as $orderkey => $orderval) {
//                    $orderItemAmount += $orderval->order_item_amt;
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
//                    $temp[$orderkey]['order_item_amt'] = $orderval->order_item_amt;
                        $servicerequest = $orderval->request_no;
//                    $temp[$orderkey]['request_no'] = $orderval->request_no;
                        $temp[$orderkey]['asset_no'] = $orderval->asset_no;
                        $temp[$orderkey]['serial_no'] = $orderval->serial_no;
                        $temp[$orderkey]['pref_contact'] = $orderval->pref_contact;
                        $temp[$orderkey]['service_plan'] = $orderval->service_plan_name;
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

                    $serviceType = 'In-house';

                    if(isset($details->on_site)&&$details->on_site)
                    {
                        if($details->on_site==2)
                        {
                            $serviceType = 'In-house';
                        }
                        else
                        {
                            $serviceType = 'On-site';
                        }
                    }

                    $data['orderItems'] = $temp;
                    $data['billing'] = $billing;
                    $data['shipping'] = $shipping;
                    $data['workorder'] = $details->work_order_number;
                    $data['ServicerequestNO'] = $servicerequest;
                    $data['serviceType'] = $serviceType;
                    $data['ServiceRequestDate'] = date('m-d-Y',strtotime(str_replace('/','-',$orderval->service_schedule_date)));
                }


                $data['prefContactName'] = $prefContactName;
                $data['customer'] = $customer;
                $path = base_path() . '/public/technician/viewsummary';
                $SummaryFile = 'viewsummary-' . uniqid();

                $summary_file = $SummaryFile.'.pdf';
                $savePDF = DB::table('tbl_work_order')->where('id',$workorderid)->update([
                    'view_summary' =>$summary_file,
                ]);
                $documentPath = url('/public/technician/viewsummary/' . $SummaryFile . '.pdf');
                view()->share($data);
                $pdf = PDF::loadView('viewSummary')
                    ->save($path . '/' . $SummaryFile . '.pdf', 'F');
            }
            $summary_path = $documentPath;
        } else {
            $details = $this->technicianuser->getWorkorderDetails($workorderid);
            $customer = DB::table('tbl_customer')->where('tbl_customer.id', $details->customer_id)->first();
            $prefContact = DB::table('tbl_customer_contacts')->where('tbl_customer_contacts.customer_id', $details->customer_id)->orderby('tbl_customer_contacts.id','ASC')->first();
            $prefContactName = (isset($prefContact->contact_name)&&$prefContact->contact_name)?$prefContact->contact_name:'';
//            $getworkorderitem = $this->technicianuser->getWorkorderItemDetails($workorderid);
            $select = array( 'E.asset_no', 'E.serial_no', 'E.pref_contact','S.request_no','S.service_schedule_date',
                'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'WOI.request_item_id as servicerequestitemid',
                'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
                'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid'
            );
            $orderItems = $this->payment->workorderItem($workorderid, array('select' => $select));

            $billing = $this->technicianuser->getBilling($details->billing_address_id);
            $shipping = $this->technicianuser->getShipping($details->shipping_address_id);

            $temp = array();
            if ($orderItems) {
                $i = 1;
                $orderItemAmount = 0;
                foreach ($orderItems as $orderkey => $orderval) {
//                    $orderItemAmount += $orderval->order_item_amt;
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
//                    $temp[$orderkey]['order_item_amt'] = $orderval->order_item_amt;
                    $servicerequest = $orderval->request_no;
//                    $temp[$orderkey]['request_no'] = $orderval->request_no;
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
                $serviceType = 'In-house';

                if(isset($details->on_site)&&$details->on_site)
                {
                       if($details->on_site==2)
                       {
                           $serviceType = 'In-house';
                       }
                       else
                       {
                           $serviceType = 'On-site';
                       }
                }

                $data['orderItems'] = $temp;
                $data['billing'] = $billing;
                $data['shipping'] = $shipping;
                $data['workorder'] = $details->work_order_number;
                $data['ServicerequestNO'] = $servicerequest;
                $data['serviceType'] = $serviceType;
                $data['ServiceRequestDate'] = date('m-d-Y',strtotime(str_replace('/','-',$orderval->service_schedule_date)));
                $data['prefContactName'] = $prefContactName;
            }



            $path = base_path() . '/public/technician/viewsummary';
            $SummaryFile = 'viewsummary-' . uniqid();

            $summary_file = $SummaryFile.'.pdf';
            $savePDF = DB::table('tbl_work_order')->where('id',$workorderid)->update([
               'view_summary' =>$summary_file,
            ]);
            $summary_path = url('/public/technician/viewsummary/' . $SummaryFile . '.pdf');
            view()->share($data);
            $pdf = PDF::loadView('viewSummary')
                ->save($path . '/' . $SummaryFile . '.pdf', 'F');
        }
            return Response::json([
                'status' => 1,
//                'data'   =>$data,
                'summary_pdf_url' => $summary_path
            ], 200);

    }
}