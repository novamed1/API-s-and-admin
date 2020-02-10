<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\User;
use JWTAuthException;
use DB;
use Response;
use Illuminate\Support\Facades\Hash;
use App\Equipment;
use App\Servicerequest;
use Validator;
use Carbon\Carbon;
use App\Models\Workorder;
use App\Workorderprocessupdate;
use PDF;
use App\Technicianuser;
use App\Workorderitemmovemodel;
use App\Models\Payment;

class ServicerequestController extends Controller
{
    private $user;
    public $uid;
    public $cid;
    public $roleid;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->equipment = new Equipment();
        $this->userModel = new User();
        $this->serviceModel = new Servicerequest();
        $this->workorder = new Workorder();
        $this->workorderProcess = new Workorderprocessupdate();
        $this->technicianuser = new Technicianuser();
        $this->workorderitemmove = new Workorderitemmovemodel();
        $this->payment = new Payment();

    }

    public function pricingCriteria(Request $request)
    {

        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }


        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        $requiredFields = [
            'equipment_id' => isset($reqInputs['equipment_id']) ? $reqInputs['equipment_id'] : '',
            'plan_id' => isset($reqInputs['plan_id']) ? $reqInputs['plan_id'] : ''

        ];

        $rules = [
            'equipment_id' => 'required',
            'plan_id' => 'required'
        ];
        $checkValid = Validator::make($requiredFields, $rules);

        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 422);
        }
        $equipmentModel = $this->serviceModel->equipmentModel($reqInputs['equipment_id']);
        $temp = array();
        if ($equipmentModel) {
            $pricing = $this->serviceModel->pricing($equipmentModel, $reqInputs['plan_id']);
            if ($pricing) {
                foreach ($pricing as $key => $row) {
                    $temp[$key]['service_price_id'] = $row->id;
                    $temp[$key]['price'] = $row->price;
                    $temp[$key]['channel_point_name'] = $row->channel_number . '/' . $row->point_channel;
                }
            }
        }
        return Response::json([
            'status' => 1,
            'data' => $temp
        ], 200);
    }


    public function createservicerequest(Request $request)
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
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        //echo '<pre>';print_r($reqInputs);die;
        // print_r($reqInputs);die;
        $input = [
            'shipDate' => $reqInputs['shipDate']
        ];
        $rules = array(

            'shipDate' => 'required'
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
        $query = DB::table('tbl_service_request');
        $query->orderby('id', 'DESC');
        $last_id = $query->get()->first();
        if ($last_id) {
            $request_num = 120000 + $last_id->id;
        } else {
            $request_num = 120000;
        }

        if (isset($reqInputs['requestItems']) && $reqInputs['requestItems']) {
            $bQuery = DB::table('tbl_customer_billing_address')->where('customer_id', $this->cid)->first();
            $sQuery = DB::table('tbl_customer_shipping_address')->where('customer_id', $this->cid)->first();
            $saveReq['id'] = false;
            $saveReq['request_no'] = $request_num;
            $saveReq['customer_id'] = $this->cid;
            $saveReq['service_schedule_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['shipDate'])));
            $saveReq['service_customer_status'] = 1;
            $saveReq['is_accessed'] = 2;
            $saveReq['billing_address_id'] = (isset($reqInputs['billing_address_id']) && $reqInputs['billing_address_id']) ? $reqInputs['billing_address_id'] : $bQuery->id;
            $saveReq['shipping_address_id'] = (isset($reqInputs['shipping_address_id']) && $reqInputs['shipping_address_id']) ? $reqInputs['shipping_address_id'] : $sQuery->id;
            $saveReq['created_by'] = $this->uid;
            $saveReq['on_site'] = (isset($reqInputs['on_or_off_site']) && $reqInputs['on_or_off_site']) ? $reqInputs['on_or_off_site'] : 2;
            // echo '<pre>'; print_r($saveReq); '</pre>'; die;


            $reqId = $this->serviceModel->saveRequest($saveReq);
            //  $reqId = 1;
            $saveStatus['id'] = false;
           // $saveStatus['request_id'] = $reqId;
            $saveStatus['notes'] = 1;
            $saveStatus['service_date'] = Carbon::now()->toDateTimeString();

            $statusId = $this->serviceModel->saveStatus($saveStatus);

            foreach ($reqInputs['requestItems'] as $row) {
                $end_month = date('t', strtotime('m Y'));
                if ((isset($row['frequencyDate'])) && $row['frequencyDate']) {
                    $query = DB::table('tbl_frequency');
                    $query->select('no_of_days', 'id');
                    $resultfre = $query->first();
                    $next_due_date = date('Y-m-d', strtotime(str_replace('/', '-', $row['frequencyDate'])));
                    $saveEqu['pickup_date'] = 1;
                    $saveEqu['next_due_date'] = $next_due_date;
                    $saveEqu['frequency_id'] = null;


                } elseif ($row['frequency']) {
                    $query = DB::table('tbl_frequency');
                    $query->where('name', $row['frequency']);
                    $query->select('no_of_days', 'id');
                    $resultfre = $query->first();
                    $next_due_date = date('Y-m-t', strtotime("+" . $resultfre->no_of_days . " months", strtotime(date("Y-m-d"))));
                    $saveEqu['pickup_date'] = 0;
                    $saveEqu['frequency_id'] = $resultfre->id;
                } else {
                    $next_due_date = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                    $saveEqu['pickup_date'] = 0;
                    $saveEqu['frequency_id'] = 1;
                }

                //print_r($saveEqu);die;
                $query = DB::table('tbl_due_equipments');
                $query->select('equipment_id');
                $query->where('id', $row['dueEquipmentId']);
                $result = $query->first();
                $equipment_id = (isset($result->equipment_id) && $result->equipment_id) ? $result->equipment_id : '';
                $query1 = DB::table('tbl_instrument_plan_log');
                $query1->select('id');
                $query1->where('equipment_id', $equipment_id);
                $query1->orderBy('id', 'DESC');
                $result1 = $query1->first();
                $log_id = (isset($result1->id) && $result1->id) ? $result1->id : '';

                $saveitem['id'] = false;
                $saveitem['service_request_id'] = $reqId;
                $saveitem['due_equipments_id'] = $row['dueEquipmentId'];
                $saveitem['equipment_log_id'] = $log_id;
                $saveitem['test_plan_id'] = $row['servicePlanId'];
                $saveitem['frequency_id'] = $saveEqu['frequency_id'];
                $saveitem['comments'] = isset($row['comments']) ? $row['comments'] : '';
                $saveitem['service_price_id'] = $row['servicePriceId'];
                $saveitem['created_date'] = date('Y-m-' . $end_month);
                $this->serviceModel->saveItems($saveitem);
                $saveEqu['id'] = $row['dueEquipmentId'];
                //$saveEqu['last_cal_date'] = date('Y-m-' . $end_month);
                //$saveEqu['next_due_date'] = $next_due_date;
                $saveEqu['calibrate_process'] = 1;
                $this->serviceModel->saveDueEqu($saveEqu);
                $savelog['id'] = false;
                $savelog['request_id'] = $reqId;
                $savelog['due_equipment_id'] = $row['dueEquipmentId'];
                $savelog['request_date'] = date('Y-m-d');
                $savelog['call_date'] = date('Y-m-' . $end_month);
                //$savelog['next_due_date'] = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                $this->serviceModel->saveReqLog($savelog);

            }
            return Response::json([
                'status' => 1,
                'message' => "Service request created"
            ], 200);

        } else {
            return Response::json([
                'status' => 0,
                'message' => 'Due equipment items are missing'
            ], 400);
        }


    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function serviceRequestDashboardCounts(Request $request)
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
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        //print_r($this->roleid);die;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $requests = $this->serviceModel->allServiceRequests($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid));
        $totalRequests = $this->serviceModel->serviceStatusCounts(array('cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid));
        $temp = array();
        $counts = array();
        $pendingRequests = $this->serviceModel->serviceWorkorderCounts(array('cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid,'status'=>1));
        $openRequests = $this->serviceModel->serviceWorkorderCounts(array('cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid,'status'=>2));
        $closedRequests = $this->serviceModel->serviceWorkorderCounts(array('cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid,'status'=>3));
        $counts['totalRequests'] = $totalRequests;
        $counts['totalPending'] = $pendingRequests?count($pendingRequests):0;
        $counts['totalOpen'] = $openRequests?count($openRequests):0;
        $counts['totalCalibrated'] = $closedRequests?count($closedRequests):0;
        return Response::json([
            'status' => 1,
            'data' => $counts

        ], 200);


    }


    public function serviceRequestLists(Request $request)
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
        $customer = $this->userModel->getUserCustomer($user['id']);
        if (!$customer) {
            return Response::json([
                'status' => 0,
                'message' => 'Not a valid customer'
            ], 401);
        }
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        //print_r($this->roleid);die;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $requests = $this->serviceModel->allServiceRequests($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid));
        $totalRequests = $this->serviceModel->serviceStatusCounts(array('cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid));
        $temp = array();
        $counts = array();
        $counts['totalRequests'] = $totalRequests;
        $counts['totalPending'] = $totalRequests;
        $counts['totalOpen'] = $totalRequests;
        $counts['totalCalibrated'] = 0;
        if ($requests) {
            foreach ($requests as $key => $row) {
                $serviceRequestedVia = '';
                $temp[$key] = (array)$row;
                $totalItems = $this->serviceModel->totalItems(array('request_id' => $row->id));
                //$calibrated_instruments = $this->serviceModel->totalItemsCalibrated(array('request_id' => $row->id));
                $calibrated_instruments=DB::table('tbl_work_order_items as oi')->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
                ->join('tbl_service_request as sr','sr.id','=','ri.service_request_id')->where('sr.id',$row->id)->where('oi.as_calibrated_status','=','completed')->count();
                $temp[$key]['totalInstruments'] = $totalItems;
                $temp[$key]['calibratedInstruments'] = $calibrated_instruments;
                $temp[$key]['pendingInstruments'] = $totalItems - $calibrated_instruments;
                $temp[$key]['request_by'] = $row->name;
                if($row->is_accessed==1)
                {
                    $serviceRequestedVia = 'Website';
                }
                elseif($row->is_accessed==2)
                {
                    $serviceRequestedVia = 'Portal';
                }
                else
                {
                    $serviceRequestedVia = 'Website';
                }
                $temp[$key]['serviceRequestedVia'] = $serviceRequestedVia;
                if (($totalItems - $calibrated_instruments) == 0) {
                    $temp[$key]['status'] = 'calibrated';
                } else {
                    $temp[$key]['status'] = 'pending';
                }

                $work_order = DB::table('tbl_work_order')->where('request_id','=',$row->id)->select('customer_review','plan_id','calibration_outside')
                              ->join('tbl_service_plan as sp','sp.id','=','tbl_work_order.plan_id','left')->get()->first();
                if((isset($work_order->customer_review)&&$work_order->customer_review))
                {
                    $temp[$key]['review_status'] = true;
                }
                else
                {
                    $temp[$key]['review_status'] = false;
                }
                $temp[$key]['consolidate_report'] = (isset($work_order->calibration_outside) && $work_order->calibration_outside==1) ? false : true;

                $subtemp = array();
                $getEquipments = $this->serviceModel->serviceItemEquipments(0, 0, array('request_id' => $row->request_no));
//                if($row->id==12)
//                {
//                    echo '<pre>';print_r($getEquipments);die;
//                }
                //echo '<pre>';print_r($getEquipments);die;
                if ($getEquipments) {
                    foreach ($getEquipments as $keySub => $rowSub) {
                        $spare_amount = 0;
                        $spares = DB::table('tbl_workorder_maintenance_log as log')->
                        join('tbl_workorder_status_move as sm', 'sm.id', '=', 'log.workorder_status_id')->
                        join('tbl_work_order_items as oi', 'oi.id', '=', 'sm.workorder_item_id')->
                        select('log.workorder_spares')->
                        where('oi.request_item_id', $rowSub->request_item_id)->get()->first();

                        if ($spares) {
                            $spares_used = json_decode($spares->workorder_spares);
                            if ($spares_used) {
                                foreach ($spares_used as $sparekey => $sparerow) {
                                    $spare_amount += $sparerow->amount;
                                }

                            }
                        }
                        $workorderitem = DB::table('tbl_work_order_items as oi')->
                        select('oi.as_calibrated_status','oi.admin_review')->
                        where('oi.request_item_id', $rowSub->request_item_id)->first(); //print_r($workorderitem->as_calibrated_status);
//                        $calibrationStatus = (isset($workorderitem->as_calibrated_status))?$workorderitem->as_calibrated_status:'';
                        $calibrationStatus = (isset($workorderitem->as_calibrated_status)&&$workorderitem->as_calibrated_status=='completed')?1:0;

                       if($calibrationStatus)
                       {
                           $is_calibrated = 1;
                       }
                       else
                       {
                           $is_calibrated = 0;
                       }
                        $total_amount = ($rowSub->price) + $spare_amount;

                        $subtemp[$keySub]['request_item_id'] = $rowSub->request_item_id;
                        $subtemp[$keySub]['asset_no'] = $rowSub->asset_no;
                        $subtemp[$keySub]['serial_no'] = $rowSub->serial_no;
                        $subtemp[$keySub]['pref_contact'] = $rowSub->pref_contact;
                        $subtemp[$keySub]['pref_email'] = $rowSub->pref_email;
                        $subtemp[$keySub]['pref_tel'] = $rowSub->pref_tel;
                        $subtemp[$keySub]['location'] = $rowSub->location;
                        $subtemp[$keySub]['service_plan_name'] = $rowSub->service_plan_name;
                        $subtemp[$keySub]['model_name'] = $rowSub->model_description;
                        $subtemp[$keySub]['price'] = $total_amount;
                        $subtemp[$keySub]['comments'] = $rowSub->comments;
                        $subtemp[$keySub]['frequency_name'] = $rowSub->frequency_name;
                        $subtemp[$keySub]['point_name'] = $rowSub->point_name;
                        $subtemp[$keySub]['point_channel'] = $rowSub->point_channel;
                        $subtemp[$keySub]['next_due_date'] = $rowSub->next_due_date;
                        $subtemp[$keySub]['last_cal_date'] = $rowSub->last_cal_date;
                        $subtemp[$keySub]['is_calibrated'] = $is_calibrated;
                        $subtemp[$keySub]['order_status'] = $rowSub->order_status;
                        $subtemp[$keySub]['payment_status'] = $rowSub->payment_status;
                        //$subtemp[$keySub]['workorder_item_id'] = (isset($workorderitem->id)&&$workorderitem->id)?$workorderitem->id:0;

                        $work_order_item = DB::table('tbl_work_order_items')->where('request_item_id','=',$rowSub->request_item_id)->select('customer_review')->get()->first();
                        if((isset($work_order_item->customer_review)&&$work_order_item->customer_review))
                        {
                            $subtemp[$keySub]['review_status'] = true;
                        }
                        else
                        {
                            $subtemp[$keySub]['review_status'] = false;
                        }

                    }
                }
                $temp[$key]['requestItems'] = $subtemp;

            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'counts' => $counts

        ], 200);


    }

    public function serviceRequestCounts(Request $request)
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
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        //print_r($this->roleid);die;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $requests = $this->serviceModel->allServiceRequests($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'cus_id' => $this->cid, 'role_id' => $this->roleid, 'user_id' => $this->uid));


        return Response::json([
            'status' => 1,
            'data' => count($requests)

        ], 200);


    }

    public function serviceRequestSummary(Request $request)
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

        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        $input = [
            'service_request_id' => $reqInputs['service_request_id']
        ];
        $rules = array(

            'service_request_id' => 'required'
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
        $fParams = array();
        $fParams['request_id'] = isset($reqInputs['service_request_id']) ? $reqInputs['service_request_id'] : '';
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['item_status'] = (isset($reqInputs['status']) && $reqInputs['status']) ? explode(',',$reqInputs['status']) : array();
        $fParams['cal_frequency'] = (isset($reqInputs['cal_frequency']) && $reqInputs['cal_frequency']) ? explode(',',$reqInputs['cal_frequency']) : array();
        $requestSummary = $this->serviceModel->serviceItemEquipments($fParams['limit'], $fParams['offset'], array('request_id' => $fParams['request_id'], 'search' => $fParams['keyword'], 'cus_id' => $this->cid,'cal_frequency'=>$fParams['cal_frequency'],'item_status'=>$fParams['item_status']));
        //echo '<pre>';print_r($requestSummary);die;
        $temp = array();
        $shippingCharge = 0;
        $discountCharge= 0;
        $request = $this->payment->getRequest($fParams['request_id']);
        if ($requestSummary) {
            foreach ($requestSummary as $keySub => $rowSub) {
                $spare_amount = 0;
                $spares = DB::table('tbl_workorder_maintenance_log as log')->
                join('tbl_workorder_status_move as sm', 'sm.id', '=', 'log.workorder_status_id')->
                join('tbl_work_order_items as oi', 'oi.id', '=', 'sm.workorder_item_id')->
                select('log.workorder_spares')->
                where('oi.request_item_id', $rowSub->request_item_id)->get()->first();

                if ($spares) {
                    $spares_used = json_decode($spares->workorder_spares);
                    if ($spares_used) {
                        foreach ($spares_used as $sparekey => $sparerow) {
                            $spare_amount += $sparerow->amount;
                        }

                    }
                }
                $total_amount = ($rowSub->price) + $spare_amount;

                $workorderitem = DB::table('tbl_work_order_items as oi')->
                select('oi.as_calibrated_status','oi.report','oi.work_order_id','oi.admin_review')->
                where('oi.request_item_id', $rowSub->request_item_id)->get()->first();
                $calibrationStatus = (isset($workorderitem->admin_review))?1:0;
                if($workorderitem)
                {
                    $getWorkorder = DB::table('tbl_work_order')->
                                    where('id', $workorderitem->work_order_id)->get()->first();
                    if($getWorkorder)
                    {
                        $getPlan = DB::table('tbl_service_plan')->
                        where('id', $getWorkorder->plan_id)->get()->first();
                        if($getPlan)
                        {

                            if($getPlan->calibration_outside==1)
                            {
                                $calibrationStatus = 1;
                            }
                            else
                            {

                                    $calibrationStatus = (isset($workorderitem->as_calibrated_status)&&$workorderitem->as_calibrated_status=='completed')?1:0;
                            }
                        }
                    }


                }



                if($calibrationStatus==1)
                {
                    $is_calibrated = 1;
                    $download_link = url('/public/report/adminreview/' . $workorderitem->report);
                }
                else
                {
                    $is_calibrated = 0;
                    $download_link = '';
                }

                $temp[$keySub]['request_item_id'] = $rowSub->request_item_id;
                $temp[$keySub]['asset_no'] = $rowSub->asset_no;
                $temp[$keySub]['serial_no'] = $rowSub->serial_no;
                $temp[$keySub]['pref_contact'] = $rowSub->pref_contact;
                $temp[$keySub]['pref_email'] = $rowSub->pref_email;
                $temp[$keySub]['pref_tel'] = $rowSub->pref_tel;
                $temp[$keySub]['location'] = $rowSub->location;
                $temp[$keySub]['service_plan_name'] = $rowSub->service_plan_name;
                $temp[$keySub]['model_name'] = $rowSub->model_description;
                $temp[$keySub]['price'] = $total_amount;
                $temp[$keySub]['comments'] = $rowSub->comments;
                $temp[$keySub]['frequency_name'] = $rowSub->frequency_name?$rowSub->frequency_name:'Others';
                $temp[$keySub]['point_name'] = $rowSub->point_name;
                $temp[$keySub]['point_channel'] = $rowSub->point_channel;
                $temp[$keySub]['next_due_date'] = $rowSub->next_due_date;
                $temp[$keySub]['last_cal_date'] = $rowSub->last_cal_date;
                $temp[$keySub]['is_calibrated'] = $is_calibrated;
                $temp[$keySub]['order_status'] = $rowSub->order_status;
                $temp[$keySub]['payment_status'] = $rowSub->payment_status;
                $temp[$keySub]['download_link'] = $download_link;
                $temp[$keySub]['channel_point'] = $rowSub->point_channel.'/'.$rowSub->channel_number;
                $calibrated = $is_calibrated == 0 ? 'Calibration Pending' : 'Calibrated';
                $temp[$keySub]['calibrated_status'] = $calibrated;

            }
            if($customer)
            {
                $shipping_id = (isset($customer->shipping)&&$customer->shipping)?$customer->shipping:'';
                if($shipping_id)
                {
                   $shipping_detail = DB::table('tbl_shipping as S')
                                       ->where('S.id',$shipping_id)
                                       ->select('S.*')
                                       ->first();
                   if($request->on_site==2)
                   {
                       $shippingCharge = (isset($shipping_detail->shipping_charge)&&$shipping_detail->shipping_charge)?$shipping_detail->shipping_charge:'';
                   }
                   else
                   {
                       $shippingCharge = 0;
                   }

                }

                $serviceRequest = DB::table('tbl_service_request')
                                ->where('request_no',$fParams['request_id'])
                                ->first();
                $discountCharge = (isset($serviceRequest->discount)&&$serviceRequest->discount)?$serviceRequest->discount:0;


            }

        }
        return Response::json([
            'status' => 1,
            'shipping_charge'=>$shippingCharge,
            'discount_charge'=>$discountCharge,
            'data' => $temp

        ], 200);


    }

    function viewhistory(Request $request)
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

        $reqInputs = $request->json()->all(); //echo'<pre>';print_r($reqInputs);'</pre>';die;
        $input = [
            'date' => $reqInputs['date'],
            'equipment_id' => $reqInputs['equipment_id']
        ];
        $rules = array(
            'date' => 'required',
            'equipment_id' => 'required'
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

        $equipment_id = $input['equipment_id'];
        $date = date('Y-m-d', strtotime(str_replace('/', '-', $input['date'])));
        $as_found = $this->serviceModel->as_found_history($equipment_id, $date);
        $temp_as_found = array();
        //  print_r($as_found);die;
        if ($as_found) {
            foreach ($as_found as $fkey => $frow) {
                if ($frow->test_point_id == 1) {
                    $test_point = 'Test point 1';
                } elseif ($frow->test_point_id == 2) {
                    $test_point = 'Test point 2';
                } else {
                    $test_point = 'Test point 3';
                }
                if ($frow->reading_document) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . 'public/technician/as_found/' . $frow->reading_document;
                    if (file_exists($file_path)) {
                        $filepath = 'public/technician/as_found/';
                        $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filepath . $frow->reading_document;
                    } else {
                        $documentPath = '';
                    }
                } else {
                    $documentPath = '';
                }
                //$temp_as_found[$fkey] = (array)$frow;
                $temp_as_found[$fkey]['test_point_name'] = $test_point;
                $temp_as_found[$fkey]['mean'] = $frow->reading_mean;
                $temp_as_found[$fkey]['mean_volume'] = $frow->reading_mean_volume;
                $temp_as_found[$fkey]['SD'] = $frow->reading_sd;
                $temp_as_found[$fkey]['accuracy'] = $frow->reading_accuracy;
                $temp_as_found[$fkey]['precision'] = $frow->reading_precision;
                $temp_as_found[$fkey]['status'] = $frow->reading_status == 1 ? 'Pass' : 'Fail';
                $temp_as_found[$fkey]['file'] = $documentPath;
                $temp_as_found[$fkey]['file_name'] = $frow->reading_document;

            }

        }

        $as_maintenance = $this->serviceModel->as_maintenance_history($equipment_id, $date);
        $checklist = $this->workorder->checklist(2);
        $temp_as_maintenance = array();

        if ($as_maintenance) {
            foreach ($as_maintenance as $mkey => $mrow) {
                $processChecklist = explode(',', str_replace('~', '', $mrow->workorder_checklists));
                $spares = json_decode($mrow->workorder_spares);
                $total = 0;
                $addedSpares = array();
                if ($spares) {
                    foreach ($spares as $skey => $srow) {
                        $getspare = $this->workorderProcess->getSpare($srow->id);
                        $addedSpares[$skey] = (array)$srow;
                        $addedSpares[$skey]['name'] = $getspare->sku_number;
                        $addedSpares[$skey]['number'] = $getspare->part_name;
                        $total += $srow->amount;
                    }

                }
                $temp_as_maintenance[$mkey]['spares'] = $addedSpares;
                $temp_as_maintenance[$mkey]['spareTotalAmount'] = $total;

                if ($checklist) {
                    $temp = array();
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
                            } else {
                                $tempitem[$ikey]['id'] = $irow->id;
                                $tempitem[$ikey]['title'] = $irow->title;
                                $tempitem[$ikey]['type'] = $irow->type;
                                $tempitem[$ikey]['selected'] = $selected;
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
                                } else {
                                    $tempitemsub[$iskey]['id'] = $isrow->id;
                                    $tempitemsub[$iskey]['title'] = $isrow->title;
                                    $tempitemsub[$iskey]['type'] = $isrow->type;
                                    $tempitemsub[$iskey]['selected'] = $selectedsub;
                                }


                            }
                            $tempitem[$ikey]['sub_checklist'] = $tempitemsub;
                        }
                        $temp[$key]['checklists'] = $tempitem;
                    }
                    $temp_as_maintenance[$mkey]['checklist'] = $temp;

                }
            } //print_r($temp_as_maintenance);die;
        }


        $as_calibrated = $this->serviceModel->as_calibrated_history($equipment_id, $date);

        $temp_as_calibrated = array();

        if ($as_calibrated) {
            foreach ($as_calibrated as $ckey => $crow) {
                if ($crow->test_point_id == 1) {
                    $test_point = 'Test point 1';
                } elseif ($crow->test_point_id == 2) {
                    $test_point = 'Test point 2';
                } else {
                    $test_point = 'Test point 3';
                }
                if ($crow->reading_document) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . 'public/technician/as_calibrated/' . $crow->reading_document;
                    if (file_exists($file_path)) {
                        $filepath = 'public/technician/as_found/';
                        $documentPath = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filepath . $crow->reading_document;
                    } else {
                        $documentPath = '';
                    }
                } else {
                    $documentPath = '';
                }
                //$temp_as_found[$fkey] = (array)$frow;
                $temp_as_calibrated[$ckey]['test_point_name'] = $test_point;
                $temp_as_calibrated[$ckey]['mean'] = $crow->reading_mean;
                $temp_as_calibrated[$ckey]['mean_volume'] = $crow->reading_mean_volume;
                $temp_as_calibrated[$ckey]['SD'] = $crow->reading_sd;
                $temp_as_calibrated[$ckey]['accuracy'] = $crow->reading_accuracy;
                $temp_as_calibrated[$ckey]['precision'] = $crow->reading_precision;
                $temp_as_calibrated[$ckey]['status'] = $crow->reading_status == 1 ? 'Pass' : 'Fail';
                $temp_as_calibrated[$ckey]['file'] = $documentPath;
                $temp_as_calibrated[$ckey]['file_name'] = $crow->reading_document;

            }

        } // echo '<pre>';print_r($temp_as_calibrated);die;

        $service_request_item = $this->serviceModel->historyRequestItem($equipment_id, $date);
        $data['plan_name'] = (isset($service_request_item->service_plan_name) && $service_request_item->service_plan_name) ? $service_request_item->service_plan_name : '';
        $data['plan_description'] = (isset($service_request_item->plan_description) && $service_request_item->plan_description) ? $service_request_item->plan_description : '';
        //$temp_as_calibrated = array();

        return Response::json([
            'status' => 1,
            'plan_details' => $data,
            'as_found' => $temp_as_found,
            'as_maintenance' => $temp_as_maintenance,
            'as_calibrated' => $temp_as_calibrated
        ], 200);

    }

//    function viewpdf(Request $request)
//    {
//        header('Access-Control-Allow-Origin: *');
//        $token = app('request')->header('token');
//        $user = JWTAuth::toUser($token);
//        if (count($user) < 0) {
//            return Response::json([
//                'status' => 0,
//                'message' => 'User not found'
//            ], 422);
//        }
//
//        $reqInputs = $request->json()->all();
//        $input = [
//            'id' => $reqInputs['id']
//        ];
//        $rules = array(
//
//            'id' => 'required'
//        );
//        $checkValid = Validator::make($input, $rules);
//        if ($checkValid->fails()) {
//            //$this->status = 0;
//            // $this->message = $checkValid->errors()->all();
//            return Response::json([
//                'status' => 0,
//                'message' => $checkValid->errors()->all()
//            ], 400);
//        }
//
//        $service_request_item_id = $input['id'];
//        $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
//            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
//            ->join('tbl_service_request as sr','sr.id','=','ri.service_request_id')
//            ->join('tbl_work_order as wo','wo.request_id','=','sr.id')
//            ->join('tbl_technician as t','t.id','=','wo.calibration_to')
//            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
//            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
//            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
//            ->join('tbl_customer as c','c.id','=','e.customer_id')
//            ->select('e.*','c.*','em.model_name','de.last_cal_date','de.next_due_date','t.first_name as tfname','t.last_name as tlname','oi.id','oi.report')->where('ri.id',$service_request_item_id)->get()->first();
//
//        if($data['equipment_details'])
//        {
//            if($data['equipment_details']->report)
//            {
//
//                return Response::json([
//                    'status' => 1,
//                    'message' => 'Success',
//                    'pdf_url' => url('/public/report/' . $data['equipment_details']->report)
//                ], 200);
//            }
//        }
//
//        $data['as_found_workorder'] = $this->serviceModel->workorder_status($data['equipment_details']->id,1);
//        $as_found_workorder_log = $this->serviceModel->workorder_asfound_log($data['equipment_details']->id);
//        $tempfound = array();
//        if($as_found_workorder_log)
//        {
//            foreach($as_found_workorder_log as $fkey=>$frow)
//            {
//
//                $tempfound[$fkey]['channel'] = $frow->reading_channel;
//                $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
//                $tempfound[$fkey]['mean'] = $frow->reading_mean;
//                $tempfound[$fkey]['sd'] = $frow->reading_sd;
//                $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
//                $tempfound[$fkey]['target_accuracy'] = '';
//                $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
//                $tempfound[$fkey]['target_precision'] = '';
//                $tempfound[$fkey]['status'] = $frow->reading_status==1?'Passed':'Failed';
//                $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings);
//
//            }
//        }
//        $data['calibrated_workorder'] = $this->serviceModel->workorder_status($data['equipment_details']->id,3);
//        $calibrated_workorder_log = $this->serviceModel->workorder_calibrated_log($data['equipment_details']->id);
//        $tempcalibrated = array();
//        if($calibrated_workorder_log)
//        {
//            foreach($calibrated_workorder_log as $ckey=>$crow)
//            {
//
//                $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
//                $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
//                $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
//                $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
//                $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
//                $tempcalibrated[$ckey]['target_accuracy'] = '';
//                $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
//                $tempcalibrated[$ckey]['target_precision'] = '';
//                $tempcalibrated[$ckey]['status'] = $crow->reading_status==1?'Passed':'Failed';
//                $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);
//
//            }
//        }
//        $data['found_log'] = $tempfound;
//        $data['calibrated_log'] = $tempcalibrated;
//        $data['comments'] = '';
//        //print_r($data);die;
//        $path = base_path() . '/public/report';
//        $reportFile = 'report-' . uniqid();
//
//        view()->share($data);
//        $pdf = PDF::loadView('report.report')
//            ->save($path . '/' . $reportFile . '.pdf', 'F');
//        $pathToFile = base_path() . '/public/report/' . $reportFile . '.pdf';
//
//        $headers = array(
//            'Content-Type: application/pdf',
//        );
//
//        $save_report['id'] = $data['equipment_details']->id;
//        $save_report['report'] = $reportFile.'.pdf';
//        $this->serviceModel->save_workorder_items($save_report);
//
//        Response::download($pathToFile, 'report.pdf', $headers);
//        return Response::json([
//            'status' => 1,
//            'message' => 'Success',
//            'pdf_url' => url('/public/report/' . $reportFile . '.pdf')
//        ], 200);
//
//
//    }


    function viewpdf(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(!$user['signature'])
        {
            return Response::json([
                'status' => 0,
                'message' => 'Please upload your signature in my profile'
            ], 500);
        }
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

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

        $service_request_item_id = $input['id'];
        $get_workorder_item = DB::table('tbl_work_order_items as oi')->select('oi.report', 'oi.technician_review_date', 'oi.admin_review', 'oi.admin_review_date','t.signature as techniciansignature','a.signature as adminsignature','oi.comments','oi.id')
            //->join('tbl_technician as u','u.id','=','oi.technician_review')
            ->join('tbl_users as t','t.id','=','oi.technician_review')
            ->join('tbl_users as a','a.id','=','oi.admin_review','left')
            ->where('request_item_id', '=', $service_request_item_id)->get()->first();
        if ($get_workorder_item) {
            if (!$get_workorder_item->admin_review) {
                return Response::json([
                    'status' => 0,
                    'message' => 'Under admin review'
                ], 500);
            }
            if ($get_workorder_item->report) {
                $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/technicianreview/' . $get_workorder_item->report;
                //serviceplanp

                if (file_exists($reviewedReport)) {
                    $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
                        ->join('tbl_service_request_item as ri', 'ri.id', '=', 'oi.request_item_id')
                        ->join('tbl_service_request as sr', 'sr.id', '=', 'ri.service_request_id')
                        ->join('tbl_work_order as wo', 'wo.request_id', '=', 'sr.id')
                        ->join('tbl_service_plan as sp', 'sp.id', '=', 'wo.plan_id')
                        ->join('tbl_technician as t', 't.id', '=', 'wo.calibration_to')
                        ->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
                        ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
                        ->join('tbl_equipment_model as em', 'em.id', '=', 'e.equipment_model_id')
                        ->join('tbl_customer as c', 'c.id', '=', 'e.customer_id')
                        ->join('tbl_customer_setups', 'tbl_customer_setups.customer_id', '=', 'e.customer_id')
                        ->select('tbl_customer_setups.cal_spec', 'e.*', 'c.*', 'em.model_name', 'em.model_description', 'de.last_cal_date', 'de.next_due_date', 't.first_name as tfname', 't.last_name as tlname', 'oi.id', 'oi.report', 'em.id as emodel_id', 'em.volume', 'em.volume_value', 'em.brand_operation', 'em.channel', 'sp.calibration_outside','sr.request_no')->where('ri.id', $service_request_item_id)->get()->first();

//                    if ($data['equipment_details']) {
//                        if ($data['equipment_details']->report) {
//
//                            return Response::json([
//                                'status' => 1,
//                                'message' => 'Success',
//                                'pdf_url' => url('/public/report/' . $data['equipment_details']->report)
//                            ], 200);
//                        }
//                    }


                        if ($data['equipment_details']->calibration_outside == 1) {
                            $array = $data['equipment_details'];
                            $outside = $this->calibrationoutsidereview($array,$user);
                            //print_r($outside);die;
                            Response::download($outside['pathToFile'], 'report.pdf', $outside['headers']);
                            return Response::json([
                                'status' => 1,
                                'message' => 'Success',
                                'pdf_url' => url('/public/report/customerreview/' . $outside['report'])
                            ], 200);
                        }

                        else
                        {
                    $data['as_found_workorder'] = $this->serviceModel->workorder_status($get_workorder_item->id, 1);
                    $as_found_workorder_log = $this->serviceModel->workorder_asfound_log($get_workorder_item->id);
                    $model_id = (isset($data['equipment_details']->emodel_id) && $data['equipment_details']->emodel_id) ? $data['equipment_details']->emodel_id : '';
                    $volume_id = (isset($data['equipment_details']->volume) && $data['equipment_details']->volume) ? $data['equipment_details']->volume : '0';
                    $test_points = array();
                    if ($model_id) {
                        // $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id',$model_id)->get();

                        switch ($data['equipment_details']->cal_spec) {
                            case 1:
                                if ($volume_id == 1) {
                                    $spec = DB::table('tbl_iso_limit_tolerance');
                                    $spec->join('tbl_iso_specifications', 'tbl_iso_specifications.id', '=', 'tbl_iso_limit_tolerance.specification_id');
                                    $spec->where([
                                        ['channel_id', '=', $data['equipment_details']->channel],
                                        ['operation_id', '=', $data['equipment_details']->brand_operation],
                                        ['volume_id', '=', $data['equipment_details']->volume],
                                        ['tbl_iso_specifications.volume_value', '=', $data['equipment_details']->volume_value],
                                    ]);
                                    $spec->select('tbl_iso_limit_tolerance.*');
                                    $test_points = $spec->get();
                                } else {
                                    $spec = DB::table('tbl_iso_limit_tolerance');
                                    $spec->join('tbl_iso_specifications', 'tbl_iso_specifications.id', '=', 'tbl_iso_limit_tolerance.specification_id');
                                    $spec->where([
                                        ['channel_id', '=', $data['equipment_details']->channel],
                                        ['operation_id', '=', $data['equipment_details']->brand_operation],
                                        ['volume_id', '=', $data['equipment_details']->volume],
                                        ['tbl_iso_specifications.volume_value', '=', $data['equipment_details']->volume_value],
                                    ]);
                                    $spec->select('tbl_iso_limit_tolerance.*');
                                    $test_points = $spec->get();
                                }

                                break;
                            case 2:
                                $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id', $model_id)->get();
                                break;
                            default:
                                $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id', $model_id)->get();
                                break;
                        }
                    }
                    $tempfound = array();
                            $foundPassFail = array();
                            $foundStatus = '';
                    if ($as_found_workorder_log) {
                        foreach ($as_found_workorder_log as $fkey => $frow) {

                            if ($frow->test_point_id == 1) {
                                $test_target = $test_points[0]->target_value;
                            } elseif ($frow->test_point_id == 2) {
                                $test_target = $test_points[1]->target_value;
                            } elseif ($frow->test_point_id == 3) {
                                $test_target = $test_points[2]->target_value;
                            } else {
                                $test_target = $test_points[0]->target_value;
                            }

                            $tempfound[$fkey]['test_target'] = $test_target;

                            $tempfound[$fkey]['channel'] = $frow->reading_channel;
                            $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
                            $tempfound[$fkey]['mean'] = $frow->reading_mean;
                            $tempfound[$fkey]['sd'] = $frow->reading_sd;
                            $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
                            $tempfound[$fkey]['target_accuracy'] = $frow->target_accuracy;
                            $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
                            $tempfound[$fkey]['target_precision'] = $frow->target_precision;
                            $tempfound[$fkey]['status'] = $frow->reading_status == 1 ? 'Passed' : 'Failed';
                            $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings);
                            if($frow->reading_status == 1)
                            {
                                $foundPassFail[$fkey] = "Passed";
                            }
                            else{
                                $foundPassFail[$fkey] = "Failed";
                            }

                        }
                    }
                            if(in_array("Failed",$foundPassFail))
                            {
                                $foundStatus = "Failed";
                            }
                            else
                            {
                                $foundStatus = "Passed";
                            }
                            $data['foundPassFail'] = $foundStatus;
                    $data['calibrated_workorder'] = $this->serviceModel->workorder_status($get_workorder_item->id, 3);

                    //last and next due dates for device

                    $data['balance_last_date'] = '';
                    $data['balance_due_date'] = '';
                    $data['barometer_last_date'] = '';
                    $data['barometer_due_date'] = '';
                    $data['thermometer_last_date'] = '';
                    $data['thermometer_due_date'] = '';
                    $data['thermocouple_last_date'] = '';
                    $data['thermocouple_due_date'] = '';

                    if ($data['calibrated_workorder']) {
                        $balance_device_id = (isset($data['calibrated_workorder']->balance_device_id) && $data['calibrated_workorder']->balance_device_id) ? $data['calibrated_workorder']->balance_device_id : 1;
                        $barometer_device_id = (isset($data['calibrated_workorder']->barometer_device_id) && $data['calibrated_workorder']->barometer_device_id) ? $data['calibrated_workorder']->barometer_device_id : 1;
                        $thermometer_device_id = (isset($data['calibrated_workorder']->thermometer_device_id) && $data['calibrated_workorder']->thermometer_device_id) ? $data['calibrated_workorder']->thermometer_device_id : 1;
                        $thermocouple_device_id = (isset($data['calibrated_workorder']->thermocouple_device_id) && $data['calibrated_workorder']->thermocouple_device_id) ? $data['calibrated_workorder']->thermocouple_device_id : 1;

                        $balance = DB::table('tbl_device')->where('id', $balance_device_id)->first();
                        $barometer = DB::table('tbl_device')->where('id', $barometer_device_id)->first();
                        $thermometer = DB::table('tbl_device')->where('id', $thermometer_device_id)->first();
                        $thermocouple = DB::table('tbl_device')->where('id', $thermocouple_device_id)->first();

                        $data['balance_last_date'] = (isset($balance->last_cal_date) && $balance->last_cal_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $balance->last_cal_date))) : '';
                        $data['balance_due_date'] = (isset($balance->next_due_date) && $balance->next_due_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $balance->next_due_date))) : '';

                        $data['barometer_last_date'] = (isset($barometer->last_cal_date) && $barometer->last_cal_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $barometer->last_cal_date))) : '';
                        $data['barometer_due_date'] = (isset($barometer->next_due_date) && $barometer->next_due_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $barometer->next_due_date))) : '';

                        $data['thermometer_last_date'] = (isset($thermometer->last_cal_date) && $thermometer->last_cal_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $thermometer->last_cal_date))) : '';
                        $data['thermometer_due_date'] = (isset($thermometer->next_due_date) && $thermometer->next_due_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $thermometer->next_due_date))) : '';

                        $data['thermocouple_last_date'] = (isset($thermocouple->last_cal_date) && $thermocouple->last_cal_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $thermocouple->last_cal_date))) : '';
                        $data['thermocouple_due_date'] = (isset($thermocouple->next_due_date) && $thermocouple->next_due_date) ? date('d-M-Y', strtotime(str_replace('/', '-', $thermocouple->next_due_date))) : '';
                    }

                    $calibrated_workorder_log = $this->serviceModel->workorder_calibrated_log($get_workorder_item->id);
                    $tempcalibrated = array();
                            $calibratedPassFail = array();
                            $calibratedStatus = '';
                    if ($calibrated_workorder_log) {
                        foreach ($calibrated_workorder_log as $ckey => $crow) {
                            if ($crow->test_point_id == 1) {
                                $test_target = $test_points[0]->target_value;
                            } elseif ($crow->test_point_id == 2) {
                                $test_target = $test_points[1]->target_value;
                            } elseif ($crow->test_point_id == 3) {
                                $test_target = $test_points[2]->target_value;
                            } else {
                                $test_target = $test_points[0]->target_value;
                            }

                            $tempcalibrated[$ckey]['test_target'] = $test_target;

                            $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
                            $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
                            $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
                            $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
                            $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
                            $tempcalibrated[$ckey]['target_accuracy'] = $crow->target_accuracy;
                            $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
                            $tempcalibrated[$ckey]['target_precision'] = $crow->target_precision;
                            $tempcalibrated[$ckey]['status'] = $crow->reading_status == 1 ? 'Passed' : 'Failed';
                            $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);
                            if($crow->reading_status == 1)
                            {
                                $calibratedPassFail[$ckey] = "Passed";
                            }
                            else{
                                $calibratedPassFail[$ckey] = "Failed";
                            }

                        }
                    }
                            if(in_array("Failed",$calibratedPassFail))
                            {
                                $calibratedStatus = "Failed";
                            }
                            else
                            {
                                $calibratedStatus = "Passed";
                            }
                            $data['calibratedPassFail'] = $calibratedStatus;
                    $data['found_log'] = $tempfound;
                    $data['calibrated_log'] = $tempcalibrated;
                    $data['comments'] = $get_workorder_item->comments;
                    $data['tech_signature'] = $get_workorder_item->techniciansignature;
                    $data['tech_date'] = $get_workorder_item->technician_review_date;
                    $data['admin_signature'] = $get_workorder_item->adminsignature;
                    $data['admin_date'] = $get_workorder_item->admin_review_date;
                    $data['customer_signature'] = $user['signature'];
                    $data['customer_date'] = date('Y-m-d');
                    //print_r($data);die;

                    $data['maintenance_workorder'] = $this->serviceModel->workorder_status($get_workorder_item->id, 2);

                    $maintenance_data = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id', '=', $data['maintenance_workorder']->id)->first();
                    //maintenance data
                    $maintenance = (isset($maintenance_data->workorder_checklists) && $maintenance_data->workorder_checklists) ? $maintenance_data->workorder_checklists : '';
                    $performed = array();
                    if ($maintenance) {
                        $checklist_array = explode(',', $maintenance);
                        if ($checklist_array) {
                            foreach ($checklist_array as $key => $row) {
                                $checklist_id = str_replace('~', '', $row);
                                $checklist = DB::table('tbl_checklist_item')->where('id', '=', $checklist_id)->first();
                                $performed[$key] = isset($checklist->title) ? $checklist->title : '';

                            }
                        }
                    }

                    $data['performed'] = implode(',', $performed);

                    //parts data
                    $parts = (isset($maintenance_data->workorder_spares) && $maintenance_data->workorder_spares) ? $maintenance_data->workorder_spares : '';
                    $decoded_parts = json_decode($parts);
                    $parts_replaced = array();

                    if ($decoded_parts) {
                        foreach ($decoded_parts as $key => $row) {
                            $part_id = $row->id;
                            $part = DB::table('tbl_equipment_model_spares')->where('id', '=', $part_id)->first();
                            $parts_replaced[$key] = isset($part->part_name) ? $part->part_name : '';

                        }
                    } //print_r($parts_replaced);die;
                   // $data['parts_replaced'] = implode(',',$parts_replaced);
                    $data['parts_replaced'] = $parts_replaced;

                    $customerpath = base_path() . '/public/report/customerreview';
                    $technicianpath = base_path() . '/public/report/technicianreview';
                    $adminpath = base_path() . '/public/report/adminreview';
                    $reportFile = 'report-' . uniqid();

                    view()->share($data);
