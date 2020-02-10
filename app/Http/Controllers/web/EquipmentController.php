<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Customertype;
use App\Models\ServicePlan;
use Illuminate\Http\Request;
use App\Models\DueEquipments;
use App\Models\Equipment;
use App\Models\Channels;
use App\Models\Customer;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Excel;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\User as Modeluser;

//use Request;

class EquipmentController extends Controller
{

    public function __construct()
    {
        $this->equipment = new Equipment();
        $this->dueequipments = new DueEquipments();
        $this->customer = new Customer();
        $this->servicePlan = new ServicePlan();
        $this->channel = new Channels();
        $this->user = new Modeluser();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment';

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Choose customer','');
        //echo '<pre>';print_r($customerdrop);die;
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $customerId = isset($input['customerId']) ? $input['customerId'] : '0';

        if ($keyword != "" && $customerId != '0') {

            $datas['search']['keyword'] = $keyword;
            $datas['search']['key'] = $customerId;
            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'search' => $datas['search']), false, array('te.asset_no',
                'te.location', 'te.serial_no', 'te.pref_contact', 'te.pref_tel', 'te.pref_email', 'tem.model_name'));

        } elseif ($keyword != "") {
            $datas['search']['keyword'] = $keyword;

            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'search' => $datas['search']), false, array('te.asset_no',
                'te.location', 'te.serial_no', 'te.pref_contact', 'te.pref_tel', 'te.pref_email', 'tem.model_name'));
        } elseif ($customerId != '') {
            $datas['search']['keyword'] = $keyword;
            $datas['search']['key'] = $customerId;
            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'search' => $datas['search']), false, array('te.asset_no',
                'te.location', 'te.serial_no', 'te.pref_contact', 'te.pref_tel', 'te.pref_email', 'tem.model_name'));

            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select));
        }
        $temp = array();
        if ($data) {
            foreach ($data as $key => $value) {

                $temp[$key]['Id'] = $value->id;
                $temp[$key]['customerId'] = $value->customer_id;
                $temp[$key]['equipmentName'] = $value->name;
                $temp[$key]['assetNo'] = $value->asset_no;
                $temp[$key]['serialNo'] = $value->serial_no;
                $temp[$key]['modelName'] = $value->model_name;
                $temp[$key]['prefContact'] = $value->pref_contact;
                $CustomernameId = $value->customer_id;
                $getcustomer = $this->customer->getCustomer($CustomernameId);
                if ($getcustomer) {
                    $temp[$key]['customerName'] = $getcustomer->customer_name;
                } else {
                    $temp[$key]['customerName'] = '';
                }
            }
        }


