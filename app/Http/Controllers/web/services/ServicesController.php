<?php

namespace App\Http\Controllers\web\services;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServicePlan;
use App\Models\Servicerequest;
use App\Models\Equipment;
use App\Models\DueEquipments;
use App\Models\CustomerService;
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
use PDF;
use Mail;

class ServicesController extends Controller
{

    public function __construct()
    {
        $this->servicerequest = new Servicerequest();
        $this->servicePlan = new ServicePlan();
        $this->equipment = new Equipment();
        $this->dueequipments = new DueEquipments();
        $this->customer = new Customer();
        $this->customerservice = new CustomerService();

    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Buy Service';
        $status = array('' => 'Please Select', '1' => 'Assign', '0' => 'Unassigned');
        $statuskeyword = isset($input['status']) ? $input['status'] : '';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';


        $temp = array();


        return view('services.buyservice')->with('status', $status)->with('statuskeyword', $statuskeyword)->with('title', $title)->with('keyword', $keyword);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['tcbs.service_number'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['tc.customer_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['total_models'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['tcbs.created_date'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';

        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('tcbs.id as buyServiceId', 'tcbs.service_number as serviceNumber', 'tcbs.service_status as serviceStatus', 'customer_id as customerid',
            'total_models as totalModels', 'tcbs.created_date as createdDate', 'tc.id as customerId', 'tc.customer_name as customerName');
        $data = $this->customerservice->AllBuyServiceGrid($param['limit'], $param['offset'], 'tcbs.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->customerservice->AllBuyServiceGrid($param['limit'], $param['offset'], 'tcbs.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true),
            true);
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $totalInstruments = DB::table('tbl_customer_service_model')->where('service_id',$row->buyServiceId)->sum('quantity');
                $totalInstrumentsCount = $totalInstruments?$totalInstruments:0;
                $values[$key]['0'] = $row->serviceNumber;
                $values[$key]['1'] = $row->customerName;
                $values[$key]['2'] = $totalInstrumentsCount;
                $values[$key]['3'] = date('m-d-Y', strtotime(str_replace('/', '-', $row->createdDate)));
                $values[$key]['4'] = "<a href=" . url('admin/serviceReqDetails/' . $row->buyServiceId) . " class=''><i class='fa fa-eye'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function buyServiceDetails(Request $request, $serviceID)
    {

        $title = 'Novamed-Service Details';


        $getServiceDetails = $this->customerservice->getService($serviceID);

        $data = array();
        if ($getServiceDetails) {
            $customerId = $getServiceDetails->customer_id;
            $getUserDetails = $this->customer->getUserByCustomer($customerId);
//            echo '<pre>';print_r($getUserDetails);exit;
            if ($getUserDetails) {
                $userId = $getUserDetails->id;
                $getUserGroup = $this->customer->getgroupUser($userId);
//                echo '<pre>';print_r($getUserGroup);exit;
                if ($getUserGroup) {

                    foreach ($getUserGroup as $groupkey => $groupval) {

                        $groups[$groupkey] = (array)$groupval;

                    }


                    if (in_array(2, array_column($groups, 'user_group_id'))) {

                        // search value in the array
                        $data['access'] = 0;
                    } else {
                        $data['access'] = 1;
                    }
                }
            }
//            echo '<pre>';print_r($data['access']);exit;
            $data['getCustomer'] = $this->customer->getCustomer($customerId);
            $data['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
            $data['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);
            $data['contactDetails'] = $this->customer->getCompanyContact($customerId);
            $data['getPlans'] = $this->customerservice->getCustomerPlans($customerId);
            $data['payment'] = DB::table('tbl_customer_setups')
                                ->where('customer_id', $customerId)->first();

        }


        $perPage = 20;
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;


        $select = array('tcbs.id as buyServiceId', 'tcbs.service_number as serviceNumber', 'customer_id as customerId',
            'total_models as totalModels', 'tcbs.created_date as createdDate', 'tcsm.id as buyServiceId', 'tcsm.model_id as modelid'
        , 'tcsm.customer_plan_id as planId', 'tem.id as modelId', 'tem.model_description as modelDescription', 'tem.model_price as modelPrice', 'tem.volume as model_volume', 'tem.brand_operation as model_operation', 'tem.channel as model_channel', 'tem.channel_number as model_channel_number', 'tcsm.quantity', 'tcsm.frequency_id', 'fr.no_of_days');
        $modelData = $this->customerservice->AllModels($perPage, $offset, 'tcsm.id', 'ASC', array('select' => $select, 'serviceId' => $serviceID));
        $count = $this->customerservice->AllModels('', '', 'tcsm.id', 'ASC', array('select' => $select, 'serviceId' => $serviceID), true);
        $frequency = DB::table('tbl_frequency')->get();
        if ($frequency) {
            foreach ($frequency as $freqkey) {

                $freq[$freqkey->id] = $freqkey->name;

            }
        }

        $freq[4] = 'Pick up date';

        if (!$modelData->isEmpty()) {
            foreach ($modelData as $servicekey => $servicemodel) {

                $planId = $servicemodel->planId;
                $customerId = $servicemodel->customerId;
                $plan_array = array();
                if ($planId) {
                    $getAcuServicePlans = substr($planId, -1); //print_r($getAcuServicePlans);die;
                    if($getAcuServicePlans==',')
                    {
                        $newAcuPlan = rtrim($planId,", ");
                    }
                    else
                    {
                        $newAcuPlan = $planId;
                    }
                    $plans = str_replace('~', '', $newAcuPlan);
                    $plangroup = explode(',', $plans);
                    //print_r($servicemodel);die;
                    if ($plangroup) {
                        foreach ($plangroup as $plankey => $planval) {
                            $getPlanName = $this->customerservice->getPlan($planval);
                            $plan_array[$planval] = $getPlanName->service_plan_name;
                        }
                    }
                }
                $plan_id = isset($plangroup[0]) ? $plangroup[0] : '';
                $pricing = $this->customerservice->service_price($plan_id, $servicemodel->model_volume, $servicemodel->model_operation, $servicemodel->model_channel, $servicemodel->model_channel_number);
                $temp[$servicekey]['service_plans'] = $plan_array;
                $temp[$servicekey]['buy_service_model_id'] = $servicemodel->buyServiceId;
                $temp[$servicekey]['serviceNumber'] = $servicemodel->serviceNumber;
                $temp[$servicekey]['customerId'] = $servicemodel->customerId;
                $temp[$servicekey]['totalModels'] = $servicemodel->totalModels;
                $temp[$servicekey]['createdDate'] = $servicemodel->createdDate;
                $temp[$servicekey]['modelid'] = $servicemodel->modelid;
                $temp[$servicekey]['modelDescription'] = $servicemodel->modelDescription;
                $temp[$servicekey]['service_price_id'] = isset($pricing->id) ? $pricing->id : '';
                $temp[$servicekey]['service_price'] = isset($pricing->price) ? $pricing->price : '';
                $temp[$servicekey]['quantity'] = $servicemodel->quantity;
                $temp[$servicekey]['frequency'] = $servicemodel->frequency_id;
                $temp[$servicekey]['days'] = $servicemodel->no_of_days;
                $temp[$servicekey]['location'] = $data['getCustomer']->address1;
                $temp[$servicekey]['location'] = $data['getCustomer']->address1;
                $temp[$servicekey]['customerTelephone'] = $data['getCustomer']->customer_telephone;
                $temp[$servicekey]['contactName'] = (isset($data['contactDetails']->contact_name)&&$data['contactDetails']->contact_name)?$data['contactDetails']->contact_name:'';

            }
        }


        // Create a new Laravel collection from the array data
        $itemCollection = collect($temp);
        // Create our paginator and pass it to the view
        $paginator = new LengthAwarePaginator($itemCollection, $count, $perPage);
        // set url path for generted links
        $paginator->setPath($request->url());

//        echo'<pre>';print_r($data);'</pre>';die;

        return view('services.serviceviewdetails')->with('getServiceDetails', $getServiceDetails)
            ->with('serviceID', $serviceID)->with('title', $title)
            ->with('customerinfo', $data)->with('data', $paginator)->with('frequency', $freq);


    }

    public function pricingValue()
    {
        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get Properly')));
        }
        $customer_service_model_id = $input['cus_service_model_id'];
        $service_plan_id = $input['service_plan_id'];

        $model = $this->customerservice->customer_service_model($customer_service_model_id);
        if (count($model)) {
            $volume = $model->volume;
            $operation = $model->brand_operation;
            $channel = $model->channel;
            $channel_number = $model->channel_number;
            $pricing = $this->customerservice->service_price($service_plan_id, $volume, $operation, $channel, $channel_number);
            if ($pricing) {
                die(json_encode(array('result' => true, 'data' => $pricing)));
            } else {
                die(json_encode(array('result' => false)));
            }


        } else {
            die(json_encode(array('result' => false)));
        }

    }

    public function addToPortal()
    {
        $input = Input::all();

        $plans = json_decode($input['plans']);

        $query = DB::table('tbl_service_request');
        $query->orderby('id', 'DESC');
        $last_id = $query->get()->first();
        if ($last_id) {
            $request_num = 120000 + $last_id->id;
        } else {
            $request_num = 120000;
        }

        $loginCredentials = $input['logincredentials'];
        $adminVerifyEmail = isset($input['admin_email_verify']) ? $input['admin_email_verify'] : 0;

        $models = $input['model'];
        $access_type = $input['accesstype'];
        $customer_id = $input['customer_id'];
        $serviceID = $input['serviceID'];
        $getUserByCustomer = $this->customer->getUserByCustomer($customer_id);

        $getCustomer = $this->customer->getCustomer($customer_id);
        $contactDetails = $this->customer->getCompanyContact($customer_id);

        $primaryContactName = (isset($getCustomer->name)&&$getCustomer->name)?$getCustomer->name:'';
        $prefContactName = (isset($contactDetails->contact_name)&&$contactDetails->contact_name)?$contactDetails->contact_name:'';
        $customerSetUps = DB::table('tbl_customer_setups')->where('customer_id',$customer_id)->first();


        $userId = $getUserByCustomer->id;

        if ($access_type == 2) {
            $shippingDate = $input['shippingDate'];
        } else {
            $shippingDate = '';
        }
        $exist_asset = array();
        $exist_serial = array();
        $i = 0;
        foreach ($input['model'] as $row) {
            foreach ($row as $value) {
                $exist_asset[$i] = $value['asset_no'];
                $exist_serial[$i] = $value['serial_no'];
                if (isset($value['asset_no']) && $value['asset_no']) {
                    $asset_no_exist = $this->dueequipments->getAssetNumber($value['asset_no'], $input['customer_id'], '');
                    if ($asset_no_exist) {
                        die(json_encode(array('result' => false, 'message' => $value['asset_no'] . '&nbsp' . '&nbsp' . 'Asset Number Already Exist')));
                    }
                }
                if (isset($value['serial_no']) && $value['serial_no']) {
                    $serial_no_exist = $this->dueequipments->getSerialNumber($value['serial_no'], $input['customer_id'], '');
                    if ($serial_no_exist) {
                        die(json_encode(array('result' => false, 'message' => $value['serial_no'] . '&nbsp' . '&nbsp' . 'Serial Number Already Exist')));
                    }
                }
                $i++;
            }
        }
        //Duplicate entry while saving asset Number
        $count_asset = array_count_values($exist_asset);
        foreach ($count_asset as $key => $value1) {
            if ($key != '') {
                if ($value1 != 1) {
                    die(json_encode(array('result' => false, 'message' => $key . '&nbsp' . '&nbsp' . 'Duplicate Asset Number.')));
                }
            }

        }
        //Duplicate entry while saving Serial Number
        $count_serial = array_count_values($exist_serial);
        foreach ($count_serial as $key => $value1) {
            if ($key != '') {
                if ($value1 != 1) {
                    die(json_encode(array('result' => false, 'message' => $key . '&nbsp' . '&nbsp' . 'Duplicate Serial Number.')));
                }
            }

        }


        if ($customer_id) {
//            if ($loginCredentials == 1) {

            $check_customer = DB::table('tbl_customer')->where('id', '=', $customer_id)->first();

            if ($check_customer) {
                $shipping_id = DB::table('tbl_customer_setups')->where('customer_id', '=', $customer_id)->first();
                if ($shipping_id) {
                    $shipping_cost= DB::table('tbl_shipping')->where('id', '=', $shipping_id->shipping)->first();
                    $Shipping_Charge = isset($shipping_cost->shipping_charge) ? $shipping_cost->shipping_charge : 0;
                }
//                else {
//                    $shipping_cost= DB::table('tbl_shipping')->where('name', '=','Ground')->first();
//                    $Shipping_Charge = $shipping_cost->shipping_charge;
//                }

//echo '<pre>';print_r($Shipping_Charge);exit;
                $get_user = DB::table('tbl_users')->where('id', '=', $check_customer->user_id)->first();
                if ($get_user) {
                    $group_user = DB::table('tbl_group_user')->where([['users_id', '=', $get_user->id], ['user_group_id', '=', 2]])->first();

                    if (empty($group_user) && ($adminVerifyEmail == 1)) {
                        $save['id'] = '';
                        $save['user_group_id'] = 2;
                        $save['role_id'] = 1;
                        $save['users_id'] = $get_user->id;
                        $group_user_id = $this->customer->saveUserGroup($save);
                        //       echo $group_user_id;die;
                    }
                    $group_user = DB::table('tbl_group_user')->where([['users_id', '=', $get_user->id], ['user_group_id', '=', 2]])->first();
//                    echo '<pre>';print_R($group_user);exit;
                }
//                }
            }
            if ($models) {
                foreach ($models as $key => $row) {
                    foreach ($row as $ekey => $erow) {
                        //Empty pickupdate
                        if ($erow['frequency'] == 4) {
                            if ($erow['exact_date'] == '')
                                die(json_encode(array('result' => false, 'message' => 'PickUp Date is Empty')));

                        }

                    }
                }
            }
//            save equipment

            $getDuesEquips = array();
            if ($models) {
                foreach ($models as $key => $row) {
                    foreach ($row as $ekey => $erow) {
//                        //Empty pickupdate
//                        if ($erow['frequency'] == 4) {
//                            if ($erow['exact_date'] == '')
//                                die(json_encode(array('result' => false, 'message' => 'PickUp Date is Empty')));
//
//                        }

                        $get_model_id = DB::table('tbl_customer_service_model')->where('id', $key)->first();
                        $model_id = isset($get_model_id->model_id) ? $get_model_id->model_id : '';
                        $saveEqu['id'] = '';
                        $saveEqu['asset_no'] = $erow['asset_no'];
                        $saveEqu['serial_no'] = $erow['serial_no'];
                        $saveEqu['plan_id'] = $erow['service_plan'];
                        $saveEqu['equipment_model_id'] = $model_id;
                        $saveEqu['customer_id'] = $customer_id;
                        $saveEqu['pref_contact'] = (isset($contactDetails->contact_name)&&$contactDetails->contact_name)?$contactDetails->contact_name:null;
                        $saveEqu['pref_tel'] = $getCustomer->customer_telephone;
//                        $saveEqu['location'] = $getCustomer->address1;
                        $saveEqu['is_active'] = 1;
                        $saveEqu['created_by'] = $userId;
                        $equipment_id = $this->dueequipments->saveEquipments($saveEqu);

//                        save due equipment
                        $saveDueEqu['id'] = '';
                        $saveDueEqu['equipment_id'] = $equipment_id;
                        if ($erow['last_cal_date']) {
                            $saveDueEqu['last_cal_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $erow['last_cal_date'])));
                        } else {
                            $saveDueEqu['last_cal_date'] = null;
                        }
                        if ($erow['frequency'] == 4) {
                            $saveDueEqu['next_due_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $erow['exact_date'])));
                            if ($erow['exact_date']) {
                                $saveDueEqu['exact_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $erow['exact_date'])));
                            }
                            $saveDueEqu['pickup_date'] = 1;
                            $saveDueEqu['frequency_id'] = null;
                        } else {
                            $saveDueEqu['frequency_id'] = $erow['frequency'];
                            $saveDueEqu['exact_date'] = null;
                            $saveDueEqu['next_due_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $erow['next_due_date'])));
                            $saveDueEqu['pickup_date'] = 0;
                        }
//                        $saveDueEqu['next_due_date_up'] = date('Y-m-d', strtotime(str_replace('-', '/', $erow['next_due_date_up'])));

                        $saveDueEqu['interval_days'] = isset($erow['intervalperiod']) ? $erow['intervalperiod'] : null;

//                        echo'<pre>';print_r($saveDueEqu);'</pre>';die;
                        $saveDues = $this->dueequipments->saveDueequipments($saveDueEqu);

                        $getDues['id'] = $saveDues;
                        $getDues['equipment_id'] = $saveDueEqu['equipment_id'];
                        $getDues['last_cal_date'] = $saveDueEqu['last_cal_date'];
                        $getDues['frequency_id'] = $saveDueEqu['frequency_id'];
                        $getDues['next_due_date'] = $saveDueEqu['next_due_date'];
//                        $getDues['next_due_date_up'] = $saveDueEqu['next_due_date_up'];
                        $getDues['equipment_id'] = $equipment_id;
                        $getDues['servicePlan'] = $erow['service_plan'];
                        $getDues['servicePriceId'] = $erow['servicePriceId'];
                        $getDuesEquips[] = $getDues;
//                        update freq

                        if ($erow['frequency'] != 4) {
                            $freq['cal_frequnecy'] = $saveDueEqu['frequency_id'];
                            DB::table('tbl_customer_setups')->where('customer_id', '=', $customer_id)->update($freq);
                        }
                    }


                }


                if ($plans) {
                    foreach ($plans as $plankey => $planrow) {
                        $updatePlans = $this->customer->updatePlans($customer_id, $planrow);
                    }
                }

//               change service status
                $saveNumber['id'] = $serviceID;
                $saveNumber['service_status'] = 1;
                $updateId = $this->customerservice->saveServiceModels($saveNumber);

                if (isset($input['serviceID']) && $input['serviceID']) {
                    $changeService['id'] = $input['serviceID'];
                    $changeService['is_created'] = 1;
                    $this->customerservice->saveBuyService($changeService);
                }

//                if it is only for ccredentials
//                if ($access_type == 1) {
////
////                    die(json_encode(array('result' => true, 'value' => 1, 'message' => 'Successfully added')));
////
////                }
//                else {


//                    if it is credentails with service request
                $bQuery = DB::table('tbl_customer_billing_address')->where('customer_id', $customer_id)->first();
                if ($access_type == 2) {
                    if ($getDuesEquips) {
                        $bQuery = DB::table('tbl_customer_billing_address')->where('customer_id', $customer_id)->first();
                        $sQuery = DB::table('tbl_customer_shipping_address')->where('customer_id', $customer_id)->first();

//                     save service request
                        $saveReq['id'] = false;
                        $saveReq['request_no'] = $request_num;
                        $saveReq['customer_id'] = $customer_id;
                        if ($shippingDate != '') {
                            $saveReq['service_schedule_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $shippingDate)));
                        } else {
                            $saveReq['service_schedule_date'] = '---';
                        }
                        $saveReq['service_customer_status'] = 1;
                        $saveReq['is_accessed'] = 1;
                        $saveReq['website_service_id'] = $serviceID;
                        $saveReq['billing_address_id'] = $bQuery->id;
                        $saveReq['shipping_address_id'] = $sQuery->id;
                        $saveReq['created_by'] = $userId;
                        $reqId = $this->servicerequest->saveRequest($saveReq);

//                        save status update

                        $saveStatus['id'] = false;
                        $saveStatus['request_id'] = $reqId;
                        $saveStatus['notes'] = 1;
                        $saveStatus['service_date'] = Carbon::now()->toDateTimeString();

                        $statusId = $this->servicerequest->saveStatus($saveStatus);


//                        save service req items adnd log
                        foreach ($getDuesEquips as $row) {
                            $end_month = date('t', strtotime('m Y'));
                            if ($row['frequency_id']) {
                                $query = DB::table('tbl_frequency');
                                $query->where('id', $row['frequency_id']);
                                $query->select('no_of_days', 'id');
                                $resultfre = $query->first();
                                $next_due_date = date('Y-m-t', strtotime("+" . $resultfre->no_of_days . " months",
                                    strtotime(date("Y-m-d"))));
                            } else {
                                $next_due_date = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                            }

                            $query1 = DB::table('tbl_instrument_plan_log');
                            $query1->select('id');
                            $query1->where('equipment_id', $row['equipment_id']);
                            $query1->orderBy('id', 'DESC');
                            $result1 = $query1->first();
                            $log_id = (isset($result1->id) && $result1->id) ? $result1->id : '';

                            $saveitem['id'] = false;
                            $saveitem['service_request_id'] = $reqId;
                            $saveitem['due_equipments_id'] = $row['id'];
                            $saveitem['equipment_log_id'] = $log_id;
                            $saveitem['test_plan_id'] = $row['servicePlan'];
                            $saveitem['frequency_id'] = $row['frequency_id'];
                            $saveitem['comments'] = '';
                            $saveitem['service_price_id'] = $row['servicePriceId'];
                            $saveitem['created_date'] = date('Y-m-' . $end_month);
                            $this->servicerequest->saveItems($saveitem);
                            $savesEqu['id'] = $row['id'];
                            //$savesEqu['last_cal_date'] = date('Y-m-' . $end_month);
                            $savesEqu['calibrate_process'] = 1;
                            $this->dueequipments->saveDueequipments($savesEqu);
                            $savelog['id'] = false;
                            $savelog['request_id'] = $reqId;
                            $savelog['due_equipment_id'] = $row['id'];
                            $savelog['request_date'] = date('Y-m-d');
                            $savelog['call_date'] = date('Y-m-' . $end_month);
                            //$savelog['next_due_date'] = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                            $this->servicerequest->saveReqLog($savelog);

                        }

                        //quotation
                        $path = base_path() . '/public/qutation';

                        $items = array();
                        $totalQutationPrice = 0;
                        if (isset($input['model']) && $input['model']) {
                            $modelArray = $input['model'];
                            $key = 0;
                            foreach ($modelArray as $mkey => $mrow) {
                                $totalItemPrice = 0;
                                $get_model_id = DB::table('tbl_customer_service_model as sm')->where('sm.id', $mkey)
                                    ->join('tbl_equipment_model as em', 'em.id', '=', 'sm.model_id', 'left')
                                    ->join('tbl_brand as b', 'b.brand_id', '=', 'em.brand_id', 'left')
                                    ->join('tbl_manufacturer as m', 'm.manufacturer_id', '=', 'b.manufacturer_id', 'left')
                                    ->select('em.id', 'em.model_name', 'em.model_description', 'b.brand_name', 'm.manufacturer_name')
                                    ->first();
                                $model_id = isset($get_model_id->id) ? $get_model_id->id : '';
                                $model_name = isset($get_model_id->model_name) ? $get_model_id->model_name : '';
                                $model_description = isset($get_model_id->model_description) ? $get_model_id->model_description : '';
                                $manufacturer_name = isset($get_model_id->manufacturer_name) ? $get_model_id->manufacturer_name : '';
                                $brand_name = isset($get_model_id->brand_name) ? $get_model_id->brand_name : '';
                                $items[$key]['model_id'] = $model_id;
                                $items[$key]['model_name'] = $model_name;
                                $items[$key]['description'] = $model_description;
                                $items[$key]['brand_name'] = $brand_name;
                                $items[$key]['manufacturer_name'] = $manufacturer_name;
                                $items[$key]['quantity'] = count($mrow);
                                $tempsub = array();
                                foreach ($mrow as $skey => $srow) {
                                    $get_plan = DB::table('tbl_service_plan as sp')->where('sp.id', $srow['service_plan'])->select('service_plan_name')->first();
                                    $get_frequency = DB::table('tbl_frequency as f')->where('f.id', $srow['frequency'])->select('name')->first();
                                    $get_price = DB::table('tbl_service_pricing as p')->where('p.id', $srow['servicePriceId'])->select('price')->first();
                                    $plan = (isset($get_plan->service_plan_name) && $get_plan->service_plan_name) ? $get_plan->service_plan_name : '';
                                    $frequency = (isset($get_frequency->name) && $get_frequency->name) ? $get_frequency->name : '';
                                    $price = (isset($get_price->price) && $get_price->price) ? $get_price->price : '';
                                    $tempsub[$skey]['asset_no'] = $srow['asset_no'];
                                    $tempsub[$skey]['serial_no'] = $srow['serial_no'];
                                    $tempsub[$skey]['service_plan'] = $plan;
                                    $tempsub[$skey]['order_item_amt'] = $price;
                                    $tempsub[$skey]['model_description'] = $model_description;
                                    $totalQutationPrice += $price;
                                    $totalItemPrice += $price;

                                }
                                $items[$key]['lineItems'] = $tempsub;
                                $items[$key]['totalItemPrice'] = $totalItemPrice;
                                $key++;
                            }
                        }


                        $shipping_charge = isset($Shipping_Charge) ? $Shipping_Charge : 0;
//                        $service_tax = isset($input['service_tax']) ? $input['service_tax'] : 0;
                        $grand_total = $shipping_charge + $totalQutationPrice;

//                        $qutationPdfName = $getCustomer->customer_name . date('mdYHis');
                        $qutationPdfName = "NOVQUAT" . str_pad($input['serviceID'], 4, "0", STR_PAD_LEFT);

                        $paymentTerms = (isset($customerSetUps->pay_terms) && $customerSetUps->pay_terms) ? $customerSetUps->pay_terms : '';

                        $view['items'] = $items;
                        $view['customer'] = $getCustomer;
                        $view['billing'] = $bQuery;
                        $view['shipping'] = $sQuery;

                        $view['primaryContactName'] = $primaryContactName;
                        $view['prefContactName'] = $prefContactName;
                        $view['paymentTerms'] = $paymentTerms;

                        view()->share('data', $view);
                        view()->share('totalCost', $totalQutationPrice);
                        view()->share('shippingCharge', $shipping_charge);
//                        view()->share('serviceTax', $service_tax);
                        view()->share('GrandTotal', $grand_total);
                        view()->share('qutationnumber', $qutationPdfName);
                        view()->share('comments', 'Coming Soon');
                        view()->share('items', $items);
                        $pdf = app('dompdf.wrapper');
                        $pdf->getDomPDF()->set_option("enable_php", true);
                        $pdf->loadView('qutation.qutation')->save($path . '/' . $qutationPdfName . '.pdf', 'F');


                        $query = DB::table('tbl_email_template');
                        $query->where('tplid', '=', 10);
                        $result = $query->first();
                        $result->tplmessage = str_replace('{name}', $bQuery->billing_contact, $result->tplmessage);
                        $result->tplmessage = str_replace('{company_name}', $getCustomer->customer_name, $result->tplmessage);
                        $result->tplmessage = str_replace('{req_num}', "SERVICE0".$input['serviceID'], $result->tplmessage);
                        $param['message'] = $result->tplmessage;
                        $param['name'] = $getCustomer->customer_name;
                        $param['title'] = $result->tplsubject;
                        $pathToFile = base_path() . '/public/qutation/' . $qutationPdfName . '.pdf';

                        if ($group_user) {
                            if ($bQuery->email) {
                                Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $bQuery, $pathToFile) {
                                    $message->to($bQuery->email)->subject
                                    ($param['title']);
                                    $message->attach($pathToFile, ['as' => 'Quotation.pdf', 'mime' => 'pdf']);
                                });
                            }
                        }

                    }
                    die(json_encode(array('result' => true, 'value' => 2, 'reqId' => $reqId, 'message' => 'Successfully added')));

                } else {
                    $query = DB::table('tbl_email_template');
                    $query->where('tplid', '=', 13);
                    $result = $query->first();
                    $result->tplmessage = str_replace('{name}', $bQuery->billing_contact, $result->tplmessage);
                    $result->tplmessage = str_replace('{company_name}', $getCustomer->customer_name, $result->tplmessage);
                    $result->tplmessage = str_replace('{req_num}', "SERVICE0".$input['serviceID'], $result->tplmessage);
                    $param['message'] = $result->tplmessage;
                    $param['name'] = $getCustomer->customer_name;
                    $param['title'] = $result->tplsubject;
                    if ($group_user) {
                        if ($bQuery->email) {
                            Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $bQuery) {
                                $message->to($bQuery->email)->subject
                                ($param['title']);
                            });
                        }
                    }
                    die(json_encode(array('result' => true, 'value' => 1, 'message' => 'Successfully added')));

                }

//                }


            } else {
                die(json_encode(array('result' => false)));

            }


        } else {
            die(json_encode(array('result' => false)));

        }


    }
}