//                    $pdf = PDF::loadView('report.customerreport')
//                        ->save($customerpath . '/' . $reportFile . '.pdf', 'F');
//                    PDF::loadView('report.customerreport')
//                        ->save($technicianpath . '/' . $reportFile . '.pdf', 'F');
//                    PDF::loadView('report.customerreport')
//                        ->save($adminpath . '/' . $reportFile . '.pdf', 'F');

                    $pdf = app('dompdf.wrapper');
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    $pdf->loadView('report.customerreport')->save($customerpath . '/' . $reportFile . '.pdf', 'F');

                    $pdf1 = app('dompdf.wrapper');
                    $pdf1->getDomPDF()->set_option("enable_php", true);
                    $pdf1->loadView('report.customerreport')->save($technicianpath . '/' . $reportFile . '.pdf', 'F');

                    $pdf2 = app('dompdf.wrapper');
                    $pdf2->getDomPDF()->set_option("enable_php", true);
                    $pdf2->loadView('report.customerreport')->save($adminpath . '/' . $reportFile . '.pdf', 'F');

                    $pathToFile = base_path() . '/public/report/customerreview/' . $reportFile . '.pdf';

                    $headers = array(
                        'Content-Type: application/pdf',
                    );

                    $save_report['id'] = $data['equipment_details']->id;
                    $save_report['report'] = $reportFile . '.pdf';
                    $save_report['customer_review'] = $user['id'];
                    $save_report['customer_review_date'] = date('Y-m-d');
                    $this->serviceModel->save_workorder_items($save_report);

                    Response::download($pathToFile, 'report.pdf', $headers);
                    return Response::json([
                        'status' => 1,
                        'message' => 'Success',
                        'pdf_url' => url('/public/report/customerreview/' . $reportFile . '.pdf')
                    ], 200);
                    }
                }

            }
        }

    }


    public
    function calibrationoutsidereview($data,$user)
    {
        // echo '<pre>';print_r($data);exit;

        $report = $data->report;
        $reviewReport = base_path() . '/public/report/technicianreview/' .  $report;

        if (file_exists($reviewReport)) {
            file_put_contents(base_path() . '/public/report/customerreview/'.$report,file_get_contents($reviewReport));
            $save_report['id'] = $data->id;
            $save_report['customer_review'] = $user['id'];
            $save_report['customer_review_date'] = date('Y-m-d');
//        echo '<pre>';print_r($save_report);exit;
            $this->serviceModel->save_workorder_items($save_report);
            $func['pathToFile'] = base_path() . '/public/report/customerreview/' . $report;
            $func['headers'] = array(
                'Content-Type: application/pdf',
            );
            $func['report']  = $report;
            return $func;

        }else{
            return Response::json([
                'status' => 0,
                'message' => 'The file was not found'
            ], 404);
        }
    }

    function viewconsolidatepdf(Request $request)
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

        $this->uid = $user['id'];
        $reqInputs = $request->json()->all(); //echo'<pre>';print_r($reqInputs);'</pre>';die;
        $input = [
            'request_id' => $reqInputs['request_id']
        ];
        $rules = array(
            'request_id' => 'required|not_in:0'
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

        $requestId = $input['request_id'];
        $getWorder = $this->workorderitemmove->getServiceRequestWorkorder($requestId); //print_r($getWorder);die;
        $workOrderId = $getWorder->id;
        $work_order = $this->workorder->getParticularWorkOrder($workOrderId);

        $service_plan = DB::table('tbl_service_plan')->select('calibration_outside')->where('id','=',$work_order->plan_id)->get()->first();
        if($service_plan->calibration_outside!=1)
        {
            if(!$work_order->admin_review)
            {
                return Response::json([
                    'status' => 0,
                    'message' => "Under QC review"
                ], 500);
            }
            $adminReview = DB::table('tbl_users')->where('id','=',$work_order->admin_review)->get()->first();


            if(empty($work_order))
            {
                return Response::json([
                    'status' => 0,
                    'message' => "This workorder is not found"
                ], 404);
            }

            $calibrated_technician = DB::table('tbl_technician')->where('id', $work_order->calibration_to)->first();
            $customerReport = $_SERVER['DOCUMENT_ROOT'] . '/public/report/consolidate/customerreview/' . $work_order->report;
            if(file_exists($customerReport))
            {
                return Response::json([
                    'status' => 1,
                    'message' => 'Success',
                    'pdf_url' => url('/public/report/consolidate/customerreview/' .  $work_order->report)
                ], 200);
            }
            $technicianReview = DB::table('tbl_technician as t')
                ->join('tbl_users as u','u.id','=','t.user_id')
                ->where('u.id','=',$work_order->technician_review)
                ->select('u.id')
                ->get()->first();
            $this->tid = $technicianReview->id;
            $work_order_items = $this->workorder->totalInstruments($workOrderId);
            $work_order_items_calibrated = $this->workorder->totalInstrumentsCalibrated($workOrderId);
            if($work_order_items!=$work_order_items_calibrated)
            {
                return Response::json([
                    'status' => 0,
                    'message' => "Some instruments in this workorder are not calibrated. please calibrate it and try"
                ], 500);
            }

            $customer = DB::table('tbl_customer as c')
                ->join('tbl_service_request as sr','sr.customer_id','=','c.id')
                ->where('sr.id','=',$work_order->request_id)->get()->first();
            $technician = DB::table('tbl_technician as t')
                ->join('tbl_users as u','u.id','=','t.user_id')
                ->where('u.id','=',$this->tid)->get()->first();

            $digital_barometer = $this->workorderitemmove->getTechnicianDeviceBarometer(2,$this->tid,$workOrderId);
            $digital_thermometer = $this->workorderitemmove->getTechnicianDeviceThermometer(3,$this->tid,$workOrderId);
            $digital_thermocouple = $this->workorderitemmove->getTechnicianDeviceThermocouple(4,$this->tid,$workOrderId);
            $digital_balance = $this->workorderitemmove->getTechnicianDeviceBalance(1, $this->tid, $workOrderId);
            $workorder_calibration = $this->workorderitemmove->getWorkorderCalibration($workOrderId);
            $calibration_data = array();
            $multicalibration_data = array();
            $data = array();
            $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/consolidate/technicianreview/' . $work_order->report;
            $reviewedReport = base_path() . '/public/report/consolidate/technicianreview/' . $work_order->report;
            if(file_exists($reviewedReport))
                if($workorder_calibration)
                {
                    $check = '';


                    foreach($workorder_calibration as $key=>$row)
                    {
                        $spares_used_array = array();

                        $calibration_data[$key]['asset_no'] = $row->asset_no;
                        $calibration_data[$key]['serial_no'] = $row->serial_no;
                        $calibration_data[$key]['location'] = $row->location;
                        $calibration_data[$key]['model'] = $row->model_description;
                        $calibration_data[$key]['volume'] = $row->reading_mean_volume;
                        $calibration_data[$key]['mean'] = $row->reading_mean;
                        $calibration_data[$key]['sd'] = $row->reading_sd;
                        $calibration_data[$key]['unc'] = $row->reading_unc;
                        $calibration_data[$key]['actual_accuracy'] = $row->reading_accuracy;
                        $calibration_data[$key]['specification_accuracy'] = $row->target_accuracy;
                        $calibration_data[$key]['actual_precision'] = $row->reading_precision;
                        $calibration_data[$key]['specification_precision'] = $row->target_precision;
                        $calibration_data[$key]['status'] = $row->reading_status==1?'Passed':'Failed';
                        $calibration_data[$key]['last_cal_date'] =  date('d-M-Y',strtotime(str_replace('/','-',$row->last_cal_date)));
                        $calibration_data[$key]['next_due_date'] =  date('d-M-Y',strtotime(str_replace('/','-',$row->next_due_date)));
                        $calibration_data[$key]['technician_name'] = (isset($calibrated_technician->first_name)&&$calibrated_technician->first_name)?$calibrated_technician->first_name.' '.$calibrated_technician->last_name:'';
                        $calibration_data[$key]['reported_date'] = $row->customer_review_date?date('d-M-Y',strtotime(str_replace('/','-',$row->customer_review_date))):'Not applicable';

                        $get_workorder_status = DB::table('tbl_workorder_status_move')->where('workorder_item_id',$row->work_order_item_id)
                            ->where('workorder_status',2)->first();
                        if($get_workorder_status)
                        {
                            $spares = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id',$get_workorder_status->id)->first();
                            if($spares)
                            {
                                if($spares->workorder_spares)
                                {
                                    $sparesMaterial = json_decode($spares->workorder_spares);
                                    if($sparesMaterial)
                                    {

                                        foreach ($sparesMaterial as $spareKey=>$spareRow)
                                        {
                                            $spares_used_array[$spareKey] = $spareRow->number;
                                        }

                                    }
                                }

                                if($spares->workorder_checklists)
                                {
                                    $arraycheck = explode(',',str_replace('~','',$spares->workorder_checklists));
                                    //$like = "id" . " LIKE '" . str_replace('~','',$spares->workorder_checklists) . "' ";
                                    $checklist = DB::table('tbl_checklist_item')->wherein('id',$arraycheck)->get();

                                    if(count($checklist))
                                    {
                                        foreach ($checklist as $ckey=>$crow)
                                        {
                                            $checks[$ckey] = $crow->title;
                                        }
                                        $check = implode(',', $checks);
                                    }
                                }

                            }
                        }
                        $get_workorder_calibration_status = DB::table('tbl_workorder_status_move')->where('workorder_item_id', $row->work_order_item_id)
                            ->where('workorder_status', 3)->first();
                        if($get_workorder_calibration_status)
                        {
                            $calibration = DB::table('tbl_workorder_ascalibrated_log')->where('workorder_status_id', $get_workorder_calibration_status->id)->get();
                            $calibrated_log = array();
                            if(count($calibration))
                            {
                                foreach ($calibration as $calkey=>$calrow)
                                {
                                    $calibrated_log[$calkey]['channel'] = $calrow->reading_channel;

                                    $calibrated_log[$calkey]['volume'] = $calrow->reading_mean_volume;
                                    $calibrated_log[$calkey]['volume'] = $calrow->reading_mean_volume;
                                    $calibrated_log[$calkey]['mean'] = $calrow->reading_mean;
                                    $calibrated_log[$calkey]['sd'] = $calrow->reading_sd;
                                    $calibrated_log[$calkey]['unc'] = $calrow->reading_unc;
                                    $calibrated_log[$calkey]['actual_accuracy'] = $calrow->reading_accuracy;
                                    $calibrated_log[$calkey]['specification_accuracy'] = $calrow->target_accuracy;
                                    $calibrated_log[$calkey]['actual_precision'] = $calrow->reading_precision;
                                    $calibrated_log[$calkey]['specification_precision'] = $calrow->target_precision;
                                    $calibrated_log[$calkey]['target_value'] = $calrow->target_data;
                                    $calibrated_log[$calkey]['status'] = $calrow->reading_status == 1 ? 'Passed' : 'Failed';
                                }

                            }
                        }
                        $spares_used = implode(',', $spares_used_array);
                        if($spares_used)
                        {
                            $calibration_data[$key]['spares'] = $check.','.$spares_used;
                        }
                        else
                        {
                            $calibration_data[$key]['spares'] = $check;
                        }
                        $calibration_data[$key]['calibrated_log'] = $calibrated_log;
                    }
                }
            $data['last_cal_date'] = $calibration_data[0]['last_cal_date'];
            $data['next_due_date'] = $calibration_data[0]['next_due_date'];
            $data['asset_no'] = $calibration_data[0]['asset_no'];

            $data['digital_barometer'] = $digital_barometer;
            $data['digital_thermometer'] = $digital_thermometer;
            $data['digital_thermocouple'] = $digital_thermocouple;
            $data['digital_balance'] = $digital_balance;
            $data['customer'] = $customer;
            $data['technician'] = $technician;
            $data['workorder'] = $work_order;
            $data['calibrated_datas'] = $calibration_data;
            $data['multicalibrated_datas'] = $multicalibration_data;
            $data['digital_barometer_device'] = (isset($digital_barometer->serial_no))?$digital_barometer->serial_no:'';
            $data['digital_thermometer_device'] = (isset($digital_thermometer->serial_no))?$digital_thermometer->serial_no:'';
            $data['digital_thermocouple_device'] = (isset($digital_thermocouple->serial_no))?$digital_thermocouple->serial_no:'';
            $data['digital_balance_device'] = (isset($digital_balance->serial_no)) ? $digital_balance->serial_no : '';

            $data['barometer_last_cal_date'] = (isset($digital_barometer->last_cal_date)) ? $digital_barometer->last_cal_date : '';
            $data['thermometer_last_cal_date'] = (isset($digital_thermometer->last_cal_date)) ? $digital_thermometer->last_cal_date : '';
            $data['thermocouple_last_cal_date'] = (isset($digital_thermocouple->last_cal_date)) ? $digital_thermocouple->last_cal_date : '';
            $data['balance_last_cal_date'] = (isset($digital_balance->last_cal_date)) ? $digital_balance->last_cal_date : '';

            $data['barometer_next_due_date'] = (isset($digital_barometer->next_due_date)) ? $digital_barometer->next_due_date : '';
            $data['thermometer_next_due_date'] = (isset($digital_thermometer->next_due_date)) ? $digital_thermometer->next_due_date : '';
            $data['thermocouple_next_due_date'] = (isset($digital_thermocouple->next_due_date)) ? $digital_thermocouple->next_due_date : '';
            $data['balance_next_due_date'] = (isset($digital_balance->next_due_date)) ? $digital_balance->next_due_date : '';

            $data['uploaded_signature'] = $technician->signature;
            $data['admin_signature'] = $adminReview->signature;
            $data['customer_signature'] = $user->signature;
            $data['tech_date'] = $work_order->technician_review_date;
            $data['admin_date'] = $work_order->admin_review_date;
            $data['comments'] = $work_order->comments;
            //echo'<pre>';print_r($data);'</pre>';die;
            $customerpath = base_path() . '/public/report/consolidate/customerreview';
            $technicianpath = base_path() . '/public/report/consolidate/technicianreview';
            $adminpath = base_path() . '/public/report/consolidate/adminreview';
            $reportFile = 'consolidate_report-' . uniqid();

            view()->share($data);

            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadView('report.consolidacustomerreport')->save($technicianpath . '/' . $reportFile . '.pdf', 'F');

            $pdf1 = app('dompdf.wrapper');
            $pdf1->getDomPDF()->set_option("enable_php", true);
            $pdf1->loadView('report.consolidacustomerreport')->save($adminpath . '/' . $reportFile . '.pdf', 'F');

            $pdf3 = app('dompdf.wrapper');
            $pdf3->getDomPDF()->set_option("enable_php", true);
            $pdf3->loadView('report.consolidacustomerreport')->save($customerpath . '/' . $reportFile . '.pdf', 'F');


            $pathToFile = base_path() . '/public/report/consolidate/customerreview/' . $reportFile . '.pdf';

            $headers = array(
                'Content-Type: application/pdf',
            );

            $save_report['id'] = $workOrderId;
            $save_report['report'] = $reportFile.'.pdf';
            $save_report['customer_review'] = $user['id'];
            $save_report['customer_review_date'] = date('Y-m-d');
            $this->workorderitemmove->saveWorkOrder($save_report);

            Response::download($pathToFile, $reportFile.'.pdf', $headers);
            return Response::json([
                'status' => 1,
                'message' => 'Success',
                'pdf_url' => url('/public/report/consolidate/customerreview/' . $reportFile . '.pdf')
            ], 200);
        }
        else
        {
            $requestId = $input['request_id'];
            $getWorder = $this->workorderitemmove->getServiceRequestWorkorder($requestId); //print_r($getWorder);die;
            $workOrderId = $getWorder->id;
            $work_order = $this->workorder->getParticularWorkOrder($workOrderId);
            $save_report['id'] = $workOrderId;
            $save_report['customer_review'] = $user['id'];
            $save_report['customer_review_date'] = date('Y-m-d');
            $this->workorderitemmove->saveWorkOrder($save_report);

            $adminpath = base_path() . '/public/report/consolidate/adminreview';
            $customerpath = base_path() . '/public/report/consolidate/customerreview';
            $fileName= $work_order->report;
            copy($adminpath.'/'.$fileName, $customerpath.'/'.$fileName);
            $pathToFile = base_path() . '/public/report/consolidate/customerreview/' . $fileName;
            $headers = array(
                'Content-Type: application/pdf',
            );
            Response::download($pathToFile, $fileName, $headers);
            return Response::json([
                'status' => 1,
                'message' => 'Success',
                'pdf_url' => url('/public/report/consolidate/customerreview/' . $fileName)
            ], 200);
        }



    }


}