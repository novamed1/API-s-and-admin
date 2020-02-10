<?php

namespace App\Http\Controllers;

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
use App\Processoperation;

class ProcessOperationController extends Controller
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
        $this->process = new Processoperation();

    }

    public function workorderinstrumentdetail(Request $request)
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

        $instrumentsLists = $this->process->instrumentLists($workorderid);
        //echo'<pre>';print_r($instrumentsLists);'</pre>';die;
        $instrumentsarr = array();
        if ($instrumentsLists) {
            foreach ($instrumentsLists as $inskey => $insrow) {


                    if($insrow->as_found_status=='progress')
                    {
                        $ongoing = 'As found';
                    }
                    elseif($insrow->maintenance_status=='progress')
                    {
                        $ongoing = 'Maintenance';
                    }
                    elseif($insrow->as_calibrated_status=='progress')
                    {
                        $ongoing = 'Calibration';
                    }
                    elseif($insrow->despatched_status=='progress')
                    {
                        $ongoing = 'Despatched';
                    }
                    else

                    {
                        $ongoing = 'Completed';
                    }

                $instrumentsarr[$inskey]['id'] = $insrow->work_order_item_id;
                $instrumentsarr[$inskey]['asset_no'] = $insrow->asset_no;
                $instrumentsarr[$inskey]['serial_no'] = $insrow->serial_no;
                $instrumentsarr[$inskey]['model'] = $insrow->model_name;
                $instrumentsarr[$inskey]['model_id'] = $insrow->model_id;
                $instrumentsarr[$inskey]['status'] = $ongoing;

            }

            return Response::json([
                'status' => 1,
                'data' => $instrumentsarr
            ], 200);
        }

    }

    public function instrumentinfo(Request $request)
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
            'id' => $reqInputs['id'],
            'work_order_id' => $reqInputs['work_order_id']
        ];
        $rules = array(

            'id' => 'required',
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
        $work_order_item_id = $input['id'];
        $workorder = $this->technicianuser->getWorkorder($workorderid);
        $service_plan = array();
        if($workorder)
        {
            $service_plan = $this->process->get_service_plan($workorder->plan_id);
        }
        $plan = (isset($service_plan->service_plan_name)&&$service_plan->service_plan_name)?$service_plan->service_plan_name:'';
        $plan_description = (isset($service_plan->plan_description)&&$service_plan->plan_description)?$service_plan->plan_description:'';
        $ins_detail = $this->process->instrumentDetail($work_order_item_id);
        //print_r($ins_detail);die;
        //$service_plan = (isset())
        $data=array();
        $data['asset_no'] = (isset($ins_detail->asset_no)&&$ins_detail->asset_no)?$ins_detail->asset_no:'';
        $data['pref_contact'] = (isset($ins_detail->pref_contact)&&$ins_detail->pref_contact)?$ins_detail->pref_contact:'';
        $data['description'] = (isset($ins_detail->model_description)&&$ins_detail->model_description)?$ins_detail->model_description:'';
        $data['plan_name'] = $plan;
        $data['plan_description'] = $plan_description;
        $data['frequency'] = (isset($ins_detail->fname)&&$ins_detail->fname)?$ins_detail->fname:'';
        $data['last_call_date'] = (isset($ins_detail->last_cal_date)&&$ins_detail->last_cal_date)?date('d/M/Y',strtotime(str_replace('-','/',$ins_detail->last_cal_date))):'';
        $data['next_due_date'] = (isset($ins_detail->next_due_date)&&$ins_detail->next_due_date)?date('d/M/Y',strtotime(str_replace('-','/',$ins_detail->next_due_date))):'';
        $data['customer_name'] = (isset($ins_detail->customer_name)&&$ins_detail->customer_name)?$ins_detail->customer_name:'';
        $data['address'] = (isset($ins_detail->address1)&&$ins_detail->address1)?$ins_detail->address1:'';
        $data['contact_person'] = (isset($ins_detail->primary_contact)&&$ins_detail->primary_contact)?$ins_detail->primary_contact:'';
        $data['email'] = (isset($ins_detail->customer_email)&&$ins_detail->customer_email)?$ins_detail->customer_email:'';
        if (!$workorder) {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }

        return Response::json([
            'status' => 1,
            'data' => $data
        ], 200);
    }


}