//        $customerNames = json_encode(array_column($temp, 'customerName'));
//        $equipmentDetails = json_encode(array_column($temp, 'equipmentDetails'));
//
//        $chartdata = array();
//        foreach ($data as $chart) {
//
//            $customerId = $chart->customer_id;
//            $countdata = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'customerId' => $customerId));
//            if (!$countdata->isEmpty()) {
//                $count = count($countdata);
//            } else {
//                $count = 0;
//            }
//            $CustomernameId = $chart->customer_id;
//            $getcustomer = $this->customer->getCustomer($CustomernameId);
//            if ($getcustomer) {
//                $customerName = $getcustomer->customer_name;
//            } else {
//                $customerName = '';
//            }
//            $chartdata[] = array('customername' => $customerName, 'count' => (int)$count);
//        }
//
//        if (!$chartdata) {
//            $jsonvalue = '[{"y":"2017 - Aug","a":0},{"y":"2017 - Jul","a":0},{"y":"2017 - Jun","a":0},{"y":"2017 - Sep","a":0}]';
//        } else {
//            $jsonvalue = json_encode($chartdata);
//
//        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($temp, count($temp), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());


        return view('equipment.equipmentlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword)->with('customer', $customer)->with('customerId', $customerId);
    }

    public function listData(Request $request, $customer_id = false)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['te.asset_no'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['te.serial_no'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['tem.model_description'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['te.location'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['te.pref_contact'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['te.pref_tel'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['te.pref_email'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';
//        print_r($search);die;

        $select = array('te.*', 'td.as_found', 'tc.customer_name', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name', 'tem.model_description');
        $data = $this->dueequipments->AllEquipmentsGrid($param['limit'], $param['offset'], 'te.id', 'DESC', array('select' => $select, 'search' => $search, 'customer' => $customer_id), false, array('tc.name', 'tc.description'));
        $count = $this->dueequipments->AllEquipmentsGrid('', '', 'te.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true, 'customer' => $customer_id), true, array('tc.name', 'tc.description'));

//echo '<pre>';print_r($data);exit;
        if ($data) {
            $values = array();
            $i = 0;
            $sNo = $param['offset'] ? $param['offset'] : $param['offset'] + 1;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $sNo;
                $values[$key]['1'] = $row->asset_no;
                $values[$key]['2'] = $row->serial_no;
                $values[$key]['3'] = $row->model_description;
                $values[$key]['4'] = $row->location;
                $values[$key]['5'] = $row->pref_contact;
                $values[$key]['6'] = $row->pref_tel;
                $values[$key]['7'] = $row->pref_email;
                $values[$key]['8'] = "<a href=" . url("admin/editEquipment/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['9'] = "<a href='javascript:void(0)' data-src=" . url('admin/deleteInstrument/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
                $sNo++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        if ($customer_id) {
            echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));
        } else {
            echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => array()));
        }


    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment Creation';

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');

        $frequency = DB::table('tbl_frequency')->pluck('name', 'id');
        //$frequency->prepend('Please Select Frequency', '');

        $modelname_drop = array();
//        $model = '';
//        $space_left_model = str_replace(' ','',$model);
//        $modelname = DB::table('tbl_equipment_model')
//            ->whereRaw('REPLACE(`model_description`," ","") = ?',$space_left_model)->where('is_active','=',1)->get();
        $modelname = DB::table('tbl_equipment_model')->select('model_description', 'id')->where('is_active', '=', 1)->get();
        $modelname_drop[0] = 'Please Choose the Model';
        if (count($modelname)) {
            foreach ($modelname as $key => $row) {
                $modelname_drop[$row->id] = $row->model_description;
            }
        }
//        echo '<pre>';print_R($modelname);exit;
//        $modelname = DB::table('tbl_equipment_model')->pluck('model_name', 'id');
//        $modelname->prepend('Please Choose the Model', '');

        $servicePlanSelect = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->get();
//        $servicePlanSelect->prepend('Please Choose the Service Plan', '');
        $pricetemp = array();
        $pref_contact = array();


        $status = array('' => 'Select Status', '1' => 'Passed', '2' => 'Fail', '3' => 'New');
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'planName' => isset($input['planName']) ? $input['planName'] : '',
            'customerId' => isset($input['customerId']) ? $input['customerId'] : '',
            'serial_no' => isset($input['serial_no']) ? $input['serial_no'] : '',
            'as_found' => isset($input['as_found']) ? $input['as_found'] : '',
            'lastDate' => isset($input['lastDate']) ? $input['lastDate'] : '',
            'pref_tel' => isset($input['pref_tel']) ? $input['pref_tel'] : '',
            'pref_email' => isset($input['pref_email']) ? $input['pref_email'] : '',
            'frequency' => isset($input['frequency']) ? $input['frequency'] : '',
            'freqDate' => isset($input['freqDate']) ? $input['freqDate'] : '',
            'assetno' => isset($input['assetno']) ? $input['assetno'] : '',
            'modelId' => isset($input['modelId']) ? $input['modelId'] : '',
            'as_calibrate' => isset($input['as_calibrate']) ? $input['as_calibrate'] : '',
            'frequencyId' => isset($input['frequencyId']) ? $input['frequencyId'] : '',
            'location' => isset($input['location']) ? $input['location'] : '',
            'pref_contact' => isset($input['pref_contact']) ? $input['pref_contact'] : '',
            'nextDate' => isset($input['nextDate']) ? $input['nextDate'] : '',
//            'nextDateUp' => isset($input['nextDateUp']) ? $input['nextDateUp'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];
        if ($id) {
            $getequipmet = $this->dueequipments->getequipments($data['id']);

            $getModel = $this->equipment->getmodel($getequipmet->equipment_model_id);
            $channel = $getModel->channel_number;
//            echo '<pre>';print_r($getModel);exit;
            if ($getequipmet) {
                $customerId = $getequipmet->customer_id;
                $getCustomer = $this->customer->getCustomer($customerId, true);
                $data['customerName'] = $getCustomer->customer_name;
                $data['customerType'] = $getCustomer->name;
                $data['customerMail'] = $getCustomer->customer_email;
            } else {
                $data['customerName'] = '';
                $data['customerType'] = '';
                $data['customerMail'] = '';
            }
            $getvalue = $this->dueequipments->getvalues($data['id']);
//            echo '<pre>';print_R($getvalue);exit;
            $getInstrumentLogDetails = $this->equipment->getInstrumentDetails($data['id']);
            if (!$getequipmet) {
                return redirect('admin/viewlist')->with('message', 'Sorry! Details are not found.');
            } else {

                $data['id'] = $getequipmet->id;
                $data['name'] = $getequipmet->name;
                $data['description'] = $getequipmet->description;
                $data['assetno'] = $getequipmet->asset_no;
                $data['planName'] = $getequipmet->plan_id;
                $data['serial_no'] = $getequipmet->serial_no;
                $data['modelId'] = $getequipmet->equipment_model_id;
                $data['customerId'] = $getequipmet->customer_id;

                $servicePlan = DB::table('tbl_customer_setups')->select('plan_id', 'id')->where('customer_id', '=', $customerId)->first();
                if ($servicePlan) {
                    $service_plan = explode(',', $servicePlan->plan_id);
                }
                $servicePlanSelect = array();
//                $serviceelement = '<div>';
                foreach ($service_plan as $row) {
                    $service_Plan_select = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->where('id', $row)->first();
                    $servicePlanSelect[$service_Plan_select->id] = $service_Plan_select->service_plan_name;
//                    $serviceelement .='<div class="am-radio inline"> <input type="radio" name="planName" class="planName"data-id="'.$service_Plan_select->id.'" id="plan_'.$service_Plan_select->id.'"  value="'.$service_Plan_select->id.'"/'.$service_Plan_select->service_plan_name.'><label for="plan_'.$service_Plan_select->id.'">'.$service_Plan_select->service_plan_name.'</label></div>';
                }

//                $serviceelement .= '</div>';
//                $servicePlanSelect =$serviceelement;
//                echo '<pre>'; print_r($getvalue->exact_date);exit;
                if ($getvalue) {
                    $data['frequency'] = isset($getvalue->frequency_id) ? $getvalue->frequency_id : '';
                    if($getvalue->exact_date != '0000-00-00'){
                        $data['freqDate'] = Carbon::parse($getvalue->exact_date)->format('d/m/Y');
                    }else{
                        $data['freqDate'] = '';
                    }
                }

                $data['customerAddress'] = $getCustomer->address1;
                $data['customerTelephone'] = $getCustomer->customer_telephone;
                $getServicePricing = $this->servicePlan->chooseServicePricing($getequipmet->plan_id, $channel);

//                echo "<pre>";print_r($getServicePricing);die;
                if (!$getServicePricing->isEmpty()) {
                    foreach ($getServicePricing as $pricekey => $priceval) {
                        $pricetemp[$pricekey]['servicePriceId'] = $priceval->id;
                        $pricetemp[$pricekey]['plan_id'] = $priceval->plan_id;
                        $getPlan = $this->servicePlan->getPlan($pricetemp[$pricekey]['plan_id']);
                        if ($getPlan) {
                            $pricetemp[$pricekey]['planName'] = $getPlan->service_plan_name;
                        } else {
                            $pricetemp[$pricekey]['planName'] = '';
                        }

                        $pricetemp[$pricekey]['operation'] = $priceval->operation;
                        $getOperation = $this->servicePlan->getOperations($pricetemp[$pricekey]['operation']);
                        $pricetemp[$pricekey]['opertionValue'] = $getOperation->operation_name;
                        $pricetemp[$pricekey]['channel'] = $priceval->channel;
                        $channelPoint = $priceval->channel_point;
                        $getChannelPointVal = $this->channel->getchannelPoint($channelPoint);
                        $pointName = $getChannelPointVal->point_name;
                        $getChannelNumbers = $this->channel->getchannelNumber($pointName);
                        $pricetemp[$pricekey]['channelNumber'] = $getChannelNumbers->channel_number;
                        $getchannel = $this->servicePlan->getChannel($pricetemp[$pricekey]['channel']);
                        $pricetemp[$pricekey]['channelValue'] = $getchannel->channel_name;
                        $pricetemp[$pricekey]['price'] = $priceval->price;
                    }
                }


                $getSelectedPricing = $this->equipment->getSelectedServicePricing($data['id']);
//                echo '<pre>';print_r($getSelectedPricing);die;
                if ($getSelectedPricing) {
                    $data['servicePricingId'] = $getSelectedPricing->pricing_criteria_id;
                    $data['customerId'] = $getequipmet->customer_id;
                    $data['location'] = $getequipmet->location;
                    //$data['pref_contact'] = $getequipmet->pref_contact;
                    $data['pref_tel'] = $getequipmet->pref_tel;
                    $data['pref_email'] = $getequipmet->pref_email;
                    $data['pref_contact'] = $getequipmet->pref_contact_id;
                    $data['is_active'] = $getequipmet->is_active;
                    $data['as_found'] = $getvalue->as_found;
                    $data['as_calibrate'] = $getvalue->as_calibrate;
                    $data['frequencyId'] = $getvalue->frequency_id;
                    if($getvalue->last_cal_date != ''){
                        $data['lastDate'] = Carbon::parse($getvalue->last_cal_date)->format('d/m/Y');
                    }else{
                        $data['lastDate'] = '';
                    }
                    $data['nextDate'] = Carbon::parse($getvalue->next_due_date)->format('d/m/Y');
//                    $data['nextDateUp'] = Carbon::parse($getvalue->next_due_date_up)->format('d/m/Y');
                } else {

                    $data['servicePricingId'] = '';
                }


            }

            $pref_contact = DB::table('tbl_users')->where('customer_id', $getequipmet->customer_id)->pluck('name', 'id');
            $pref_contact->prepend('Select Preferred Contact', '');
        }


        $rules = [
            'name' => 'required',
            'assetno' => 'required',
        ];
        $error = array();
        $equipmentDetail = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($data, $rules);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();

            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }

//        echo '<pre>';print_r($data);exit;
        if ($checkStatus) {
            return view('equipment.equipmentform')->with('equipmentDetail', $equipmentDetail)->with('title', $title)->with('getServicePricing', $pricetemp)->with('input', $data)->with('servicePlanSelect', $servicePlanSelect)->with('customer', $customer)->with('modelname', $modelname_drop)->with('status', $status)->with('frequency', $frequency)->with('pref_contact', $pref_contact);
        } else {
            $data = Input::all();

            return redirect('admin/viewlist')->with('message', 'Added Successfully!');
        }
    }


    public function Excelsheet($url)
    {
        switch ($url) {
            case '0':
                $customerdetails = DueEquipments::join('tbl_due_equipments as td', 'td.equipment_id', '=', 'tbl_equipment.id')
                    ->select('tbl_equipment.name', 'tbl_equipment.asset_no', 'tbl_equipment.serial_no', 'tbl_equipment.customer_id', 'tbl_equipment.location', 'tbl_equipment.pref_contact', 'tbl_equipment.pref_tel', 'tbl_equipment.pref_email', 'tbl_equipment.equipment_model_id', 'td.equipment_id')
                    ->get();
                // Initialize the array which will be passed into the Excel
                // generator.
                $customerdetailsArray = [];

                // Define the Excel spreadsheet headers
                $customerdetailsArray[] = ['CustomerName', 'Asset', 'Serial', 'Model', 'Location', 'Pre Contact', 'Pre Tel', 'Pre Email'];

                // Convert each member of the returned collection into an array,

                foreach ($customerdetails as $row) {
                    $customerId = $row['customer_id'];
                    $getcustomer = $this->customer->getCustomer($customerId);
                    if ($getcustomer) {
                        $temp['customername'] = $getcustomer->customer_name;
                    } else {
                        $temp['customername'] = '';
                    }
                    $temp['asset_no'] = $row['asset_no'];
                    $temp['serial_no'] = $row['serial_no'];
                    $modelId = $row['equipment_model_id'];
                    $getmodel = $this->equipment->getmodel($modelId);
                    if ($getmodel) {

                        $temp['modelname'] = $getmodel->model_name;
                    } else {
                        $temp['modelname'] = '';
                    }
                    $temp['location'] = $row['location'];
                    $temp['pref_contact'] = $row['pref_contact'];
                    $temp['pref_tel'] = $row['pref_tel'];
                    $temp['pref_email'] = $row['pref_email'];

                    $customerdetailsArray[] = $temp;
                }
                // Generate and return the spreadsheet
                \Excel::create("Add/view list", function ($excel) use ($customerdetailsArray) {

                    $excel->setTitle('Add/view list');
                    $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
                    $excel->setDescription('view List File');

                    // Build the spreadsheet, passing in the payments array
                    $excel->sheet('sheet1', function ($sheet) use ($customerdetailsArray) {
                        $sheet->fromArray($customerdetailsArray, null, 'A1', false, false);
                    });
                })->download('csv');
                break;
            case (!0):

                $customerId = $url;
                $customerdetails = DueEquipments::join('tbl_due_equipments as td', 'td.equipment_id', '=', 'tbl_equipment.id')->where('tbl_equipment.customer_id', '=', $customerId)
                    ->select('tbl_equipment.name', 'tbl_equipment.asset_no', 'tbl_equipment.serial_no', 'tbl_equipment.customer_id', 'tbl_equipment.location', 'tbl_equipment.pref_contact', 'tbl_equipment.pref_tel', 'tbl_equipment.pref_email', 'tbl_equipment.equipment_model_id', 'tbl_equipment.customer_id', 'td.equipment_id')
                    ->get();
//       echo'<pre>'; print_r($customerdetails);die;

                // Initialize the array which will be passed into the Excel
                // generator.
                $customerdetailsArray = [];

                // Define the Excel spreadsheet headers
                $customerdetailsArray[] = ['CustomerName', 'Asset', 'Serial', 'Model', 'Location', 'Pre Contact', 'Pre Tel', 'Pre Email'];

                // Convert each member of the returned collection into an array,

                foreach ($customerdetails as $row) {

                    $customerId = $row['customer_id'];
                    $getcustomer = $this->customer->getCustomer($customerId);
                    if ($getcustomer) {

                        $temp['customername'] = $getcustomer->customer_name;
                    } else {
                        $temp['customername'] = '';
                    }
                    $temp['asset_no'] = $row['asset_no'];
                    $temp['serial_no'] = $row['serial_no'];
                    $modelId = $row['equipment_model_id'];
                    $getmodel = $this->equipment->getmodel($modelId);
                    if ($getmodel) {

                        $temp['modelname'] = $getmodel->model_name;
                    } else {
                        $temp['modelname'] = '';
                    }
                    $temp['location'] = $row['location'];
                    $temp['pref_contact'] = $row['pref_contact'];
                    $temp['pref_tel'] = $row['pref_tel'];
                    $temp['pref_email'] = $row['pref_email'];

                    $customerdetailsArray[] = $temp;
                }
                // Generate and return the spreadsheet
                \Excel::create("Add/view list", function ($excel) use ($customerdetailsArray) {

                    $excel->setTitle('Add/view list');
                    $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
                    $excel->setDescription('view List File');

                    // Build the spreadsheet, passing in the payments array
                    $excel->sheet('sheet1', function ($sheet) use ($customerdetailsArray) {
                        $sheet->fromArray($customerdetailsArray, null, 'A1', false, false);
                    });
                })->download('csv');

                break;
            default:
                return false;
                break;
        }


    }

    public function addcustomerEquipment()
    {
        $data = Input::all();


        $loginuserId = Sentinel::getUser()->id;


        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get Properly')));
        }


        if ($data) {

//echo'<pre>';print_r($data);exit;
            $save = array();
            $save['id'] = isset($data['Id']) ? $data['Id'] : '';
            $save['name'] = $data['name'];
            $save['description'] = $data['description'];
            $save['asset_no'] = $data['assetno'];
            $save['serial_no'] = $data['serial_no'];
            $save['equipment_model_id'] = $data['modelId'];
            $save['customer_id'] = $data['customerId'];
            $save['location'] = $data['location'];
            $save['pref_tel'] = $data['pref_tel'];
            $save['plan_id'] = $data['planName'];
            $save['pref_email'] = $data['pref_email'];

            if (isset($data['assetno']) && $data['assetno']) {
                $asset_no_exist = $this->dueequipments->getAssetNumber($data['assetno'], $data['customerId'], $data['Id']);
                if ($asset_no_exist) {
                    die(json_encode(array('result' => false, 'message' => 'Asset Number Already Exist')));
                }
            }
            if (isset($data['serial_no']) && $data['serial_no']) {
                $serial_no_exist = $this->dueequipments->getSerialNumber($data['serial_no'], $data['customerId'], $data['Id']);
                if ($serial_no_exist) {
                    die(json_encode(array('result' => false, 'message' => 'Serial Number Already Exist')));
                }
            }


            if (isset($data['pref_contact']) && $data['pref_contact']) {
                $pref_contact_id = $data['pref_contact'];
                $pref_contact_user = $this->user->getUserDetailData($pref_contact_id);
                if ($pref_contact_user) {
                    $pref_contact_name = isset($pref_contact_user->name) ? $pref_contact_user->name : '';
                } else {
                    $pref_contact_name = '';
                }

            } else {
                $pref_contact_name = '';
            }

            $save['pref_contact'] = $pref_contact_name;
            $save['pref_contact_id'] = isset($data['pref_contact']) ? $data['pref_contact'] : '';

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }

//echo '<pre>';print_r($save);exit;
            $Saveresult = $this->dueequipments->saveEquipments($save);

//            get equipment
//            $deleteEquipment = $this->equipment->deleteDueEquipment($Saveresult);

//            if (!$data['Id']) {
            if ($data['calFrequency'] == 1) {
                $inputsave['frequency_id'] = $data['frequency'];
            } else {
                $exactDate = str_replace('/', '-', $data['freqDate']);
                $inputsave['exact_date'] = date('Y-m-d', strtotime($exactDate));
            }
            if (!$data['Id']) {
                $inputsave['id'] = false;
            } else {
                $getDueEquipment = $this->dueequipments->getvalues($data['Id']);
//                    echo '<pre>';print_r($getDueEquipment);die;
                if ($getDueEquipment) {
                    $inputsave['id'] = $getDueEquipment->id;
                } else {
                    $inputsave['id'] = false;
                }
            }

            $startdates = str_replace('/', '-', $data['lastDate']);
            $enddates = str_replace('/', '-', $data['nextDate']);

//            $nextduedateup = str_replace('/', '-', $data['nextDateUp']);
//            $inputsave['last_cal_date'] = date('Y-m-d', strtotime($startdates));
//            $inputsave['next_due_date'] = date('Y-m-d', strtotime($enddates));
//            $inputsave['next_due_date_up'] = date('Y-m-d', strtotime($nextduedateup));
            if($data['lastDate'] != ''){
                $inputsave['last_cal_date'] = date('Y-m-d', strtotime(str_replace('-', '/',$data['lastDate'])));
            }else{
                $inputsave['last_cal_date'] = null;
            }
//            $inputsave['last_cal_date'] = date('Y-m-d', strtotime(str_replace('-', '/',$data['lastDate'])));
            $inputsave['next_due_date'] = date('Y-m-d', strtotime(str_replace('-', '/',$data['nextDate'])));
//            $inputsave['next_due_date_up'] = date('Y-m-d', strtotime(str_replace('-', '/',$data['nextDateUp'])));


            $inputsave['equipment_id'] = $Saveresult;
            $inputsave['frequency_id'] = $data['frequency'];
            if($data['freqDate']){
                $inputsave['exact_date'] = date('Y-m-d', strtotime(str_replace('-', '/',$data['freqDate'])));;
            }else{
                $inputsave['exact_date'] = '';
            }
            $inputsave['as_found'] = $data['as_found'];
            $inputsave['as_found'] = $data['as_found'];
            $inputsave['as_calibrate'] = $data['as_calibrate'];

//            echo'<pre>';print_r($inputsave);exit;
            $inputresult = $this->dueequipments->saveDueequipments($inputsave);
//            }


////            save instrument detail tab
//            $deleteEquipment = $this->equipment->deleteInstrumentDetails($Saveresult);

            $instrumentDetail['id'] = false;
            $instrumentDetail['equipment_id'] = $Saveresult;
            $instrumentDetail['plan_name'] = $data['finalplanName'];
            $instrumentDetail['no_of_channels'] = $data['channelNumber'];
            $instrumentDetail['operation_method'] = $data['operation'];
            $instrumentDetail['price'] = $data['price'];
            $instrumentDetail['plan_id'] = $data['planName'];
            $instrumentDetail['pricing_criteria_id'] = $data['servicePricingId'];
            $instrumentDetail['created_by'] = $loginuserId;

            $deleteEquipment = $this->equipment->saveInstrumentDetails($instrumentDetail);


            $instrumentDetailLog['id'] = false;
            $instrumentDetailLog['equipment_id'] = $Saveresult;
            $instrumentDetailLog['plan_name'] = $data['finalplanName'];
            $instrumentDetailLog['no_of_channels'] = $data['channelNumber'];
            $instrumentDetailLog['operation_method'] = $data['operation'];
            $instrumentDetailLog['price'] = $data['price'];
            $instrumentDetailLog['plan_id'] = $data['planName'];
            $instrumentDetailLog['pricing_criteria_id'] = $data['servicePricingId'];

            $instrumentDetailLog['created_by'] = $loginuserId;

            $deleteEquipment = $this->equipment->saveInstrumentLogDetails($instrumentDetailLog);


            die(json_encode(array('result' => true, 'message' => 'Successfully Added!')));
        } else {
            die(json_encode(array('result' => false, 'message' => 'Details not found')));
        }


    }

    public function getCustomerEquipment()
    {
        $input = Input::all();


        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get Properly')));
        }
        $customerId = $input['customerId'];
        $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
        $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'customerId' => $customerId));

        $count = 0;
        if (!$data->isEmpty()) {
            $count = count($data);
            foreach ($data as $key => $value) {
                $temp[$key]['name'] = $value->name;
                $temp[$key]['customerId'] = $value->customer_id;
                $temp[$key]['plan_id'] = $value->plan_id;
                $getPlan = $this->servicePlan->getPlan($temp[$key]['plan_id']);
                if ($getPlan) {
                    $temp[$key]['planName'] = $getPlan->service_plan_name;
                } else {
                    $temp[$key]['planName'] = '';
                }

                $temp[$key]['asset_no'] = $value->asset_no;
                $temp[$key]['serial_no'] = $value->serial_no;

            }
            die(json_encode(array('result' => true, 'data' => $temp, 'count' => $count)));
        } else {
            die(json_encode(array('result' => true, 'message' => 'Sorry! Details are not found')));
        }

    }

    public function editEquipment(Request $request, $customerId)
    {

        $input = Input::all();
        $title = 'Novamed-Equipment Creation';

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');

        $frequency = DB::table('tbl_frequency')->pluck('no_of_days', 'id');
        $frequency->prepend('-Select-', '');

        $modelname = DB::table('tbl_equipment_model')->pluck('model_name', 'id');
        $modelname->prepend('Please Choose the Model', '');

        $servicePlanSelect = DB::table('tbl_service_plan')->pluck('service_plan_name', 'id');
        $servicePlanSelect->prepend('Please Choose the Service Plan', '');


        $status = array('' => 'Select Status', '1' => 'Passed', '2' => 'Fail', '3' => 'New');
        $data = [

            'name' => isset($input['name']) ? $input['name'] : '',
            'id' => isset($input['id']) ? $input['id'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'planName' => isset($input['planName']) ? $input['planName'] : '',
            'customerId' => $customerId,
            'serial_no' => isset($input['serial_no']) ? $input['serial_no'] : '',
            'as_found' => isset($input['as_found']) ? $input['as_found'] : '',
            'lastDate' => isset($input['lastDate']) ? $input['lastDate'] : '',
            'pref_tel' => isset($input['pref_tel']) ? $input['pref_tel'] : '',
            'pref_email' => isset($input['pref_email']) ? $input['pref_email'] : '',
            'assetno' => isset($input['assetno']) ? $input['assetno'] : '',
            'modelId' => isset($input['modelId']) ? $input['modelId'] : '',
            'as_calibrate' => isset($input['as_calibrate']) ? $input['as_calibrate'] : '',
            'frequencyId' => isset($input['frequencyId']) ? $input['frequencyId'] : '',
            'location' => isset($input['location']) ? $input['location'] : '',
            'pref_contact' => isset($input['pref_contact']) ? $input['pref_contact'] : '',
            'nextDate' => isset($input['nextDate']) ? $input['nextDate'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];
        if ($customerId) {
            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $getEquipmentByCustomer = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'customerId' => $customerId));

//echo '<pre>';print_r($getEquipmentByCustomer);exit;
            $totalequipmentDetail = 0;

            if ($getEquipmentByCustomer->isEmpty()) {
                return redirect('admin/viewlist')->with('message', 'Sorry! Details are not found.');
            } else {


                $getCustomer = $this->customer->getCustomer($customerId, true);

                if ($getCustomer) {
                    $data['customerName'] = $getCustomer->customer_name;
                    $data['customerType'] = $getCustomer->name;
                    $data['customerMail'] = $getCustomer->customer_email;
                    $data['customerAddress'] = $getCustomer->address1;
                }
                if ($getEquipmentByCustomer) {
                    $totalequipmentDetail = count($getEquipmentByCustomer);
                    foreach ($getEquipmentByCustomer as $equipkey => $equipvalue) {
                        $equipmentDetail[$equipkey]['saveId'] = $equipvalue->id;
                        $equipmentDetail[$equipkey]['equipmentName'] = $equipvalue->name;
                        $equipmentDetail[$equipkey]['Description'] = $equipvalue->description;
                        $equipmentDetail[$equipkey]['planval'] = $equipvalue->plan_id;

                        $getPlan = $this->servicePlan->getPlan($equipmentDetail[$equipkey]['planval']);
                        if ($getPlan) {
                            $equipmentDetail[$equipkey]['planName'] = $getPlan->service_plan_name;
                        } else {
                            $equipmentDetail[$equipkey]['planName'] = '';
                        }
                        $equipmentDetail[$equipkey]['asset'] = $equipvalue->asset_no;
                        $equipmentDetail[$equipkey]['serialNo'] = $equipvalue->serial_no;
                        $equipmentDetail[$equipkey]['modelValue'] = $equipvalue->equipment_model_id;
                        $equipmentDetail[$equipkey]['modelText'] = $equipvalue->model_name;
                        $equipmentDetail[$equipkey]['customerId'] = $equipvalue->customer_id;
                        $equipmentDetail[$equipkey]['prefContact'] = $equipvalue->pref_tel;
                        $equipmentDetail[$equipkey]['prefContact'] = $equipvalue->pref_contact;
                        $equipmentDetail[$equipkey]['prefEmail'] = $equipvalue->pref_email;
                        $equipmentDetail[$equipkey]['location'] = $equipvalue->location;
                        $equipmentDetail[$equipkey]['isActive'] = $equipvalue->is_active;
                        $equipmentDetail[$equipkey]['asfoundValue'] = $equipvalue->as_found;
                        if ($equipmentDetail[$equipkey]['asfoundValue'] == 1) {
                            $equipmentDetail[$equipkey]['asfoundText'] = 'Passed';
                        } elseif ($equipmentDetail[$equipkey]['asfoundValue'] == 2) {
                            $equipmentDetail[$equipkey]['asfoundText'] = 'Failed';
                        } elseif ($equipmentDetail[$equipkey]['asfoundValue'] == 3) {
                            $equipmentDetail[$equipkey]['asfoundText'] = 'New';
                        } else {
                            $equipmentDetail[$equipkey]['asfoundText'] = '';
                        }

                        $equipmentDetail[$equipkey]['asCalibrateValue'] = $equipvalue->as_calibrate;
                        if ($equipmentDetail[$equipkey]['asCalibrateValue'] == 1) {
                            $equipmentDetail[$equipkey]['asCalibrateText'] = 'Passed';
                        } elseif ($equipmentDetail[$equipkey]['asCalibrateValue'] == 2) {
                            $equipmentDetail[$equipkey]['asCalibrateText'] = 'Failed';
                        } elseif ($equipmentDetail[$equipkey]['asCalibrateValue'] == 3) {
                            $equipmentDetail[$equipkey]['asCalibrateText'] = 'New';
                        } else {
                            $equipmentDetail[$equipkey]['asCalibrateText'] = '';
                        }
                        $equipmentDetail[$equipkey]['lastDate'] = $equipvalue->last_cal_date;
                        $equipmentDetail[$equipkey]['nextDate'] = $equipvalue->next_due_date;

                    }
                }
            }
        }

        $rules = [
            'name' => 'required',
            'assetno' => 'required',
        ];
        $error = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($data, $rules);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }

