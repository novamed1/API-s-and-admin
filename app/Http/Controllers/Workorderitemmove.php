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
use App\Models\Device;

use App\Process;
use Validator;
use Carbon\Carbon;
use App\Workorderprocessupdate;
use App\Workorderitemmovemodel;
use PDF;

class Workorderitemmove extends Controller
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
        $this->process = new Process();
        $this->workorderProcess = new Workorderprocessupdate();
        $this->device = new Device();
        $this->workorderitemmove = new Workorderitemmovemodel();

    }

    function workorderitemmove(Request $request)
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
        $reqInputs = $request->json()->all(); //echo'<pre>';print_r($reqInputs);'</pre>';die;
        $input = [
            'work_order_item_id' => $reqInputs['work_order_item_id'],
            'work_order_status' => $reqInputs['work_order_status']
        ];
        $rules = array(
            'work_order_item_id' => 'required|not_in:0',
            'work_order_status' => 'required|not_in:0'
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
        $workorder_item_id = $input['work_order_item_id'];
        $workorder_status = $input['work_order_status'];
        $work_order_item = $this->workorderitemmove->workorderitem($workorder_item_id);
        $work_order = $this->workorder->workorder($work_order_item->work_order_id);

        //check for same technician in maintenance and calibration
        if($work_order)
        {
           if($work_order->maintanence_to!=$work_order->calibration_to)
           {
               return Response::json([
                   'status' => 0,
                   'message' => 'You cannot move this item'
               ], 500);
           }
        }

        $statusArray = array('completed', 'progress', 'none');
        $details = $this->technicianuser->getWorkorderDetails($work_order_item->work_order_id);

        $workorder_items = $this->workorder->totalInstruments($work_order_item->work_order_id);
        $workorder_items_move = $this->workorder->totalInstrumentsWorkOrderProcess($work_order_item->work_order_id,$workorder_status);
        $servicePlanDetails = DB::table('tbl_service_plan')->where('id',$work_order->plan_id)->first();
        if ($workorder_status == 1) {

            $workorder_status_data = $this->workorderitemmove->work_order_status_move($workorder_item_id,1);
            if(empty($workorder_status_data))
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'You cannot move item without doing the process'
                ], 500);
            }

            if ($work_order->maintanence_to == $this->tid) {
                $save['id'] = $workorder_item_id;
                $save['as_found_status'] = 'completed';
                $save['maintenance_status'] = 'progress';
                $this->workorderitemmove->saveWorkOrderItemStatus($save);
            } else {
                $save['id'] = $workorder_item_id;
                $save['as_found_status'] = 'completed';
                $save['maintenance_status'] = 'progress';
                $this->workorderitemmove->saveWorkOrderItemStatus($save);

            }

            if($workorder_items==$workorder_items_move)
            {
                $saveWorkorderStatus['id'] = $work_order_item->work_order_id;
                $saveWorkorderStatus['status'] = 2;
                $this->workorder->saveWorkOrderStatus($saveWorkorderStatus);
            }

            $status_msg = 'Next process is maintenance';




        } elseif ($workorder_status == 2) {
            $workorder_status_data = $this->workorderitemmove->work_order_status_move($workorder_item_id,2);
            if(empty($workorder_status_data))
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'You cannot move item without doing the process'
                ], 500);
            }
            if ($work_order->as_calibrated == 1) {
                if ($work_order->calibration_to == $this->tid) {
                    $save['id'] = $workorder_item_id;
                    $save['maintenance_status'] = 'completed';
                    $save['as_calibrated_status'] = 'progress';
                    $this->workorderitemmove->saveWorkOrderItemStatus($save);
//sending status to the next step
                    //workorder auto  move if instruments counts match
                    if($workorder_items==$workorder_items_move)
                    {
                        $saveWorkorderStatus['id'] = $work_order_item->work_order_id;
                        $saveWorkorderStatus['status'] = 3;
                        $this->workorder->saveWorkOrderStatus($saveWorkorderStatus);
                    }

                } else {
//                    $save['id'] = $workorder_item_id;
//                    $save['maintenance_status'] = 'completed';
//                    $save['as_calibrated_status'] = 'progress';
//                    $this->workorderitemmove->saveWorkOrderItemStatus($save);

                    return Response::json([
                        'status' => 0,
                        'message' => 'You cannot move this item, because this process is assigned to others'
                    ], 500);

                }
                $status_msg = 'Next process is calibration';
            } else {
                $save['id'] = $workorder_item_id;
                $save['as_calibrated_status'] = 'completed';
                $save['despatched_status'] = 'progress';
                $this->workorderitemmove->saveWorkOrderItemStatus($save);
                $status_msg = 'Next process is dispatched';

                //workorder auto  move if instruments counts match
                if($workorder_items==$workorder_items_move)
                {
                    $saveWorkorderStatus['id'] = $work_order_item->work_order_id;
                    $saveWorkorderStatus['status'] = 4;
                    $this->workorder->saveWorkOrderStatus($saveWorkorderStatus);
                }
            }

        } else {
            $save['id'] = $workorder_item_id;
            $save['as_calibrated_status'] = 'completed';
            $save['despatched_status'] = 'progress';
            $this->workorderitemmove->saveWorkOrderItemStatus($save);
            $status_msg = 'Next process is dispatched';

            //workorder auto  move if instruments counts match
            if($workorder_items==$workorder_items_move)
            {
                $saveWorkorderStatus['id'] = $work_order_item->work_order_id;
                $saveWorkorderStatus['status'] = 4;
                $this->workorder->saveWorkOrderStatus($saveWorkorderStatus);
            }

        }

        $instrument = $this->workorderitemmove->getInstrument($work_order_item->work_order_id);
        //echo'<pre>';print_r($instrument);'</pre>';die;

        foreach ($instrument as $inskey => $insrow) {
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
            $processUpdation = $this->workorder->checkProcessUpdation($insrow->work_order_item_id, $details->status);
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

        //overall response

        return Response::json([
            'status' => 1,
            'instrument' => $instrumentsarr,
            'message' => $status_msg,
            'work_order_item_id' => $workorder_item_id
        ], 200);

    }

   function consolidateReport(Request $request)
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
       $reqInputs = $request->json()->all(); //echo'<pre>';print_r($reqInputs);'</pre>';die;
       $input = [
           'work_order_id' => $reqInputs['work_order_id'],
           'comments' => $reqInputs['comments'],
           'outside_calibrated' => isset($reqInputs['outside_calibrated'])?$reqInputs['outside_calibrated']:0
       ];
       $rules = array(
           'work_order_id' => 'required|not_in:0'
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

      $workOrderId = $input['work_order_id'];
      $work_order = $this->workorder->getParticularWorkOrder($workOrderId);
      if(empty($work_order))
      {
          return Response::json([
              'status' => 0,
              'message' => "This workorder is not found"
          ], 404);
      }
       $calibrated_technician = DB::table('tbl_technician')->where('id', $work_order->calibration_to)->first();
       $work_order_items = $this->workorder->totalInstruments($workOrderId);
       $work_order_items_calibrated = $this->workorder->totalInstrumentsCalibrated($workOrderId);
//      if($work_order_items!=$work_order_items_calibrated)
//      {
//          return Response::json([
//              'status' => 0,
//              'message' => "Some instruments in this workorder are not calibrated. please calibrate it and try"
//          ], 500);
//      }

      $customer = DB::table('tbl_customer as c')
          ->join('tbl_service_request as sr','sr.customer_id','=','c.id')
          ->where('sr.id','=',$work_order->request_id)->get()->first();
      $digital_barometer = $this->workorderitemmove->getTechnicianDeviceBarometer(2,$this->tid,$workOrderId);
      $digital_thermometer = $this->workorderitemmove->getTechnicianDeviceThermometer(3,$this->tid,$workOrderId);
      $digital_thermocouple = $this->workorderitemmove->getTechnicianDeviceThermocouple(4,$this->tid,$workOrderId);
      $digital_balance = $this->workorderitemmove->getTechnicianDeviceBalance(1, $this->tid, $workOrderId);
      $workorder_calibration = $this->workorderitemmove->getWorkorderCalibration($workOrderId);
      $calibration_data = array();
       $multicalibration_data = array();
      $data = array();
      if($input['outside_calibrated']!=1)
      {
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
                  $calibration_data[$key]['technician_name'] = (isset($calibrated_technician->first_name)&&$calibrated_technician->first_name)?$calibrated_technician->first_name:'';
                  $calibration_data[$key]['reported_date'] = $row->technician_review_date?date('d-M-Y',strtotime(str_replace('/','-',$row->technician_review_date))):'Not applicable';

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
          //echo '<pre>';print_r($calibration_data);die;
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
          $data['comments'] = $input['comments'];
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
          $data['uploaded_signature'] = $user->signature;
          //$data['certificate_num'] = 122910;
          //print_r($data);die;

          $path = base_path() . '/public/report/consolidate/technicianreview';
          $reportFile = 'consolidate_report-' . uniqid();

          view()->share($data);


          $pdf = app('dompdf.wrapper');
          $pdf->getDomPDF()->set_option("enable_php", true);
          $pdf->loadView('report.consolidatetechnicianreport')->save($path . '/' . $reportFile . '.pdf', 'F');

          //print_r($path);die;
          $headers = array(
              'Content-Type: application/pdf',
          );

          $save_report['id'] = $input['work_order_id'];
          $save_report['report'] = $reportFile.'.pdf';
          $save_report['technician_review'] = $user['id'];
          $save_report['technician_review_date'] = date('Y-m-d');
          $save_report['comments'] = $input['comments'];
          $this->workorderitemmove->saveWorkOrder($save_report);

          $pdf_url = url('/public/report/consolidate/technicianreview/' . $reportFile . '.pdf');
          //Response::download($path, $reportFile.'.pdf', $headers);

      }

      else
      {
          $save_report['id'] = $input['work_order_id'];
          $save_report['technician_review'] = $user['id'];
          $save_report['technician_review_date'] = date('Y-m-d');
          $save_report['comments'] = $input['comments'];
          $this->workorderitemmove->saveWorkOrder($save_report);
          $pdf_url = '';
      }

       return Response::json([
           'status' => 1,
           'message' => 'Success',
           'pdf_url' => $pdf_url
       ], 200);

   }

}