<?php

namespace App\Http\Controllers\web\customer;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Sentinel\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Mail;
use GuzzleHttp\Client;

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;


//use Request;

class CustomerSetupControlller extends Controller
{
    protected $mail;

    public function __construct()
    {
        $this->customer = new Customer();

    }


    public function form(Request $request, $customerId, $id = false)
    {
        $input = Input::all();

        $title = 'Novamed-Customer Setup';


//        select customer

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');

//        select cal Specifications
        $calSpecifications = DB::table('tbl_cal_specification')->select('cal_specification', 'id')->get();

//        for choose cal frequency
        $calFrequency = DB::table('tbl_frequency')->select('*')->get();

//        for choose labeling

        $selectLabeling = DB::table('tbl_labeling')->select('*')->get();


//        for choose Shipping

        $selectShipping = DB::table('tbl_shipping')->select('*')->get();

//        for chooseing servicePlan


        $customerInfo = DB::table('tbl_customer')->where('id',$customerId)->first();
        $serviceTypeId = (isset($customerInfo->customer_type_id)&&$customerInfo->customer_type_id)?$customerInfo->customer_type_id:'';
        if($serviceTypeId)
        {
            $servicePlanSelect = DB::table('tbl_service_plan')->where('service_plan_type',$serviceTypeId)->select('*')->get();
        }
        else
        {
            $servicePlanSelect = DB::table('tbl_service_plan')->select('*')->get();
        }


//        select pay methods

        $selectPayMethods = DB::table('tbl_pay_method')->select('*')->get();


        $data = [
            'id' => $id,
            'testPlanName' => isset($input['testPlanName']) ? $input['testPlanName'] : '',
            'testPlanUnit' => isset($input['testPlanUnit']) ? $input['testPlanUnit'] : '',
            'testPlanDescription' => isset($input['testPlanDescription']) ? $input['testPlanDescription'] : '',
            'testPlanEquipmentModel' => isset($input['testPlanEquipmentModel']) ? $input['testPlanEquipmentModel'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
            'test_Tolerences' => array(),
            'model_id' => '',
            'model_name' => '',
        ];
//        print_r($data);die;

        if ($id) {
            $getplan = $this->testplan->getTest($data['id']);

            $data['id'] = $getplan->testPlanId;
            $data['model_id'] = $getplan->model_id;
            $data['testPlanName'] = $getplan->name;
            $data['testPlanDescription'] = $getplan->description;
            $data['testPlanUnit'] = $getplan->unit;
            $data['is_active'] = $getplan->is_active;
            $data['test_Tolerences'] = $this->testplan->getTestTolerences($id);
            $data['model_name'] = $getplan->model_name;


//                  echo'<pre>';  print_r($data);die;

        }
        $requiredFields = [
            'customerCompanyName' => isset($input['customerCompanyName']) ? $input['customerCompanyName'] : '',
            'customerCompanyType' => isset($input['customerCompanyType']) ? $input['customerCompanyType'] : ''

        ];

        $rules = [
            'customerCompanyName' => 'required',
            'customerCompanyType' => 'required'
        ];
        $error = array();
        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($requiredFields, $rules);

            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
            $customer_setups = $this->customer->getCustomerSetup($customerId);
            $customer_setup_array = array();
            if($customer_setups)
            {
                $customer_setup_array['id'] = $customer_setups->id;
                $customer_setup_array['customerId'] = $customer_setups->customer_id;
                $customer_setup_array['payMethod'] = $customer_setups->pay_method;
                $customer_setup_array['payTerms'] = $customer_setups->pay_terms;
                $customer_setup_array['calSpecification'] = $customer_setups->cal_spec;
                $customer_setup_array['specComments'] = $customer_setups->spec_comments;
                $customer_setup_array['calFrequency'] = $customer_setups->cal_frequnecy;
//                $customer_setup_array['excatDate'] = $customer_setups->exact_date;
                $customer_setup_array['assetLabel'] = explode(',',$customer_setups->asset_label);
                $customer_setup_array['shipping'] = $customer_setups->shipping;
                $customer_setup_array['shippingComments'] = $customer_setups->ship_comments;
                $customer_setup_array['plans'] = explode(',',$customer_setups->plan_id);
            }
        }
        //echo'<pre>';print_r($customer_setup_array);'</pre>';die;
        $select = array('c.id', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.customer_email', 'c.customer_main_telephone', 'address1', 'c.state', 'c.city', 'c.customer_email');
        $customerdetails = $this->customer->AllCustomer('', '', 'c.id', 'DESC', array('select' => $select, 'customerId' => $customerId), false);

        if ($checkStatus) {

            return view('customer.customerSetup')->with('input', $data)->with('customerdetails', $customerdetails)->with('customer', $customer)->with('selectPayMethods', $selectPayMethods)->with('selectShipping', $selectShipping)->with('selectServicePlan', $servicePlanSelect)
                ->with('title', $title)->with('calFrequency', $calFrequency)->with('selectLabeling', $selectLabeling)->with('customerId', $customerId)->with('calSpecification', $calSpecifications)->with('errors', $error)
                ->with('customer_setups',$customer_setup_array);
        } else {
            $post = Input::all();


        }
    }

    function limittolerence()
    {
        $input = Input::all();
//       echo'<pre>'; print_r($input);die;
        $modelId = $input['modelId'];
        if ($modelId) {
            $tolerences = $this->testplan->getLimitTolerences($modelId);
            $view = View::make('testplan.testplanTolerenceAjax', ['data' => $tolerences]);
            $formData = $view->render();
            die(json_encode(array("result" => true, "formData" => trim($formData))));

        }
    }

    public function addCustomerSetup()
    {
        $input = Input::all();

//        echo '<pre>';
//        print_r($input);
//        die;

//        $input['id'] = isset($input['Id']) ? $input['Id'] : false;
        $input['id'] = isset($input['Id']) ? $input['Id'] : false;
//        echo '<pre>';print_r($input['Id']);die;

        if (!$input) {
            return redirect('admin/customerSetup')->with('message', 'Values are not get Properly');

        }

        $exactDate = str_replace('/', '-', (isset($input['exactDate'])? $input['exactDate'] : ''));

            if (isset($input['labeling'])) {

            $param = array();
            foreach ($input['labeling'] as $value) {
                $param[] = $value;
            }
            $lebeling = implode(',', $param);
        }

        if (isset($input['servicePlan'])) {

            $servies = array();
            foreach ($input['servicePlan'] as $servicevalue) {
                $servies[] = $servicevalue;
            }
            $serviePlan = implode(',', $servies);
        }
         //**delete old setup for the customer
        $checkSetup = DB::table('tbl_customer_setups')->where('customer_id', $input['customerName'])->first();
            if($checkSetup)
            {
                DB::table('tbl_customer_setups')->where('customer_id', $input['customerName'])->delete();
            }

        //echo 'hai';die;
        $saveSetup['id'] = '';
        $saveSetup['customer_id'] = $input['customerName'];
        $saveSetup['pay_method'] = isset($input['payMethod']) ? $input['payMethod'] : null ;
        $saveSetup['pay_terms'] = isset($input['paymentTerms']) ? $input['paymentTerms'] : null ;
        $saveSetup['cal_spec'] = isset($input['calSpecification']) ? $input['calSpecification'] : null ;
        if (isset($input['calSpecification']) == 3) {
            $saveSetup['spec_comments'] = $input['specComments'];
        }

        $saveSetup['cal_frequnecy'] = isset($input['calFrequency']) ? $input['calFrequency'] : '' ;
        if(isset($input['exactDate'])){
            $saveSetup['exact_date'] = date('Y-m-d', strtotime($exactDate));
        }else{
            $saveSetup['exact_date'] = null;
        }

        $saveSetup['asset_label'] = isset($lebeling)?$lebeling : '';
        $saveSetup['shipping'] = isset($input['shipValue']) ? $input['shipValue'] : '' ;
        $saveSetup['ship_comments'] = $input['shippingComment'];
        $saveSetup['plan_id'] = isset($serviePlan) ? $serviePlan : '';
        //echo'<pre>';print_r($saveSetup);'</pre>';die;
        //$saveSetups = $this->customer->saveCustomerSetup($saveSetup);
        $result = DB::table('tbl_customer_setups')->insertGetId($saveSetup);


        return redirect('admin/customerlists')->with('message', 'Customer setup updated successfully');


    }

    public function getCustomer()
    {
        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
        if (!$input) {
            die(json_encode(array('result' => true, 'message' => 'Values are not get Properly')));
        }
        $data = array();
        $customerId = $input['customerId'];
        $getCustomer = $this->customer->getCustomer($customerId, true);
        $get_contact_users = $this->customer->getCustomerUsers($customerId);
        $element = '<option value="">Select Preferred Contact</option>';
        if(count($get_contact_users))
        {

            foreach ($get_contact_users as $val) {
                $element .= '<option value="' . $val->id . '">' . $val->name . '</option>';
            }

            $data = $element;
        }

        $servicePlan = DB::table('tbl_customer_setups')->select('plan_id', 'id')->where('customer_id','=',$customerId)->first();
        if($servicePlan){
            $service_plan = explode(',',$servicePlan->plan_id);
        }else{
            $service_plan[0] = "";
        }

        $servicePlanSelect=array();
        $serviceelement = '<div>';
        if($service_plan[0] == ''){
//            echo '<pre>';print_r($service_plan);exit;
            $customerInfo = DB::table('tbl_customer')->where('id',$customerId)->first();
            $serviceTypeId = (isset($customerInfo->customer_type_id)&&$customerInfo->customer_type_id)?$customerInfo->customer_type_id:'';
            if($serviceTypeId)
            {
                $service_plan = DB::table('tbl_service_plan')->where('service_plan_type',$serviceTypeId)->select('*')->get();
                foreach($service_plan as $row){
                    $servicePlanSelect[$row->id] = $row->service_plan_name;
                    $serviceelement .='<div class="am-radio inline"> <input type="radio" name="planName" class="planName"data-id="'.$row->id.'" id="plan_'.$row->id.'"  value="'.$row->id.'"/'.$row->service_plan_name.'><label for="plan_'.$row->id.'">'.$row->service_plan_name.'</label></div>';

                }
            }

        }else{
            foreach($service_plan as $row)
            {
                $service_Plan_select = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->where('id',$row)->first();
//
                $servicePlanSelect[$service_Plan_select->id] = $service_Plan_select->service_plan_name;
                $serviceelement .='<div class="am-radio inline"> <input type="radio" name="planName" class="planName"data-id="'.$service_Plan_select->id.'" id="plan_'.$service_Plan_select->id.'"  value="'.$service_Plan_select->id.'"/'.$service_Plan_select->service_plan_name.'><label for="plan_'.$service_Plan_select->id.'">'.$service_Plan_select->service_plan_name.'</label></div>';
            }
        }

        $serviceelement .= '</div>';
        $servicePlanSelect =$serviceelement;
        if (!$getCustomer) {
            die(json_encode(array('result' => true, 'message' => 'Customer Not found')));
        } else {
            die(json_encode(array('result' => true, 'data' => $getCustomer, 'message' => 'Customer Details Found','users'=>$data,'serviceplanselect'=>$servicePlanSelect)));
        }

    }
}
