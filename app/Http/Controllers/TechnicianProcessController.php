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
use App\Process;
use Validator;
use Carbon\Carbon;
use App\Servicerequest;
use View;
use PDF;
use App\Workorderprocessupdate;


class TechnicianProcessController extends Controller
{
    private $user;
    public $uid;
    public $cid;
    public $roleid;

    public function __construct(Technicianuser $user){

        $this->user = $user;
        $this->workorder = new Workorder();
        $this->technicianuser = new Technicianuser();
        $this->process = new Process();
        $this->service = new Servicerequest();
        $this->workorderProcess = new Workorderprocessupdate();
    }

    public function partslist(Request $request)
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

        $temp = array();
        $model_id = $reqInputs['model_id'];
        if($model_id)
        {
           $spares = $this->process->getModelSpares($model_id);
           if($spares)
           {
               $filePath = 'novamed/public/equipment_model/spares';
               $filePath1 = 'public/equipment_model/spares';
              foreach ($spares as $key=>$row)
              {
                  $temp[$key]['id'] = $row->id;
                  $temp[$key]['number'] = $row->sku_number;
                  $temp[$key]['name'] = $row->part_name;
                  $temp[$key]['price'] = $row->price;
                  if($row->image)
                  {
                      $large = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/large/' . $row->image; //print_r($large); die;
                      $medium = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/medium/' . $row->image;
                      $small = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/small/' . $row->image;
                      $original = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/original/' . $row->image;
                      $extraLarge = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/extraLarge/' . $row->image;
                      $icon = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/icon/' . $row->image;
                      $thumbnail = $_SERVER['DOCUMENT_ROOT'].'/' .$filePath.'/thumbnail/' . $row->image;

                      $largePath = env('file_path'). '/'. $filePath1.'large/'.$row->image;
                      $mediumPath = env('file_path') . '/' . $filePath1.'medium/'.$row->image;
                      $smallPath = env('file_path') . '/' . $filePath1.'small/'.$row->image;
                      $originalPath = env('file_path') . '/' . $filePath1.'original/'.$row->image;
                      $extraLargePath = env('file_path') . '/' . $filePath1.'extraLarge/'.$row->image;
                      $iconPath = env('file_path') . '/' . $filePath1.'icon/'.$row->image;
                      $thumbnailPath = env('file_path') . $filePath1.'thumbnail/'.$row->image;

                      if (file_exists($large) && file_exists($medium) && file_exists($small) && file_exists($original) && file_exists($extraLarge) && file_exists($icon) && file_exists($thumbnail)) {
                          $spareimage = array('largePath' => $largePath, 'mediumPath' => $mediumPath, 'smallPath' => $smallPath, 'originalPath' => $originalPath, 'extraLargePath' => $extraLargePath, 'iconPath' => $iconPath, 'thumbnailPath' => $thumbnailPath);
                      } else {
                          $filePath = 'novamed/public/equipment_model/spares/';
                          //$filePath = '/uploads/profile/';
                          $originalPath = env('file_path') . '/' . $filePath1 . '/original/default.png';
                          $spareimage = array('defaultImage' => $originalPath);
                      }
                  }
                  else
                  {
                      $filePath = 'novamed/public/equipment_model/spares/';
                      //$filePath = '/uploads/profile/';
                      $originalPath = env('file_path') . '/' . $filePath1 . '/original/noimage.png';
                      $spareimage = array('defaultImage' => $originalPath);
                  }

                  $temp[$key]['image'] = $spareimage;
              }


           }

            return Response::json([
                'status' => 1,
                'data' => $temp
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'msg' => 'model_id is missing'
            ], 400);
        }

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
        if(!$workorder)
        {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }
        $save['id'] = $workorderid;
        $save['work_progress'] = 2;
        $this->workorder->updateWorkorder($save);
        $details = $this->technicianuser->getWorkorderDetails($workorderid);
        $instruments = $this->workorder->totalInstruments($workorderid);
        $instrumentsLists = $this->technicianuser->instrumentLists($workorderid);
        $instrumentsarr = array();
        if($instrumentsLists)
        {
             foreach ($instrumentsLists as $inskey=>$insrow)
             {
                 $instrumentsarr[$inskey]['id'] = $insrow->work_order_item_id;
                 $instrumentsarr[$inskey]['asset_no'] = $insrow->asset_no;
                 $instrumentsarr[$inskey]['serial_no'] = $insrow->serial_no;
                 $instrumentsarr[$inskey]['model'] = $insrow->model_name;

             }
        }
        $workorderdetailsarr = array();
        $customerarr = array();
        if($details)
        {
            $workorderdetailsarr['workorder_number'] = $details->work_order_number;
            $workorderdetailsarr['totalinstruments'] = $instruments;
            $workorderdetailsarr['plan_name'] = $details->service_plan_name;
            if($details->status)
            {
                switch ($details->status)
                {
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
                    $status='Despatched';
                    break;
                }
            }
            else
            {
                $status = 'maintenance';
            }
            $workorderdetailsarr['current_step'] = $status;
            $asFound = $details->as_found==1?1:0;
            $asCalibrated = $details->as_calibrate==1?1:0;
            if($asFound && $asCalibrated)
            {
                $steps = array('As Found','Maintenance','Calibration','Despatched');
            }
            elseif($asFound && $asCalibrated==0)
            {
                $steps = array('As Found','Maintenance','Despatched');
            }
            elseif($asFound==0 && $asCalibrated)
            {
                $steps = array('Maintenance','Calibration','Despatched');
            }
            else
            {
                $steps = array('Maintenance','Despatched');
            }

            $customerarr['customer_name'] = $details->customer_name;
            $customerarr['address'] = $details->address1;
            $customerarr['contact_person'] = $details->contact_name;
            $customerarr['location'] = $details->location;
            $customerarr['email'] = $details->email_id;
            $customerarr['phone'] = $details->phone_no;

            return Response::json([
                'status' => 1,
                'data' => $workorderdetailsarr,
                'steps'=> $steps,
                'instruments'=>$instrumentsarr,
                'customer'=>$customerarr
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
        $select = array('W.id', 'W.work_order_number', 'W.plan_id', 'S.service_plan_name', 'C.customer_name', 'W.workorder_date','W.as_found','W.as_calibrated','W.status','cc.location','cc.contact_name','cc.email_id','cc.phone_no','W.modified_date');
        $workorders = $this->workorder->assignedworkorders($fParams['limit'], $fParams['offset'], array('select' => $select, 'search' => $fParams['keyword'],'week_or_month'=>$fParams['week_or_month'],'tid'=>$this->tid,'work_progress'=>2));

        $temp = array();
        if($workorders)
        {
            foreach ($workorders as $key=>$row)
            {
                // $temp[$key] = (array)$row;
                $temp[$key]['id'] = $row->id;
                $temp[$key]['workorder_number'] = $row->work_order_number;
                $temp[$key]['customer_name'] = $row->customer_name;
                $temp[$key]['plan_name'] = $row->service_plan_name;
                $temp[$key]['plan_id'] = $row->plan_id;
                $temp[$key]['job_assigned_date'] = date('m/d/Y',strtotime(str_replace('/','-',$row->workorder_date)));
                $temp[$key]['last_modified_date'] = date('m/d/Y',strtotime(str_replace('/','-',$row->modified_date)));
                $instruments = $this->workorder->totalInstruments($row->id);
                $temp[$key]['total_instruments'] = $instruments;
                $temp[$key]['customer_name'] = $row->customer_name;

                if($row->status)
                {
                    switch($row->status)
                    {
                        case 1:
                            $status='As Found';
                            break;
                        case 2:
                            $status='Maintenance';
                            break;
                        case 3:
                            $status ='Calibration';
                            break;
                        default:
                            $status = 'Maintenance';

                    }
                }
                else
                {
                    $status = 'Maintenance';
                }
                $temp[$key]['status'] = $status;

            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp
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
        $data['channel'] = isset($workordersitem->channel_name)?$workordersitem->channel_name:'';
        $data['opertaion'] = isset($workordersitem->operation_name)?$workordersitem->operation_name:'';
       // print_r($checklist);die;
        $temp = array();

        if($checklist)
        {
           foreach ($checklist as $key=>$row)
           {
               $item = $this->workorder->checklistItem($row->id);
               $temp[$key]['title'] = $row->title;
               $tempitem = array();
               foreach ($item as $ikey=>$irow)
               {
                   $tempitem[$ikey]['id'] = $irow->id;
                   $tempitem[$ikey]['title'] = $irow->title;
                   $tempitem[$ikey]['type'] = $irow->type;
                   $tempitem[$ikey]['selected'] = '';
                   $itemsub = $this->workorder->checklistItemSub($irow->id);
                   $tempitemsub = array();
                   foreach ($itemsub as $iskey=>$isrow)
                   {
                       $tempitemsub[$iskey]['id'] = $isrow->id;
                       $tempitemsub[$iskey]['title'] = $isrow->title;
                       $tempitemsub[$iskey]['type'] = $isrow->type;
                       $tempitemsub[$iskey]['selected'] = '';
                   }
                   $tempitem[$ikey]['sub_checklist'] = $tempitemsub;
               }
               $temp[$key]['checklists'] = $tempitem;
           }

        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'fields'=>$data
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
        if(!$workorder)
        {
            return Response::json([
                'status' => 0,
                'message' => "This workorder not found"
            ], 404);
        }

        $instrumentsLists = $this->technicianuser->instrumentLists($workorderid);
        $instrumentsarr = array();
        if($instrumentsLists)
        {
            foreach ($instrumentsLists as $inskey=>$insrow)
            {
                $instrumentsarr[$inskey]['id'] = $insrow->work_order_item_id;
                $instrumentsarr[$inskey]['asset_no'] = $insrow->asset_no;
                $instrumentsarr[$inskey]['serial_no'] = $insrow->serial_no;
                $instrumentsarr[$inskey]['model'] = $insrow->model_name;
                $instrumentsarr[$inskey]['model_id'] = $insrow->model_id;

            }

            return Response::json([
                'status' => 1,
                'data' => $instrumentsarr
            ], 200);
        }


    }

    function downloadpdf(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);

        if($user['signature']=='')
        {
            return Response::json([
                'status' => 0,
                'message' => 'Please upload your signature in my profile'
            ], 400);
        }
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
            'comments' => isset($reqInputs['comments'])?$reqInputs['comments']:'',
            'outside_calibrated' => isset($reqInputs['outside_calibrated'])?$reqInputs['outside_calibrated']:0,
            'document' => isset($reqInputs['document'])?$reqInputs['document']:''
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

        $work_order_item_id = $input['id'];

        $workorder_item = DB::table('tbl_work_order_items')->where([['id',$work_order_item_id]])->get()->first();
        
        if($workorder_item)
        {
            if($workorder_item->report)
            {

                return Response::json([
                    'status' => 1,
                    'message' => 'Success',
                    'pdf_url' => url('/public/report/technicianreview/' . $workorder_item->report)
                ], 200);
            }
        }

        $dueEquipments = DB::table('tbl_work_order_items as oi')->select('DE.id','ri.frequency_id','DE.pickup_date','DE.next_due_date','DE.frequency_id as f_id','DE.interval_days')->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as DE','DE.id','=','ri.due_equipments_id')->where('oi.id',$work_order_item_id)->first();

        if($dueEquipments)
        {
            if($dueEquipments->f_id)
            {
                $query = DB::table('tbl_frequency');
                $query->where('id', $dueEquipments->f_id);
                $query->select('no_of_days', 'id');
                $resultfre = $query->first();
                $next_due_date = date('Y-m-t', strtotime("+" . $resultfre->no_of_days . " months", strtotime(date("Y-m-d"))));
            }
            else
            {
                if($dueEquipments->pickup_date==1)
                {
                    $interval_days = (isset($dueEquipments->interval_days)&&$dueEquipments->interval_days)?$dueEquipments->interval_days:'';
                    if($interval_days)
                    {
                        $next_due_date =date('Y-m-d', strtotime("+" . $interval_days . " days", strtotime(date("Y-m-d"))));
                    }
                    else
                    {
                        $next_due_date = (isset($dueEquipments->next_due_date)&&$dueEquipments->next_due_date)?$dueEquipments->next_due_date:'';
                    }

                }
                else
                {
                    $next_due_date = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                }

            }
            $save_due_equip['id'] = $dueEquipments->id;
            $save_due_equip['last_cal_date'] = date('Y-m-d');
            $save_due_equip['next_due_date'] = $next_due_date;
            //$save_due_equip['calibrate_process'] = 0;
            $this->service->saveDueEqu($save_due_equip);

        }

        $data['comments'] = $input['comments'];
        $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_service_request as sr','sr.id','=','ri.service_request_id')
            ->join('tbl_work_order as wo','wo.request_id','=','sr.id')
            ->join('tbl_service_plan as sp','sp.id','=','wo.plan_id')
            ->join('tbl_technician as t','t.id','=','wo.calibration_to')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_customer as c','c.id','=','e.customer_id')
            ->join('tbl_customer_setups','tbl_customer_setups.customer_id','=','e.customer_id')
            ->select('tbl_customer_setups.cal_spec','e.*','c.*','em.model_name','em.model_description','de.last_cal_date','de.next_due_date','t.first_name as tfname','t.last_name as tlname', 'ri.id as servicerequestItemId', 'oi.id', 'oi.report', 'em.id as emodel_id','em.volume','em.volume_value','em.brand_operation','em.channel','sp.calibration_outside','sr.request_no')->where('oi.id',$work_order_item_id)->get()->first();

        $data['as_found_workorder'] = $this->service->workorder_status($work_order_item_id,1);
        $as_found_workorder_log = $this->service->workorder_asfound_log($work_order_item_id);
       // echo '<pre>';print_r($as_found_workorder_log);die;
        $model_id =  (isset($data['equipment_details']->emodel_id)&&$data['equipment_details']->emodel_id)?$data['equipment_details']->emodel_id:'';
        $volume_id = (isset($data['equipment_details']->volume) && $data['equipment_details']->volume) ? $data['equipment_details']->volume : '0';
        $test_points = array();
        if($input['outside_calibrated']!=1)
        {

            if($model_id)
            {
                //$test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id',$model_id)->get();

                switch($data['equipment_details']->cal_spec)
                {
                    case 1:
                        if($volume_id==1)
                        {
                            $spec = DB::table('tbl_iso_limit_tolerance');
                            $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
                            $spec->where([
                                ['channel_id', '=', $data['equipment_details']->channel],
                                ['operation_id', '=', $data['equipment_details']->brand_operation],
                                ['volume_id', '=', $data['equipment_details']->volume],
                                ['tbl_iso_specifications.volume_value', '=', $data['equipment_details']->volume_value],
                            ]);
                            $spec->select('tbl_iso_limit_tolerance.*');
                            $test_points = $spec->get();
                        }
                        else
                        {
                            $spec = DB::table('tbl_iso_limit_tolerance');
                            $spec->join('tbl_iso_specifications','tbl_iso_specifications.id','=','tbl_iso_limit_tolerance.specification_id');
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
                        $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id',$model_id)->get();
                        break;
                    default:
                        $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id',$model_id)->get();
                        break;
                }

            }

            $tempfound = array();
            $foundPassFail = array();
            $foundStatus = '';
            if($as_found_workorder_log)
            {
                foreach($as_found_workorder_log as $fkey=>$frow)
                {

                    if($frow->test_point_id==1)
                    {
                        $test_target = $test_points[0]->target_value;
                        $accuracy_percentage = (isset($test_points[0]->accuracy)&&$test_points[0]->accuracy)?$test_points[0]->accuracy:'';
                        $precision_percentage = (isset($test_points[0]->precision)&&$test_points[0]->precision)?$test_points[0]->precision:'';
                    }
                    elseif($frow->test_point_id==2)
                    {
                        $test_target = $test_points[1]->target_value;
                        $accuracy_percentage = (isset($test_points[1]->accuracy)&&$test_points[1]->accuracy)?$test_points[1]->accuracy:'';
                        $precision_percentage = (isset($test_points[1]->precision)&&$test_points[1]->precision)?$test_points[1]->precision:'';
                    }
                    elseif($frow->test_point_id==3)
                    {
                        $test_target = $test_points[2]->target_value;
                        $accuracy_percentage = (isset($test_points[2]->accuracy)&&$test_points[2]->accuracy)?$test_points[2]->accuracy:'';
                        $precision_percentage = (isset($test_points[2]->precision)&&$test_points[2]->precision)?$test_points[2]->precision:'';
                    }
                    else
                    {
                        $test_target = $test_points[0]->target_value;
                        $accuracy_percentage = (isset($test_points[0]->accuracy)&&$test_points[0]->accuracy)?$test_points[0]->accuracy:'';
                        $precision_percentage = (isset($test_points[0]->precision)&&$test_points[0]->precision)?$test_points[0]->precision:'';
                    }

                    $tempfound[$fkey]['test_target'] = $test_target;
                    $tempfound[$fkey]['channel'] = $frow->reading_channel;
                    $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
                    $tempfound[$fkey]['mean'] = $frow->reading_mean;
                    $tempfound[$fkey]['sd'] = $frow->reading_sd;
                    $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
                    $tempfound[$fkey]['target_accuracy'] = $accuracy_percentage;
                    $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
                    $tempfound[$fkey]['target_precision'] = $precision_percentage;
                    $tempfound[$fkey]['status'] = $frow->reading_status==1?'Passed':'Failed';
                    $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings);

                    if($frow->reading_status == 1)
                    {
                        $foundPassFail[$fkey] = "Passed";
                    }
                    else{
                        $foundPassFail[$fkey] = "Failed";
                    }

                    $save['id'] = $frow->id;
                    $save['target_accuracy'] = $accuracy_percentage;
                    $save['target_precision'] = $precision_percentage;
                    $this->workorderProcess->save_asfound_log($save,$this->uid);

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
            $data['calibrated_workorder'] = $this->service->workorder_status($work_order_item_id,3);

            //last and next due dates for device

            $data['balance_last_date'] = '';
            $data['balance_due_date'] = '';
            $data['barometer_last_date'] = '';
            $data['barometer_due_date'] = '';
            $data['thermometer_last_date'] = '';
            $data['thermometer_due_date'] = '';
            $data['thermocouple_last_date'] = '';
            $data['thermocouple_due_date'] = '';

            if($data['calibrated_workorder'])
            {
                $balance_device_id = (isset($data['calibrated_workorder']->balance_device_id)&&$data['calibrated_workorder']->balance_device_id)?$data['calibrated_workorder']->balance_device_id:1;
                $barometer_device_id = (isset($data['calibrated_workorder']->barometer_device_id)&&$data['calibrated_workorder']->barometer_device_id)?$data['calibrated_workorder']->barometer_device_id:1;
                $thermometer_device_id = (isset($data['calibrated_workorder']->thermometer_device_id)&&$data['calibrated_workorder']->thermometer_device_id)?$data['calibrated_workorder']->thermometer_device_id:1;
                $thermocouple_device_id = (isset($data['calibrated_workorder']->thermocouple_device_id)&&$data['calibrated_workorder']->thermocouple_device_id)?$data['calibrated_workorder']->thermocouple_device_id:1;

                $balance = DB::table('tbl_device')->where('id',$balance_device_id)->first();
                $barometer = DB::table('tbl_device')->where('id',$barometer_device_id)->first();
                $thermometer = DB::table('tbl_device')->where('id',$thermometer_device_id)->first();
                $thermocouple = DB::table('tbl_device')->where('id',$thermocouple_device_id)->first();

                $data['balance_last_date'] = (isset($balance->last_cal_date)&&$balance->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$balance->last_cal_date))):'';
                $data['balance_due_date'] = (isset($balance->next_due_date)&&$balance->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$balance->next_due_date))):'';

                $data['barometer_last_date'] = (isset($barometer->last_cal_date)&&$barometer->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$barometer->last_cal_date))):'';
                $data['barometer_due_date'] = (isset($barometer->next_due_date)&&$barometer->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$barometer->next_due_date))):'';

                $data['thermometer_last_date'] = (isset($thermometer->last_cal_date)&&$thermometer->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermometer->last_cal_date))):'';
                $data['thermometer_due_date'] = (isset($thermometer->next_due_date)&&$thermometer->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermometer->next_due_date))):'';

                $data['thermocouple_last_date'] = (isset($thermocouple->last_cal_date)&&$thermocouple->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermocouple->last_cal_date))):'';
                $data['thermocouple_due_date'] = (isset($thermocouple->next_due_date)&&$thermocouple->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermocouple->next_due_date))):'';
            }

            $calibrated_workorder_log = $this->service->workorder_calibrated_log($work_order_item_id);
            $tempcalibrated = array();
            $tempcalibrated = array();
            $calibratedPassFail = array();
            $calibratedStatus = '';
            if($calibrated_workorder_log)
            {
                foreach($calibrated_workorder_log as $ckey=>$crow)
                {
                    if($crow->test_point_id==1)
                    {
                        $test_target = $test_points[0]->target_value;
                        $accuracy_percentage = (isset($test_points[0]->accuracy)&&$test_points[0]->accuracy)?$test_points[0]->accuracy:'';
                        $precision_percentage = (isset($test_points[0]->precision)&&$test_points[0]->precision)?$test_points[0]->precision:'';
                    }
                    elseif($crow->test_point_id==2)
                    {
                        $test_target = $test_points[1]->target_value;
                        $accuracy_percentage = (isset($test_points[1]->accuracy)&&$test_points[1]->accuracy)?$test_points[1]->accuracy:'';
                        $precision_percentage = (isset($test_points[1]->precision)&&$test_points[1]->precision)?$test_points[1]->precision:'';
                    }
                    elseif($crow->test_point_id==3)
                    {
                        $test_target = $test_points[2]->target_value;
                        $accuracy_percentage = (isset($test_points[2]->accuracy)&&$test_points[2]->accuracy)?$test_points[2]->accuracy:'';
                        $precision_percentage = (isset($test_points[2]->precision)&&$test_points[2]->precision)?$test_points[2]->precision:'';
                    }
                    else
                    {
                        $test_target = $test_points[0]->target_value;
                        $accuracy_percentage = (isset($test_points[0]->accuracy)&&$test_points[0]->accuracy)?$test_points[0]->accuracy:'';
                        $precision_percentage = (isset($test_points[0]->precision)&&$test_points[0]->precision)?$test_points[0]->precision:'';
                    }

                    $tempcalibrated[$ckey]['test_target'] = $test_target;
                    $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
                    $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
                    $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
                    $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
                    $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
                    $tempcalibrated[$ckey]['target_accuracy'] = $accuracy_percentage;
                    $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
                    $tempcalibrated[$ckey]['target_precision'] = $precision_percentage;
                    $tempcalibrated[$ckey]['status'] = $crow->reading_status==1?'Passed':'Failed';
                    $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);

                    if($crow->reading_status == 1)
                    {
                        $calibratedPassFail[$ckey] = "Passed";
                    }
                    else{
                        $calibratedPassFail[$ckey] = "Failed";
                    }

                    $save['id'] = $crow->id;
                    $save['target_accuracy'] = $accuracy_percentage;
                    $save['target_precision'] = $precision_percentage;
                    $this->workorderProcess->save_ascalibrated_log($save,$this->uid);

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
            $data['reviewer'] = 'technician';
            $data['tech_signature'] = $user['signature'];
            $data['tech_date'] = date('Y-m-d');
            // print_r($data);die;
            //echo '<pre>';print_r($data);die;

            $data['maintenance_workorder'] = $this->service->workorder_status($work_order_item_id, 2);

            $maintenance_data = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id','=',$data['maintenance_workorder']->id)->first();
            //maintenance data
            $maintenance = (isset($maintenance_data->workorder_checklists)&&$maintenance_data->workorder_checklists)?$maintenance_data->workorder_checklists:'';
            $performed = array();
            if($maintenance)
            {
                $checklist_array = explode(',',$maintenance);
                if($checklist_array)
                {
                    foreach($checklist_array as $key=>$row)
                    {
                        $checklist_id = str_replace('~','',$row);
                        $checklist = DB::table('tbl_checklist_item')->where('id','=',$checklist_id)->first();
                        $performed[$key]=isset($checklist->title)?$checklist->title:'';

                    }
                }
            }

            $data['performed'] = implode(',',$performed);

            //parts data
            $parts = (isset($maintenance_data->workorder_spares)&&$maintenance_data->workorder_spares)?$maintenance_data->workorder_spares:'';
            $decoded_parts = json_decode($parts);
            $parts_replaced = array();

            if($decoded_parts)
            {
                foreach ($decoded_parts as $key=>$row)
                {
                    $part_id = $row->id;
                    $part = DB::table('tbl_equipment_model_spares')->where('id','=',$part_id)->first();
                    $parts_replaced[$key]=isset($part->part_name)?$part->part_name:'';

                }
            } //print_r($parts_replaced);die;
            //$data['parts_replaced'] = implode(',',$parts_replaced);
            $data['parts_replaced'] = $parts_replaced;

            $path = base_path() . '/public/report/technicianreview';
            $reportFile = 'report-' . uniqid();
            view()->share($data);

//        PDF::setOptions(['isPhpEnabled'=>true,'isHtml5ParserEnabled'=>true]);
//        $pdf = PDF::loadView('report.report')
//            ->save($path . '/' . $reportFile . '.pdf', 'F');
            //  echo '<pre>';print_r($pdf);die;
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);

            $pdf->loadView('report.report')->save($path . '/' . $reportFile . '.pdf', 'F');
            //echo '<pre>';print_r($data);die;
            $pathToFile = base_path() . '/public/report/technicianreview/' . $reportFile . '.pdf';

            $headers = array(
                'Content-Type: application/pdf',
            );
        }

        else
        {
            $document = (isset($input['document']) && $input['document'])?$input['document']:'';
            $mainPath = public_path() . '/report/technicianreview/'; //print_r($mainPath);die;
            $location = $mainPath;
            $trimmedLocation = str_replace('\\', '/', $location);
            if ($document) {
                $offset1 = strpos($document, ',');
                $tmp = base64_decode(substr($document, $offset1));
                $memType = $this->_file_mime_type($tmp);
                $fileType = explode('/', $memType);
                $fileType = $fileType[1];
                $filepath = $trimmedLocation;
                if (!is_dir($filepath)) {
                    return Response::json([
                        'status' => 0,
                        'message' => 'The path you given was invalid'
                    ], 400);
                }
                $docuName = 'report-' . uniqid();
                $uploadedFile = file_put_contents($filepath . '/' . $docuName.'.pdf', $tmp);

                if ($uploadedFile) {
                    $reportFile = $docuName;
                } else {
                    $reportFile = $docuName;
                }
            }
            else
            {
                $reportFile = '';
            }
            $pathToFile = base_path() . '/public/report/technicianreview/' . $reportFile . '.pdf';
            $headers = array(
                'Content-Type: application/pdf',
            );
        }

        $save_report['id'] = $input['id'];
        $save_report['report'] = $reportFile.'.pdf';
        $save_report['technician_review'] = $user['id'];
        $save_report['technician_review_date'] = date('Y-m-d');
        $save_report['comments'] = $input['comments'];
        $this->service->save_workorder_items($save_report);


         Response::download($pathToFile, 'report.pdf', $headers);
        return Response::json([
            'status' => 1,
            'message' => 'Success',
            'pdf_url' => url('/public/report/technicianreview/' . $reportFile . '.pdf')
        ], 200);


    }

    protected function _file_mime_type($file)
    {
        // We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
        $regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

        /* Fileinfo extension - most reliable method
         *
         * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
         * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
         */
        if (function_exists('finfo_buffer')) {

            $finfo = @finfo_open(FILEINFO_MIME);
            if (is_resource($finfo)) // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
            {
                $mime = @finfo_buffer($finfo, $file);
                finfo_close($finfo);

                /* According to the comments section of the PHP manual page,
                 * it is possible that this function returns an empty string
                 * for some files (e.g. if they don't exist in the magic MIME database)
                 */
                if (is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $file_type = $matches[1];
                    return $file_type;
                }
            }
        }

    }

    public function completeworkorder(Request $request)
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
            'work_order_id' => $reqInputs['work_order_id'],
            'confirm' => isset($reqInputs['confirm'])?$reqInputs['confirm']:0
        ];
        $rules = array(

            'work_order_id' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
           return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }
        $workOrderId = $input['work_order_id'];
        //check work order items and despatch items counts are same
        $allWorkorderItems = DB::table('tbl_work_order_items as oi')->select('id')->where('work_order_id',$workOrderId)->get()->count();
        $despatchItems = DB::table('tbl_work_order_items as oi')->select('id')->where([['work_order_id',$workOrderId],['as_calibrated_status','completed']])->get()->count();
        //not match
        if(!$input['confirm'])
        {
            if($allWorkorderItems!=$despatchItems)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Some instruments are not complete. Do you want to force complete?'
                ], 200);
            }
            else
            {
                return Response::json([
                    'status' => 1,
                    'message' => 'Are you sure to complete the workorder?'
                ], 200);
            }
        }
        if($allWorkorderItems!=$despatchItems)
        {
           $force_complete = 1;
        }
        else
        {
            $force_complete = 0;
        }

        $saveprocess['id'] = $workOrderId;
        $saveprocess['work_progress'] = 3;
        $saveprocess['status'] = 5;
        $saveprocess['force_complete'] = $force_complete;
        $this->workorder->saveWorkOrderComplete($saveprocess);

        return Response::json([
            'status' => 1,
            'msg' => 'Workorder has been completed'
        ], 200);

    }
}