//        echo '<pre>';print_r($data);die;
        if ($checkStatus) {
            return view('equipment.equipmentform')->with('title', $title)->with('equipmentDetail', $equipmentDetail)->with('totalequipmentDetail', $totalequipmentDetail)->with('input', $data)->with('servicePlanSelect', $servicePlanSelect)->with('customer', $customer)->with('modelname', $modelname)->with('status', $status)->with('frequency', $frequency);
        } else {
            $data = Input::all();

            return redirect('admin/viewlist')->with('message', 'Added Successfully!');
        }


    }

    public function showPricing(Request $request)
    {

        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Details are not found')));
        }
        $modelId = $input['modelId'];
        if ($modelId) {
            $getModel = $this->equipment->getmodel($modelId);
            if (!$getModel) {
                die(json_encode(array('result' => false, 'message' => 'Details are not found')));
            }
            $planId = $input['planId'];
            $channel = $getModel->channel_number;
            $getServicePricing = $this->servicePlan->chooseServicePricing($planId, $channel);
//            echo '<pre>';print_r($getServicePricing);die;
            if ($getServicePricing->isEmpty()) {
                die(json_encode(array('result' => false, 'message' => 'Service Pricing is not found')));
            }
            $temp = array();

            if (!$getServicePricing->isEmpty()) {

                foreach ($getServicePricing as $servicekey => $servicerow) {
                    $temp[$servicekey]['servicePriceId'] = $servicerow->id;
                    $temp[$servicekey]['plan_id'] = $servicerow->plan_id;
                    $getPlan = $this->servicePlan->getPlan($temp[$servicekey]['plan_id']);
                    $temp[$servicekey]['planName'] = $getPlan->service_plan_name;
                    $temp[$servicekey]['operation'] = $servicerow->operation;
                    $getOperation = $this->servicePlan->getOperations($temp[$servicekey]['operation']);
                    $temp[$servicekey]['opertionValue'] = $getOperation->operation_name;
                    $temp[$servicekey]['channel'] = $servicerow->channel;
                    $channelPoint = $servicerow->channel_point;
                    $getChannelPointVal = $this->channel->getchannelPoint($channelPoint);
                    $pointName = $getChannelPointVal->point_name;
                    $getChannelNumbers = $this->channel->getchannelNumber($pointName);
                    $temp[$servicekey]['channelNumber'] = $getChannelNumbers->channel_number;
                    $getchannel = $this->servicePlan->getChannel($temp[$servicekey]['channel']);
                    $temp[$servicekey]['channelValue'] = $getchannel->channel_name;
                    $temp[$servicekey]['price'] = $servicerow->price;

                }
            }

//            echo '<pre>';print_r($temp);die;

            die(json_encode(array('result' => true, 'message' => 'Details found', 'data' => $temp)));

        }

    }


    public
    function delete($id)
    {

        $getdetail = $this->equipment->getEquipment($id);

        if ($getdetail) {

//            $getSubdetail = DB::table('tbl_equipment_model')->where('brand_id','=',$id)->select('*')->first();
//            if($getModel){
//                $message = Session::flash('error', "You can't able to delete this brand");
//                return redirect('admin/devicelist')->with(['data', $message], ['message', $message]);
//            }

            $service_items = DB::table('tbl_service_request_item as ri')->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
                ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
                ->where('e.id', '=', $id)->select('*')->first();
            if ($service_items) {
                $message = 'This Equipment having service request item. You cannot delete';

                return redirect('admin/viewlist')->with(['data', $message], ['message', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully!');
            $member = $this->equipment->deleteEquipment($id);
            $member = $this->equipment->deleteDueEquipment($id);

            return redirect('admin/viewlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/viewlist')->with('data', $error);
        }
    }

    function getUserContact(Request $request)
    {
        $post = Input::all();
        if ($post) {
            $contact_user_id = isset($post['user_contact_id']) ? $post['user_contact_id'] : '';
            if ($contact_user_id) {
                $contact_user = DB::table('tbl_users')->where('id', $contact_user_id)->first();
                if ($contact_user) {
                    $location = $contact_user->location;
                    $email = $contact_user->email;
                    $phone = $contact_user->telephone;

                } else {
                    $location = '';
                    $email = '';
                    $phone = '';
                }

            } else {
                $location = '';
                $email = '';
                $phone = '';
            }

            die(json_encode(array('result' => true, 'location' => $location, 'email' => $email, 'phone' => $phone)));

        }

    }

    function uploadPage()
    {
        $input = Input::all();
        $title = 'Upload Instruments';

        $customers = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customers->prepend('Choose customer', '');

        return view('equipment.uploadPage')->with('customer', $customers)->with('title', $title)->with('message', '');
    }

//    public function parseImport(Request $request)
//    {
//            $title = 'Upload Instruments';
//            $path = Input::file('csv_file');
//            $data = \Excel::load($path)->get();
//            $post = Input::all();
//            $customers = DB::table('tbl_customer')->pluck('customer_name','id');
//            $customers->prepend('Choose customer','');
//
//            # Validate the file
//
//            $fileformat = $path->getClientOriginalExtension();
//            if ($fileformat != 'xlsx')
//            {
//                $message = 'Unsupported File Format.Upload Only Excel File';
//                return view('equipment/uploadPage')->with('title', $title)->with('customer', $customers)->with('message', $message);
//            }
//            if ($data->count())
//            {
//                $model = '';
//             foreach ($data as $key => $value)
//             {
//                 foreach ($value as $val)
//                 {
//                     $arr[] = ['asset_no' => $val->asset_no,
//                         'serial_no' => $val->serial_no,
//                         'model' => $val->model,
//                         'contact' => $val->tel,
//                         'location' => $val->location,
//                         'service_plan' => $val->service_plan,
//                         'last_cal_date' => $val->last_cal_date,
//                         'as_found' => $val->as_found,
//                         'as_cal' => $val->as_cal,
//                         'next_due_date' => $val->next_due_date,
//                     ];
//                 }
//
//             }  //echo '<pre>';print_r($arr);die;
//
//             if(count($arr))
//             {
//
//                 $insData = array();
//                 $insFailData = array();
//                 foreach ($arr as $key=>$row)
//                 {
//                     if($row['model'] && $row['service_plan'])
//                     {
//                         $model = $row['model'];
//                         $checkModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',str_replace(' ','',$model))->first(); //echo '<pre>';print_r($checkModel);die;
//                         $plan = $row['service_plan'];
//                         $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?',str_replace(' ','',$plan))->first(); //echo '<pre>';print_r($checkPlan);die;
//
//                         if($checkModel&&$checkPlan)
//                         {
//                             //check asset num duplication
//                             $checkAsset=array();
//                             if($row['asset_no'])
//                             {
//                                 $checkAsset = DB::table('tbl_equipment')->whereRaw('REPLACE(`asset_no`," ","") = ?',str_replace(' ','',$row['asset_no']))->where('customer_id',$post['customer'])->first();
//                             }
//                             if($checkAsset)
//                             {
//                                 $insFailData[$key] = $row;
//                                 $insFailData[$key]['failure_reason'] = 'Asset number is duplicated';
//                             }
//                             else
//                             {
//                                 $insData[$key] = $row;
//                                 $insData[$key]['model_id'] = $checkModel->id;
//                                 $insData[$key]['plan_id'] = $checkPlan->id;
//                             }
//
//                         }
//                         else
//                         {
//                             $insFailData[$key] = $row;
//                             $insFailData[$key]['failure_reason'] = 'Instrument model name or Service Plan not exist';
//                         }
//                     }
//                     else
//                     {
//                         $insFailData[$key] = $row;
//                         $insFailData[$key]['failure_reason'] = 'Instrument model name or Service Plan not given';
//                     }
//
//                 } //echo '<pre>';print_r($insData);die;
//
//                 if(count($insData))
//                 {
//                     foreach ($insData as $key=>$row)
//                     {
//
//                         $model = $row['model'];
//                         $checkModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',str_replace(' ','',$model))->first(); //echo '<pre>';print_r($checkModel);die;
//                         $plan = $row['service_plan'];
//                         $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?',str_replace(' ','',$plan))->first(); //echo '<pre>';print_r($checkPlan);die;
//
//                         $save = array();
//                         $save['id'] = '';
//                         $save['asset_no'] = $row['asset_no'];
//                         $save['serial_no'] = $row['serial_no'];
//                         $save['equipment_model_id'] = $row['model_id'];
//                         $save['customer_id'] = $post['customer'];
//                         $save['location'] = $row['location'];
//                         $save['plan_id'] = $row['plan_id'];
//                         $save['pref_tel'] = $row['contact'];
//                         $save['is_active'] = 1;
//                         $save_equipment = $this->dueequipments->saveEquipments($save);
//                         if($save_equipment)
//                         {
//                             if($row['next_due_date'])
//                             {
//                                 $enddates = str_replace('/', '-', $row['next_due_date']);
//                                 $nextDueDate = date('Y-m-d', strtotime($enddates));
//                             }
//                             else
//                             {
//                                 $nextDueDate = date('Y-m-d', strtotime('+3 months'));
//                             }
//                             if($row['last_cal_date'])
//                             {
//                                 $startdates = str_replace('/', '-', $row['last_cal_date']);
//                                 $lastCalDate = date('Y-m-d', strtotime($startdates));
//                             }
//                             else
//                             {
//                                 $lastCalDate = date('Y-m-d', strtotime('-3 months'));
//                             }
//
//                             $saveDue['id'] = '';
//                             $saveDue['equipment_id'] = $save_equipment;
//                             $saveDue['last_cal_date'] = $lastCalDate;
//                             $saveDue['next_due_date'] = $nextDueDate;
//                             $saveDue['as_found'] = 1;
//                             $saveDue['as_calibrate'] = 1;
//                             $save_equipment_due = $this->dueequipments->saveDueequipments($saveDue);
//
//                             //get pricing
//                             $getPricing = DB::table('tbl_service_pricing')->where('volume',$checkModel->volume)
//                                 ->where('operation',$checkModel->brand_operation)->where('channel',$checkModel->channel)
//                                 ->where('channel_number',$checkModel->channel_number)->first();
//                             $price = (isset($getPricing->price)&&$getPricing->price)?$getPricing->price:'';
//                             $price_id = (isset($getPricing->id)&&$getPricing->id)?$getPricing->id:'';
//                             //Save Instrument Detail
//                             $deleteEquipment = $this->equipment->deleteInstrumentDetails($save_equipment);
//                             $instrumentDetail['id'] = false;
//                             $instrumentDetail['equipment_id'] = $save_equipment;
//                             $instrumentDetail['plan_name'] = $plan;
//                             $instrumentDetail['no_of_channels'] = $checkModel->channel_number;
//                             $instrumentDetail['operation_method'] = $checkModel->brand_operation;
//                             $instrumentDetail['price'] = $price;
//                             $instrumentDetail['plan_id'] = $row['plan_id'];
//                             $instrumentDetail['pricing_criteria_id'] = $price_id;
//                             $save_equipment_detail = $this->equipment->saveInstrumentDetails($instrumentDetail);
//
//                             $instrumentDetailLog['id'] = false;
//                             $instrumentDetailLog['equipment_id'] = $save_equipment;
//                             $instrumentDetailLog['plan_name'] = $plan;
//                             $instrumentDetailLog['no_of_channels'] = $checkModel->channel_number;
//                             $instrumentDetailLog['operation_method'] = $checkModel->brand_operation;
//                             $instrumentDetailLog['price'] = $price;
//                             $instrumentDetailLog['plan_id'] = $row['plan_id'];
//                             $instrumentDetailLog['pricing_criteria_id'] = $price_id;
//                             $save_equipment_log = $this->equipment->saveInstrumentLogDetails($instrumentDetailLog);
//
//                         }
//
//                     }
//
//                 }
//
//             }
//
//            }
//            //$csv_data = array_slice($data, 0, 2);
//           // echo '<pre>';print_r($data);die;
//        if(count($insData)>0)
//        {
//            $message = Session::flash('message', 'Successfully Uploaded '.count($insData).' out of '.count($arr));
//        }
//        else
//        {
//            $message = Session::flash('error', 'No Instruments are Uploaded out of '.count($arr));
//        }
//
//            return view('equipment/success_upload')->with('data',$insFailData)->with('title',$title)->with('message',$message)->with('sData',$insData)->with('total',$arr);
//    }


    public function parseImport(Request $request)
    {
        $title = 'Upload Instruments';
        $path = Input::file('csv_file')->getRealPath();
        $data = \Excel::load($path)->get();
        $post = Input::all();
        $customerId = $post['customer'];
        $customerSetups = DB::table('tbl_customer_setups')->where('customer_id', $customerId)->first();
        $planArray = array();
        if ($customerSetups) {
            $planArray = explode(',', $customerSetups->plan_id);
        }
        if ($data->count()) {
            $model = '';
            foreach ($data as $key => $value) {
                foreach ($value as $val) {
//                     echo '<pre>';print_r($val);exit;
                    if ($val->model) {
                        $arr[] = ['asset_no' => $val->asset_no,
                            'serial_no' => $val->serial_no,
                            'model' => $val->model,
                            'contact' => $val->tel,
                            'location' => $val->location,
                            'service_plan' => $val->service_plan,
                            'last_cal_date' => $val->last_cal_date,
                            'as_found' => $val->as_found,
                            'as_cal' => $val->as_cal,
                            'next_due_date' => $val->next_due_date,
                            'prefer_contact' => $val->prefer_contact,
                        ];
                    }
                } //echo '<pre>';print_r($arr);die;

            }


            if (count($arr)) {

                $insData = array();
                $insFailData = array();
                foreach ($arr as $key => $row) {
                    if ($row['model'] && $row['service_plan']) {
                        $model = $row['model'];
                        $output = preg_replace('!\s+!', ' ', $model);
                        $model = $output;
//                        echo '<pre>';print_r($output);die;
                        $space_left_model = str_replace(' ', '', $model);
                        $checkModel = DB::table('tbl_equipment_model')
                            ->whereRaw('REPLACE(`model_description`," ","") = ?', $space_left_model)->first();
                        //->whereRaw('REPLACE(`model_description`,"-","") = ?',str_replace('-','',$space_left_model))->first();
//                        echo '<pre>';print_r($checkModel);die;
                        $plan = $row['service_plan'];
                        $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?', str_replace(' ', '', $plan))->wherein('id', $planArray)->first(); //echo '<pre>';print_r($checkPlan);die;
                        $checkContact = DB::table('tbl_users')->where('customer_id', $post['customer'])->where('name', $row['prefer_contact'])->select('*')->first();
                        //echo'<pre>';print_r($checkContact);exit;
//                         if($checkModel&&$checkPlan)
                        if ($checkModel && $checkPlan && $checkContact) {
                            //check asset num duplication
                            $checkAsset = array();
                            if ($row['asset_no']) {
                                $checkAsset = DB::table('tbl_equipment')->whereRaw('REPLACE(`asset_no`," ","") = ?', str_replace(' ', '', $row['asset_no']))->where('customer_id', $post['customer'])->first();
                            }
                            if ($checkAsset) {
                                $insFailData[$key] = $row;
                                $insFailData[$key]['failure_reason'] = 'Asset number is duplicated';
                            } else {
                                $insData[$key] = $row;
                                $insData[$key]['model_id'] = $checkModel->id;
                                $insData[$key]['plan_id'] = $checkPlan->id;
                                $insData[$key]['pref_contact'] = $checkContact->name;
                                $insData[$key]['pref_tel'] = $checkContact->telephone;
                                $insData[$key]['pref_email'] = $checkContact->email;
                                $insData[$key]['pref_contact_id'] = $checkContact->id;
                            }

                        } else {
                            $insFailData[$key] = $row;
                            if (!$checkModel) {
                                $insFailData[$key]['failure_reason'] = 'Instrument model name should be equivalent to the master data';
                            } elseif (!$checkPlan) {
                                $insFailData[$key]['failure_reason'] = 'This Service Plan is not configured to this customer';
                            } elseif (!$checkContact) {
                                $insFailData[$key]['failure_reason'] = 'Pref Contact user is not the valid user for this customer';
                            } else {
                                $insFailData[$key]['failure_reason'] = 'Master Data is wrong';
                            }

                        }
                    } else {
                        $insFailData[$key] = $row;
                        $insFailData[$key]['failure_reason'] = 'Instrument model name or Service Plan not given';
                    }

                }
//                echo '<pre>';print_r($insData);die;

                if (count($insData)) {
                    foreach ($insData as $key => $row) {

                        $model = $row['model'];
                        $checkModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?', str_replace(' ', '', $model))->first();
                        $plan = $row['service_plan'];
                        $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?', str_replace(' ', '', $plan))->wherein('id', $planArray)->first();
                        $checkContact = DB::table('tbl_users')->where('customer_id', $post['customer'])->where('name', $row['prefer_contact'])->select('*')->first();
                        $save = array();
                        $save['id'] = '';
                        $save['asset_no'] = $row['asset_no'];
                        $save['serial_no'] = $row['serial_no'];
                        $save['equipment_model_id'] = $row['model_id'];
                        $save['customer_id'] = $post['customer'];
                        $save['location'] = $row['location'];
                        $save['plan_id'] = $row['plan_id'];
//                         $save['pref_tel'] = $row['contact'];
                        $save['pref_contact'] = $row['pref_contact'];
                        $save['pref_tel'] = $row['pref_tel'];
                        $save['pref_email'] = $row['pref_email'];
                        $save['pref_contact_id'] = $row['pref_contact_id'];
                        $save['is_active'] = 1;
                        $save_equipment = $this->dueequipments->saveEquipments($save);
                        if ($save_equipment) {
                            if ($row['next_due_date']) {
                                $enddates = str_replace('/', '-', $row['next_due_date']);
                                $nextDueDate = date('Y-m-d', strtotime($enddates));
                            } else {
                                $nextDueDate = date('Y-m-d', strtotime('+3 months'));
                            }
                            if ($row['last_cal_date']) {
                                $startdates = str_replace('/', '-', $row['last_cal_date']);
                                $lastCalDate = date('Y-m-d', strtotime($startdates));
                            } else {
                                $lastCalDate = date('Y-m-d', strtotime('-3 months'));
                            }

                            $saveDue['id'] = '';
                            $saveDue['equipment_id'] = $save_equipment;
                            $saveDue['last_cal_date'] = $lastCalDate;
                            $saveDue['next_due_date'] = $nextDueDate;
                            $saveDue['as_found'] = 1;
                            $saveDue['as_calibrate'] = 1;
                            $save_equipment_due = $this->dueequipments->saveDueequipments($saveDue);

                            //get pricing
                            $getPricing = DB::table('tbl_service_pricing')->where('volume', $checkModel->volume)
                                ->where('operation', $checkModel->brand_operation)->where('channel', $checkModel->channel)
                                ->where('channel_number', $checkModel->channel_number)->first();
                            $price = (isset($getPricing->price) && $getPricing->price) ? $getPricing->price : '';
                            $price_id = (isset($getPricing->id) && $getPricing->id) ? $getPricing->id : '';
                            //Save Instrument Detail
                            $deleteEquipment = $this->equipment->deleteInstrumentDetails($save_equipment);
                            $instrumentDetail['id'] = false;
                            $instrumentDetail['equipment_id'] = $save_equipment;
                            $instrumentDetail['plan_name'] = $plan;
                            $instrumentDetail['no_of_channels'] = $checkModel->channel_number;
                            $instrumentDetail['operation_method'] = $checkModel->brand_operation;
                            $instrumentDetail['price'] = $price;
                            $instrumentDetail['plan_id'] = $row['plan_id'];
                            $instrumentDetail['pricing_criteria_id'] = $price_id;
                            $save_equipment_detail = $this->equipment->saveInstrumentDetails($instrumentDetail);

                            $instrumentDetailLog['id'] = false;
                            $instrumentDetailLog['equipment_id'] = $save_equipment;
                            $instrumentDetailLog['plan_name'] = $plan;
                            $instrumentDetailLog['no_of_channels'] = $checkModel->channel_number;
                            $instrumentDetailLog['operation_method'] = $checkModel->brand_operation;
                            $instrumentDetailLog['price'] = $price;
                            $instrumentDetailLog['plan_id'] = $row['plan_id'];
                            $instrumentDetailLog['pricing_criteria_id'] = $price_id;
                            $save_equipment_log = $this->equipment->saveInstrumentLogDetails($instrumentDetailLog);

                        }

                    }

                }

            }

        }
        //$csv_data = array_slice($data, 0, 2);
        // echo '<pre>';print_r($data);die;
        if (count($insData) > 0) {
            $message = Session::flash('message', 'Successfully Uploaded ' . count($insData) . ' out of ' . count($arr));
        } else {
            $message = Session::flash('error', 'No Instruments are Uploaded out of ' . count($arr));
        }

        return view('equipment/success_upload')->with('data', $insFailData)->with('title', $title)->with('message', $message)->with('sData', $insData)->with('total', $arr);
    }

    function downloadSampleCsv()
    {
        $file = public_path() . "/format/sample.xlsx";
        $name = "sample.xlsx";
        //print_r($name);die;
        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, $name, $headers);
    }

    function uploadPageSeperate()
    {
        $input = Input::all();
        $title = 'Upload Instruments without Model Description';

        $customers = DB::table('tbl_customer')->pluck('customer_name','id');
        $customers->prepend('Choose customer','');

        return view('equipment.uploadPageSeperate')->with('customer', $customers)->with('title',$title)->with('message','');
    }

    function downloadSampleCsvWithoutDescription()
    {
        $file= public_path(). "/format/SampleWithoutDescription.xlsx";
        $name = "SampleWithoutDescription.xls";
        //print_r($name);die;
        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, $name, $headers);
    }

    public function import_parse_withoutdescription(Request $request)
    {
        $title = 'Upload Instruments';
        $path = Input::file('csv_file');
        $fileformat = $path->getClientOriginalExtension(); //print_r($fileformat);die;
        $realPath = $path->getRealPath();
        $data = \Excel::load($realPath)->get(); //echo '<pre>';print_r($data);die;
        $post = Input::all();
        $customerId = $post['customer'];
        $customerSetups = DB::table('tbl_customer_setups')->where('customer_id',$customerId)->first();
        $planArray = array();
        if($customerSetups)
        {
            $planArray = explode(',',$customerSetups->plan_id);
        }
        //echo '<pre>';print_r($data);die;
        if ($data->count())
        {
            $model = '';
            foreach ($data as $key => $value)
            { //echo '<pre>';print_r($val['model_name']);die;
                //foreach ($value as $val) {
                    if ($value->model_name) {
                        // echo '<pre>';print_r($value);exit;
                        $arr[] = ['asset_no' => $value->asset,
                            'serial_no' => $value->serial,
                            'model_name' => $value->model_name,
                            'manufacturer' => $value->manufacturer,
                            'brand' => $value->brand,
                            'operation' => $value->operation,
                            'channel_type' => $value->channel_type,
                            'channel_number' => $value->channel_number,
                            'volume_type' => $value->volume_type,
                            'volume_from' => $value->volume_from,
                            'volume_to' => $value->volume_to,
                            'location' => $value->location,
                            'last_cal_date' => $value->last_cal_date,
                            'as_found' => $value->as_found,
                            'as_cal' => $value->as_cal,
                            'next_due' => $value->next_due,
                            'tel' => $value->tel,
                            'service_plan' => $value->service_plan,
                            'prefer_contact' => $value->prefer_contact,
                        ];
                    }
                //}

            }
           //echo '<pre>';print_r($arr);die;

            if(count($arr))
            {

                $insData = array();
                $insFailData = array();
                foreach ($arr as $key=>$row)
                {
                    if(isset($row['volume_to'])&&$row['volume_to'])
                    {
                        $modelCombo = $row['brand'].' '.$row['model_name'].' ('.$row['volume_from'].'-'.$row['volume_to'].') μl '.$row['volume_type'].' '.$row['operation'].' '.$row['channel_type'].' Pipette';
                    }
                    else
                    {
                        $modelCombo = $row['brand'].' '.$row['model_name'].' ('.$row['volume_from'].') μl '.$row['volume_type'].' '.$row['operation'].' '.$row['channel_type'].' Pipette';
                    }

                    //print_r($modelCombo);die;

                    if($modelCombo && $row['service_plan'])
                    {
//                        echo '<pre>';print_r($post['customer']);print_r($row['prefer_contact']);die;

                        $model = $modelCombo;
                        $checkModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',str_replace(' ','',$model))

                            ->first();
                        $plan = $row['service_plan'];
                        $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?',str_replace(' ','',$plan))->wherein('id',$planArray)->first(); //echo '<pre>';print_r($checkPlan);die;
                        $checkContact = DB::table('tbl_users')->where("customer_id",$post['customer'])->where("name",$row['prefer_contact'])->select('*')->first();


//                        echo'<pre>';print_r($checkContact);exit;
//                         if($checkModel&&$checkPlan)
                        if($checkModel && $checkPlan && $checkContact)
                        {
                            //check asset num duplication
                            $checkAsset=array();
                            if($row['asset_no'])
                            {
                                $checkAsset = DB::table('tbl_equipment')->whereRaw('REPLACE(`asset_no`," ","") = ?',str_replace(' ','',$row['asset_no']))->where('customer_id',$post['customer'])->first();
                            }
                            if($checkAsset)
                            {
                                $insFailData[$key] = $row;
                                $insFailData[$key]['failure_reason'] = 'Asset number is duplicated';
                            }
                            else
                            {
                                $insData[$key] = $row;
                                $insData[$key]['model_id'] = $checkModel->id;
                                $insData[$key]['plan_id'] = $checkPlan->id;
                                $insData[$key]['pref_contact'] = $checkContact->name;
                                $insData[$key]['pref_tel'] = $row['tel'];
                                $insData[$key]['pref_email'] = $checkContact->email;
                                $insData[$key]['pref_contact_id'] = $checkContact->id;
                            }

                        }
                        else
                        {
                            $insFailData[$key] = $row;
                            if(!$checkModel)
                            {
                                $insFailData[$key]['failure_reason'] = 'Instrument model name should be equivalent to the master data';
                            }
                            elseif(!$checkPlan)
                            {
                                $insFailData[$key]['failure_reason'] = 'This Service Plan is not configured to this customer';
                            }
                            elseif(!$checkContact)
                            {
                                $insFailData[$key]['failure_reason'] = 'Pref Contact user is not the valid user for this customer';
                            }
                            else
                            {
                                $insFailData[$key]['failure_reason'] = 'Master Data is wrong';
                            }

                        }
                    }
                    else
                    {
                        $insFailData[$key] = $row;
                        $insFailData[$key]['failure_reason'] = 'Instrument model name or Service Plan not given';
                    }

                }
//                echo '<pre>';print_r($insData);die;

                if(count($insData))
                {
                    foreach ($insData as $key=>$row)
                    {
                        if(isset($row['volume_to'])&&$row['volume_to'])
                        {
                            $modelCombo = $row['brand'].' '.$row['model_name'].' ('.$row['volume_from'].'-'.$row['volume_to'].') μl '.$row['volume_type'].' '.$row['operation'].' '.$row['channel_type'].' Pipette';
                        }
                        else
                        {
                            $modelCombo = $row['brand'].' '.$row['model_name'].' ('.$row['volume_from'].') μl '.$row['volume_type'].' '.$row['operation'].' '.$row['channel_type'].' Pipette';
                        }

                        $model = $modelCombo;
                        $checkModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',str_replace(' ','',$model))->first(); //echo '<pre>';print_r($checkModel);die;
                        $plan = $row['service_plan'];
                        $checkPlan = DB::table('tbl_service_plan')->whereRaw('REPLACE(`service_plan_name`," ","") = ?',str_replace(' ','',$plan))->wherein('id',$planArray)->first();
                        $checkContact = DB::table('tbl_users')->where('customer_id',$post['customer'])->where('name',$row['prefer_contact'])->select('*')->first();
                        $save = array();
                        $save['id'] = '';
                        $save['asset_no'] = $row['asset_no'];
                        $save['serial_no'] = $row['serial_no'];
                        $save['equipment_model_id'] = $row['model_id'];
                        $save['customer_id'] = $post['customer'];
                        $save['location'] = $row['location'];
                        $save['plan_id'] = $row['plan_id'];
//                         $save['pref_tel'] = $row['contact'];
                        $save['pref_contact'] =  $row['pref_contact'];
                        $save['pref_tel'] =  $row['pref_tel'];
                        $save['pref_email'] = $row['pref_email'];
                        $save['pref_contact_id'] = $row['pref_contact_id'];
                        $save['is_active'] = 1;
                        $save_equipment = $this->dueequipments->saveEquipments($save);
                        if($save_equipment)
                        {
                            if($row['next_due'])
                            {
                                $enddates = str_replace('/', '-', $row['next_due']);
                                $nextDueDate = date('Y-m-d', strtotime($enddates));
                            }
                            else
                            {
                                $nextDueDate = date('Y-m-d', strtotime('+3 months'));
                            }
                            if($row['last_cal_date'])
                            {
                                $startdates = str_replace('/', '-', $row['last_cal_date']);
                                $lastCalDate = date('Y-m-d', strtotime($startdates));
                            }
                            else
                            {
                                $lastCalDate = date('Y-m-d', strtotime('-3 months'));
                            }

                            $saveDue['id'] = '';
                            $saveDue['equipment_id'] = $save_equipment;
                            $saveDue['last_cal_date'] = $lastCalDate;
                            $saveDue['next_due_date'] = $nextDueDate;
                            $saveDue['as_found'] = 1;
                            $saveDue['as_calibrate'] = 1;
                            $save_equipment_due = $this->dueequipments->saveDueequipments($saveDue);

                            //get pricing
                            $getPricing = DB::table('tbl_service_pricing')->where('volume',$checkModel->volume)
                                ->where('operation',$checkModel->brand_operation)->where('channel',$checkModel->channel)
                                ->where('channel_number',$checkModel->channel_number)->first();
                            $price = (isset($getPricing->price)&&$getPricing->price)?$getPricing->price:'';
                            $price_id = (isset($getPricing->id)&&$getPricing->id)?$getPricing->id:'';
                            //Save Instrument Detail
                            $deleteEquipment = $this->equipment->deleteInstrumentDetails($save_equipment);
                            $instrumentDetail['id'] = false;
                            $instrumentDetail['equipment_id'] = $save_equipment;
                            $instrumentDetail['plan_name'] = $plan;
                            $instrumentDetail['no_of_channels'] = $checkModel->channel_number;
                            $instrumentDetail['operation_method'] = $checkModel->brand_operation;
                            $instrumentDetail['price'] = $price;
                            $instrumentDetail['plan_id'] = $row['plan_id'];
                            $instrumentDetail['pricing_criteria_id'] = $price_id;
                            $save_equipment_detail = $this->equipment->saveInstrumentDetails($instrumentDetail);

                            $instrumentDetailLog['id'] = false;
                            $instrumentDetailLog['equipment_id'] = $save_equipment;
                            $instrumentDetailLog['plan_name'] = $plan;
                            $instrumentDetailLog['no_of_channels'] = $checkModel->channel_number;
                            $instrumentDetailLog['operation_method'] = $checkModel->brand_operation;
                            $instrumentDetailLog['price'] = $price;
                            $instrumentDetailLog['plan_id'] = $row['plan_id'];
                            $instrumentDetailLog['pricing_criteria_id'] = $price_id;
                            $save_equipment_log = $this->equipment->saveInstrumentLogDetails($instrumentDetailLog);

                        }

                    }

                }

            }

        }
        //$csv_data = array_slice($data, 0, 2);
        // echo '<pre>';print_r($data);die;
        if(count($insData)>0)
        {
            $message = Session::flash('message', 'Successfully Uploaded '.count($insData).' out of '.count($arr));
        }
        else
        {
            $message = Session::flash('error', 'No Instruments are Uploaded out of '.count($arr));
        }

        return view('equipment/success_uploadwithoutdescription')->with('data',$insFailData)->with('title',$title)->with('message',$message)->with('sData',$insData)->with('total',$arr);
    }


}
