<?php
namespace App\Http\Controllers;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Models\Settings;
use JWTAuthException;
use DB;
use Response;
use Validator;
use Illuminate\Support\Facades\Hash;
use Image;
use App\User;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedExceptionException;
class SettingsController extends Controller
{
    private $user;
    public function __construct(Settings $user){
        $this->user = $user;
        $this->settings = new Settings();
        $this->userModel = new User();
    }

    public function index()
    {
        //print_r($this->user);die;
        return response()->json(['auth'=>Auth::user(), 'users'=>User::all()]);
    }

    public function setupmasters(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        $customer = $this->settings->getcustomer($user->user_id);
        $getlabelling = array();
        $getpaymethods = array();
        $getshipping = array();
        if($user)
        {
            $setups = $this->settings->setups($user->user_id);
            $data['plans'] = $setups->plan_id;
            $data['cal_specefications'] = $setups->cal_spec;
            $data['labels'] = $setups->asset_label;
            $data['frequency'] = $setups->cal_frequnecy;
            $data['exact_date'] = date('Y-m-d',strtotime($setups->exact_date));
            $data['paymethod'] = $setups->pay_method;
            $data['pay_comments'] = $setups->pay_terms;
            $data['shipping'] = $setups->shipping;
            $data['shipping_comments'] = $setups->ship_comments;

            $data['frequency_name'] = $setups->fname;
            $data['pay_method_name'] = $setups->pname;
            $data['cal_specefication_name'] = $setups->cal_specification;
            $data['shipping_name'] = $setups->sname;

            $plan_ids = (isset($setups->plan_id) && $setups->plan_id)?explode(',',$setups->plan_id):'';
            $label_ids = (isset($setups->asset_label) && $setups->asset_label)?explode(',',$setups->asset_label):'';
            $specefication_id = (isset($setups->cal_spec) && $setups->cal_spec)?explode(',',$setups->cal_spec):'';
            $frequency_id = (isset($setups->cal_frequnecy) && $setups->cal_frequnecy)?explode(',',$setups->cal_frequnecy):'';
            $paymethod_id = (isset($setups->pay_method) && $setups->pay_method)?explode(',',$setups->pay_method):'';
            $shipping_id = (isset($setups->shipping) && $setups->shipping)?explode(',',$setups->shipping):'';
            $getPlans = $this->settings->servicePlans($plan_ids);
            $getSpecifications = $this->settings->specefications($specefication_id);
            $getfrequency = $this->settings->frequency($frequency_id);
            if($label_ids)
            {
                $getlabelling = $this->settings->labelling($label_ids);
            }
            if($paymethod_id)
            {
                $getpaymethods = $this->settings->paymethods($paymethod_id);
            }
            if($shipping_id)
            {
                $getshipping = $this->settings->shipping($shipping_id);
            }



            $tempplan = array();
            if($getPlans)
            {
                foreach ($getPlans as $key=>$row)
                {
                    $asFoundReadingValue = '';
                    $asCalibratedReadingValue = '';
                    if($row->as_found_readings)
                    {
                        $asFoundReadings = DB::table('tbl_samples')->select('name')->where('id',$row->as_found_readings)->first();
                        $asFoundReadingValue= (isset($asFoundReadings->name)&&$asFoundReadings->name)?$asFoundReadings->name:'';
                    }
                    if($row->as_calibrate_readings)
                    {
                        $asCalibrateReadings = DB::table('tbl_samples')->select('name')->where('id',$row->as_calibrate_readings)->first();
                        $asCalibratedReadingValue= (isset($asCalibrateReadings->name)&&$asCalibrateReadings->name)?$asCalibrateReadings->name:'';
                    }

                    $tempplan[$key]['id']  = $row->id;
                    $tempplan[$key]['service_plan_name']  = $row->service_plan_name;
                    $tempplan[$key]['plan_description']  = $row->plan_description;
                    $tempplan[$key]['as_found']  = $row->as_found==1?'Yes':'No';
                    $tempplan[$key]['as_calibrate']  = $row->as_calibrate==1?'Yes':'No';
                    $tempplan[$key]['as_found_readings']  = $asFoundReadingValue?$asFoundReadingValue:'Not specified';
                    $tempplan[$key]['as_calibrate_readings']  = $asCalibratedReadingValue?$asCalibratedReadingValue:'Not specified';
                    $tempplan[$key]['as_found_points']  = $row->as_found_TP?$row->as_found_TP:'Not specified';
                    $tempplan[$key]['as_calibrated_points']  = $row->as_calibrate_TP?$row->as_calibrate_TP:'Not specified';
                    $tempplan[$key]['issue_certificate']  = $row->issue_certificate==1?'Yes':'No';
                }
            }
            return Response::json([
                'status' => 1,
                'data' => $data,
                'service_plans'=>$tempplan,
                'cal_specifications'=>$getSpecifications,
                'frequency'=>$getfrequency,
                'labelling'=>$getlabelling,
                'paymethods'=>$getpaymethods,
                'shipping'=>$getshipping
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function editsetups(Request $request)
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
        $reqInputs = $request->input();


        $user = $this->userModel->getUser($user['id']);
        $customer = $this->settings->getcustomer($user->user_id);
        if ($user) {
           // $company = $this->company->getCompanyProfile($user->user_id);
            $save['customer_id'] = $customer->id;
            $save['cal_spec'] = $reqInputs['cal_specefications'];
            $save['cal_frequnecy'] = $reqInputs['frequency'];
            $save['exact_date'] = $reqInputs['exact_date'];
            $save['asset_label'] = $reqInputs['labels'];
            $save['plan_id'] = $reqInputs['plans'];
            $save['pay_method'] = $reqInputs['paymethod'];
            $save['pay_terms'] = $reqInputs['paycomments'];
            $save['shipping'] = $reqInputs['shipping'];
            $save['ship_comments'] = $reqInputs['shipping_comments'];

            $id = $this->settings->saveSetups($save);

        }
        return Response::json([
            'status' => 1,
            'message' => 'Setup has been changed'
        ], 200);

    }
}