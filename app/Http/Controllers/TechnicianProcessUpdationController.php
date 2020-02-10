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
use App\Servicerequest;
use App\Http\Controllers\OutsideCalibration;

class TechnicianProcessUpdationController extends Controller
{
    public $user;
    public $uid;
    public $cid;
    public $roleid;
    protected static $workorder_item_id;
    protected static $user_id;
    protected static $despatch_items;
    protected $workorderoutside;


    public function __construct(Technicianuser $user){

        $this->user = $user;
        $this->workorder = new Workorder();
        $this->technicianuser = new Technicianuser();
        $this->process = new Process();
        $this->workorderProcess = new Workorderprocessupdate();
        $this->device = new Device();
        $this->workorderitemmove = new Workorderitemmovemodel();
        $this->outsidecalibration = new OutsideCalibration($this->user);
        $this->workorderoutside = new Workorderitemmovemodel();
        $this->serviceModel = new Servicerequest();
     }

    public function savemaintenance(Request $request)
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
        $reqInputs = $request->json()->all(); //print_r($reqInputs);die;
        $checklistArray =(isset($reqInputs['checklists']) && isset($reqInputs['checklists']))?$reqInputs['checklists']:'';
        $partsArray =(isset($reqInputs['parts']) && isset($reqInputs['parts']))?$reqInputs['parts']:'';
        $work_order_item_id =(isset($reqInputs['work_order_item_id']) && isset($reqInputs['work_order_item_id']))?$reqInputs['work_order_item_id']:'';
        $tempCheklist = array();
        $tempParts = array();
        $checkedChecklists = '';
        $addedParts = '';

        $savestatus['id'] = false;
        $savestatus['workorder_item_id'] = $work_order_item_id;
        $savestatus['workorder_status'] = 2;
        $savestatus['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
        $savestatus['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
        $savestatus['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
        $savestatus['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
        $savestatus['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
        $savestatus['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';
        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,$this->uid);

        if($checklistArray)
        {
           foreach ($checklistArray as $key=>$row)
           {
               $tempCheklist[$key] = '~'.$row['id'].'~';
           }
        }
        $checkedChecklists = implode(',',$tempCheklist);
        if($partsArray)
        {
            foreach($partsArray as $pkey=>$prow)
            {
                $query= DB::table('tbl_equipment_model_spares')->select('service_price as price')->where('id','=',$prow['id'])->first();
                $price = (isset($query->price) && $query->price)?$query->price:0;
                $amount = $prow['quantity'] * $price;
                $tempParts[$pkey] = (array) $prow;
                $tempParts[$pkey]['amount'] = $amount;
            }
        }
        $addedParts = json_encode($tempParts); //print_r($addedParts);die;

        $deletequery = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id',$save_status_updation)->delete();

        $save['id'] = false;
        $save['workorder_status_id'] = $save_status_updation;
        $save['workorder_checklists'] = $checkedChecklists;
        $save['workorder_spares'] = $addedParts;
        $save['cali_date'] = date('Y-m-d');
        $save_status = $this->workorderProcess->save_maintenance_log($save,$this->uid);
        if($save_status)
        {
            return Response::json([
                'status' => 1,
                'msg' => 'Maintenance has been updated'
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'msg' => 'Something went wrong'
            ], 500);
        }

    }

//    public function saveasfound(Request $request)
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
//        $technician = $this->technicianuser->getUserTechnician($user['id']);
//
//        $this->uid = $user['id'];
//        $this->tid = $technician->id;
//        $reqInputs = $request->json()->all(); //echo'<pre>';print_r($reqInputs);'</pre>';die;
//        $channels =(isset($reqInputs['channel_readings']) && isset($reqInputs['channel_readings']))?$reqInputs['channel_readings']:'';
//        $work_order_item_id = (isset($reqInputs['work_order_item_id']) && isset($reqInputs['work_order_item_id']))?$reqInputs['work_order_item_id']:'';
//
//
//        $savestatus['id'] = false;
//        $savestatus['workorder_item_id'] = $work_order_item_id;
//        $savestatus['workorder_status'] = 1;
//        $savestatus['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
//        $savestatus['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
//        $savestatus['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
//        $savestatus['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
//        $savestatus['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
//        $savestatus['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';
//        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,$this->uid);
//
//        $deletequery = DB::table('tbl_workorder_asfound_log')->where('workorder_status_id',$save_status_updation)->delete();
//
//        if($channels)
//        {
//            foreach ($channels as $key=>$row)
//            {
//               $testPoints = (isset($row['test_points']) && isset($row['test_points']))?$row['test_points']:'';
//               if($testPoints)
//               {
//                    foreach($testPoints as $tkey=>$trow)
//                    {
//                        $document = (isset($trow['reading_file']) && $trow['reading_file'])?$trow['reading_file']:'';
//                        $mainPath = public_path() . '/technician/as_found/';
//                        $location = $mainPath;
//                        $trimmedLocation = str_replace('\\', '/', $location);
//                        if ($document) {
//                            $offset1 = strpos($document, ',');
//                            $tmp = base64_decode(substr($document, $offset1));
//                            $memType = $this->_file_mime_type($tmp);
//                            $fileType = explode('/', $memType);
//                            $fileType = $fileType[1];
////                $imagesize=getimagesize($tmp);
////                $width=$imagesize[0];
////                $height=$imagesize[1];
//                            $docuName = 'Readingdocs' . '-' . uniqid() . '.' . $fileType;
//
//                            //    image upload
//                            //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);
//
//                            $filepath = $trimmedLocation;
//                            if (!is_dir($filepath)) {
//                                return Response::json([
//                                    'status' => 0,
//                                    'message' => 'The path you given was invalid'
//                                ], 400);
//                            }
//                            $uploadedFile = file_put_contents($filepath . '/' . $docuName, $tmp);
//
//                            if ($uploadedFile) {
//                                $document_name = $docuName;
//                            } else {
//                                $document_name = '';
//                            }
//                        }
//                        else
//                        {
//                            $document_name = '';
//                        }
//
//                        $savelog['id'] = false;
//                        $savelog['workorder_status_id'] = $save_status_updation;
//                        $savelog['reading_channel'] = $row['channel'];
//                        $savelog['test_point_id'] = $trow['test_point_id'];
//                        $savelog['sample_readings'] = json_encode($trow['sample_reading']);
//                        $savelog['reading_mean'] = $trow['reading_mean'];
//                        $savelog['reading_sd'] = $trow['reading_sd'];
//                        $savelog['reading_unc'] = $trow['reading_unc'];
//                        $savelog['reading_accuracy'] = $trow['reading_accuracy'];
//                        $savelog['reading_precision'] = $trow['reading_precision'];
//                        $savelog['reading_status'] = $trow['reading_status'];
//                        $savelog['reading_document'] = $document_name;
//                        $save_status_log = $this->workorderProcess->save_asfound_log($savelog,$this->uid);
//                    }
//               }
//            }
//        }
//
//        return Response::json([
//            'status' => 1,
//            'msg' => 'As found has been updated'
//        ], 200);
//
//
//
//
//
//    }

    public function saveasfound(Request $request)
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
        $channels =(isset($reqInputs['channel_readings']) && isset($reqInputs['channel_readings']))?$reqInputs['channel_readings']:'';
        //echo'<pre>';print_r($channels);'</pre>';die;
        $work_order_item_id = (isset($reqInputs['work_order_item_id']) && isset($reqInputs['work_order_item_id']))?$reqInputs['work_order_item_id']:'';
        $work_order = $this->workorderProcess->getWorkorder($work_order_item_id);
        $outside_calibrated = (isset($reqInputs['outside_calibrated']) && isset($reqInputs['outside_calibrated']))?$reqInputs['outside_calibrated']:'';
        if($outside_calibrated==1)
        {
            self::$workorder_item_id= $work_order_item_id;
            self::$user_id = $this->uid;
            $this->outsidecalibration->saveasfoundoutside($user);
            return Response::json([
                'status' => 1,
                'msg' => 'As found has been updated'
            ], 200);
        }

        $balance_device_id = (isset($reqInputs['balance']) && isset($reqInputs['balance']))?$reqInputs['balance']:'';
        $barometer_device_id = (isset($reqInputs['digital_barometer']) && isset($reqInputs['digital_barometer']))?$reqInputs['digital_barometer']:'';
        $thermometer_device_id = (isset($reqInputs['digital_thermometer']) && isset($reqInputs['digital_thermometer']))?$reqInputs['digital_thermometer']:'';
        $thermocouple_device_id = (isset($reqInputs['thermocouple']) && isset($reqInputs['thermocouple']))?$reqInputs['thermocouple']:'';
        $balance_device_serial_no = '';
        $barometer_device_serial_no = '';
        $thermometer_device_serial_no = '';
        $thermocouple_device_serial_no = '';
        $balance_sensitivity = '';
        $balance_units = '';
        if($balance_device_id)
        {
            $balance_device = $this->workorder->getdevice($balance_device_id);
            $balance_device_serial_no = ($balance_device && isset($balance_device->serial_no))?$balance_device->serial_no:'';
            $balance_sensitivity = ($balance_device && isset($balance_device->sensitivity_name))?$balance_device->sensitivity_name:'';
            $balance_units = ($balance_device && isset($balance_device->unit))?$balance_device->unit:'';
        }
        if($barometer_device_id)
        {
            $barometer_device = $this->workorder->getdevice($barometer_device_id);
            $barometer_device_serial_no = ($barometer_device && isset($barometer_device->serial_no))?$barometer_device->serial_no:'';
        }
        if($thermometer_device_id)
        {
            $thermometer_device = $this->workorder->getdevice($thermometer_device_id);
            $thermometer_device_serial_no = ($thermometer_device && isset($thermometer_device->serial_no))?$thermometer_device->serial_no:'';
        }
        if($thermocouple_device_id)
        {
            $thermocouple_device = $this->workorder->getdevice($thermocouple_device_id);
            $thermocouple_device_serial_no = ($thermocouple_device && isset($thermocouple_device->serial_no))?$thermocouple_device->serial_no:'';
        }


        if($work_order)
        {
            if($work_order->water_temperature=='' && $work_order->relevent_humidity=='' && $work_order->barometric_pressure=='' && $work_order->air_dencity=='' && $work_order->z_factor=='' && $work_order->liquid_dencity=='')
            {

                $savew['id'] = $work_order->id;
                $savew['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
                $savew['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
                $savew['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
                $savew['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
                $savew['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
                $savew['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';

                $savew['balance_device_id'] = $balance_device_id;
                $savew['barometer_device_id'] = $barometer_device_id;
                $savew['thermometer_device_id'] = $thermometer_device_id;
                $savew['thermocouple_device_id'] = $thermocouple_device_id;
                $savew['balance_device_serial_no'] = $balance_device_serial_no;
                $savew['barometer_device_serial_no'] = $barometer_device_serial_no;
                $savew['thermometer_device_serial_no'] = $thermometer_device_serial_no;
                $savew['thermocouple_device_serial_no'] = $thermocouple_device_serial_no;
                $savew['balance_sensitivity'] = $balance_sensitivity;
                $savew['balance_units'] = $balance_units;
                $this->workorderProcess->saveWorkOrder($savew);
            }
        }


        $savestatus['id'] = false;
        $savestatus['workorder_item_id'] = $work_order_item_id;
        $savestatus['workorder_status'] = 1;
        $savestatus['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
        $savestatus['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
        $savestatus['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
        $savestatus['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
        $savestatus['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
        $savestatus['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';
        $savestatus['balance_device_id'] = $balance_device_id;
        $savestatus['barometer_device_id'] = $barometer_device_id;
        $savestatus['thermometer_device_id'] = $thermometer_device_id;
        $savestatus['thermocouple_device_id'] = $thermocouple_device_id;
        $savestatus['balance_device_serial_no'] = $balance_device_serial_no;
        $savestatus['barometer_device_serial_no'] = $barometer_device_serial_no;
        $savestatus['thermometer_device_serial_no'] = $thermometer_device_serial_no;
        $savestatus['thermocouple_device_serial_no'] = $thermocouple_device_serial_no;
        $savestatus['balance_sensitivity'] = $balance_sensitivity;
        $savestatus['balance_units'] = $balance_units;

        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,$this->uid);

        $deletequery = DB::table('tbl_workorder_asfound_log')->where('workorder_status_id',$save_status_updation)->delete();

        if($channels)
        {
             $testPoints = $channels;
                if($testPoints)
                {
                    foreach($testPoints as $tkey=>$trow)
                    {
                        $document = (isset($trow['reading_file']) && $trow['reading_file'])?$trow['reading_file']:'';
                        $mainPath = public_path() . '/technician/as_found/'; //print_r($mainPath);die;
                        $location = $mainPath;
                        $trimmedLocation = str_replace('\\', '/', $location);
                        if ($document) {
                            $offset1 = strpos($document, ',');
                            $tmp = base64_decode(substr($document, $offset1));
                            $memType = $this->_file_mime_type($tmp);
                            $fileType = explode('/', $memType);
                            $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                            $docuName = 'Readingdocs' . '-' . uniqid() . '.' . $fileType;

                            //    image upload
                            //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                            $filepath = $trimmedLocation;
                            if (!is_dir($filepath)) {
                                return Response::json([
                                    'status' => 0,
                                    'message' => 'The path you given was invalid'
                                ], 400);
                            }
                            $uploadedFile = file_put_contents($filepath . '/' . $docuName, $tmp);

                            if ($uploadedFile) {
                                $document_name = $docuName;
                            } else {
                                $document_name = '';
                            }
                        }
                        else
                        {
                            $document_name = '';
                        }

                        $savelog['id'] = false;
                        $savelog['workorder_status_id'] = $save_status_updation;
                        $savelog['reading_channel'] = $trow['channel_id'];
                        $savelog['test_point_id'] = $trow['test_point_id'];
                        $savelog['sample_readings'] = json_encode($trow['sample_reading']);
                        $savelog['reading_mean'] = $trow['reading_mean'];
                        $savelog['reading_sd'] = $trow['reading_sd'];
                        $savelog['reading_unc'] = $trow['reading_unc'];
                        $savelog['reading_mean_volume'] = $trow['reading_mean_volume'];
                        $savelog['reading_accuracy'] = $trow['reading_accuracy'];
                        $savelog['reading_precision'] = $trow['reading_precision'];
                        $savelog['reading_status'] = $trow['reading_status']=='pass'?1:0;
                        $savelog['reading_document'] = $document_name;
                        $savelog['cali_date'] = date('Y-m-d');
                        $save_status_log = $this->workorderProcess->save_asfound_log($savelog,$this->uid);
                    }
                }

        }

        return Response::json([
            'status' => 1,
            'msg' => 'As found has been updated'
        ], 200);





    }


    public function saveascalibrated(Request $request)
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
        $channels =(isset($reqInputs['channel_readings']) && isset($reqInputs['channel_readings']))?$reqInputs['channel_readings']:'';
        //echo '<pre>';print_r($channels);die;
        $work_order_item_id = (isset($reqInputs['work_order_item_id']) && isset($reqInputs['work_order_item_id']))?$reqInputs['work_order_item_id']:'';
        $work_order = $this->workorderProcess->getWorkorder($work_order_item_id);


        /*Last cal date and update*/

        $dueEquipments = DB::table('tbl_work_order_items as oi')->select('DE.id','ri.frequency_id','DE.pickup_date','DE.next_due_date','DE.frequency_id as f_id','DE.interval_days')->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as DE','DE.id','=','ri.due_equipments_id')->where('oi.id',$work_order_item_id)->first();
        //print_r($dueEquipments);die;
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
                    $interval_days = (isset($dueEquipments->interval_days)&&$dueEquipments->interval_days)?$dueEquipments->interval_days:30;
                    $next_due_date =date('Y-m-d', strtotime("+" . $interval_days . " days", strtotime(date("Y-m-d"))));
                }
                else {
                    $next_due_date = date('Y-m-t', strtotime("+3 months", strtotime(date("Y-m-d"))));
                }
            }
            $save_due_equip['id'] = $dueEquipments->id;
            $save_due_equip['last_cal_date'] = date('Y-m-d');
            //$save_due_equip['next_due_date'] = $next_due_date;
            //$save_due_equip['calibrate_process'] = 0;
            $this->serviceModel->saveDueEqu($save_due_equip);

        }

        /*End*/



        $outside_calibrated = (isset($reqInputs['outside_calibrated']) && isset($reqInputs['outside_calibrated']))?$reqInputs['outside_calibrated']:'';
        if($outside_calibrated==1)
        {
            self::$workorder_item_id= $work_order_item_id;
            self::$user_id = $this->uid;
            $this->outsidecalibration->saveascalibratedoutside($user);
            return Response::json([
                'status' => 1,
                'msg' => 'As Calibrated has been updated'
            ], 200);
        }

        if($work_order)
        {
            if($work_order->water_temperature=='' && $work_order->relevent_humidity=='' && $work_order->barometric_pressure=='' && $work_order->air_dencity=='' && $work_order->z_factor=='' && $work_order->liquid_dencity=='')
            {

                $savew['id'] = $work_order->id;
                $savew['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
                $savew['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
                $savew['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
                $savew['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
                $savew['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
                $savew['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';
                $this->workorderProcess->saveWorkOrder($savew);
            }
        }
        $balance_device_id = (isset($reqInputs['balance']) && isset($reqInputs['balance']))?$reqInputs['balance']:'';
        $barometer_device_id = (isset($reqInputs['digital_barometer']) && isset($reqInputs['digital_barometer']))?$reqInputs['digital_barometer']:'';
        $thermometer_device_id = (isset($reqInputs['digital_thermometer']) && isset($reqInputs['digital_thermometer']))?$reqInputs['digital_thermometer']:'';
        $thermocouple_device_id = (isset($reqInputs['thermocouple']) && isset($reqInputs['thermocouple']))?$reqInputs['thermocouple']:'';
        $balance_device_serial_no = '';
        $barometer_device_serial_no = '';
        $thermometer_device_serial_no = '';
        $thermocouple_device_serial_no = '';
        $balance_sensitivity = '';
        $balance_units = '';
        if($balance_device_id)
        {
            $balance_device = $this->workorder->getdevice($balance_device_id);
            $balance_device_serial_no = ($balance_device && isset($balance_device->serial_no))?$balance_device->serial_no:'';
            $balance_sensitivity = ($balance_device && isset($balance_device->sensitivity_name))?$balance_device->sensitivity_name:'';
            $balance_units = ($balance_device && isset($balance_device->unit))?$balance_device->unit:'';
        }
        if($barometer_device_id)
        {
            $barometer_device = $this->workorder->getdevice($barometer_device_id);
            $barometer_device_serial_no = ($barometer_device && isset($barometer_device->serial_no))?$barometer_device->serial_no:'';
        }
        if($thermometer_device_id)
        {
            $thermometer_device = $this->workorder->getdevice($thermometer_device_id);
            $thermometer_device_serial_no = ($thermometer_device && isset($thermometer_device->serial_no))?$thermometer_device->serial_no:'';
        }
        if($thermocouple_device_id)
        {
            $thermocouple_device = $this->workorder->getdevice($thermocouple_device_id);
            $thermocouple_device_serial_no = ($thermocouple_device && isset($thermocouple_device->serial_no))?$thermocouple_device->serial_no:'';
        }

        if($work_order)
        {
            if($work_order->water_temperature_calibrated=='' && $work_order->relevent_humidity_calibrated=='' && $work_order->barometric_pressure_calibrated=='' && $work_order->air_dencity_calibrated=='' && $work_order->z_factor_calibrated=='' && $work_order->liquid_dencity_calibrated=='')
            {

                $savew['id'] = $work_order->id;
                $savew['water_temperature_calibrated'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
                $savew['relevent_humidity_calibrated'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
                $savew['barometric_pressure_calibrated'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
                $savew['air_dencity_calibrated'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
                $savew['z_factor_calibrated'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
                $savew['liquid_dencity_calibrated'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';

                $savew['balance_device_id'] = $balance_device_id;
                $savew['barometer_device_id'] = $barometer_device_id;
                $savew['thermometer_device_id'] = $thermometer_device_id;
                $savew['thermocouple_device_id'] = $thermocouple_device_id;
                $savew['balance_device_serial_no'] = $balance_device_serial_no;
                $savew['barometer_device_serial_no'] = $barometer_device_serial_no;
                $savew['thermometer_device_serial_no'] = $thermometer_device_serial_no;
                $savew['thermocouple_device_serial_no'] = $thermocouple_device_serial_no;
                $savew['balance_sensitivity'] = $balance_sensitivity;
                $savew['balance_units'] = $balance_units;
                $this->workorderProcess->saveWorkOrder($savew);
            }
        }

        $savestatus['id'] = false;
        $savestatus['workorder_item_id'] = $work_order_item_id;
        $savestatus['workorder_status'] = 3;
        $savestatus['water_temperature'] = (isset($reqInputs['water_temperature']) && isset($reqInputs['water_temperature']))?$reqInputs['water_temperature']:'';
        $savestatus['relevent_humidity'] = (isset($reqInputs['relevent_humidity']) && isset($reqInputs['relevent_humidity']))?$reqInputs['relevent_humidity']:'';
        $savestatus['barometric_pressure'] = (isset($reqInputs['barometric_pressure']) && isset($reqInputs['barometric_pressure']))?$reqInputs['barometric_pressure']:'';
        $savestatus['air_dencity'] = (isset($reqInputs['air_dencity']) && isset($reqInputs['air_dencity']))?$reqInputs['air_dencity']:'';
        $savestatus['z_factor'] = (isset($reqInputs['z_factor']) && isset($reqInputs['z_factor']))?$reqInputs['z_factor']:'';
        $savestatus['liquid_dencity'] = (isset($reqInputs['liquid_dencity']) && isset($reqInputs['liquid_dencity']))?$reqInputs['liquid_dencity']:'';

        $savestatus['balance_device_id'] = $balance_device_id;
        $savestatus['barometer_device_id'] = $barometer_device_id;
        $savestatus['thermometer_device_id'] = $thermometer_device_id;
        $savestatus['thermocouple_device_id'] = $thermocouple_device_id;
        $savestatus['balance_device_serial_no'] = $balance_device_serial_no;
        $savestatus['barometer_device_serial_no'] = $barometer_device_serial_no;
        $savestatus['thermometer_device_serial_no'] = $thermometer_device_serial_no;
        $savestatus['thermocouple_device_serial_no'] = $thermocouple_device_serial_no;
        $savestatus['balance_sensitivity'] = $balance_sensitivity;
        $savestatus['balance_units'] = $balance_units;
        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,$this->uid);

        $deletequery = DB::table('tbl_workorder_ascalibrated_log')->where('workorder_status_id',$save_status_updation)->delete();

        if($channels)
        {
            $testPoints = $channels;
            if($testPoints)
            {
                foreach($testPoints as $tkey=>$trow)
                {
                    $document = (isset($trow['reading_file']) && $trow['reading_file'])?$trow['reading_file']:'';
                    $mainPath = public_path() . '/technician/as_calibrated/'; //print_r($mainPath);die;
                    $location = $mainPath;
                    $trimmedLocation = str_replace('\\', '/', $location);
                    if ($document) {
                        $offset1 = strpos($document, ',');
                        $tmp = base64_decode(substr($document, $offset1));
                        $memType = $this->_file_mime_type($tmp);
                        $fileType = explode('/', $memType);
                        $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                        $docuName = 'Readingdocs' . '-' . uniqid() . '.' . $fileType;

                        //    image upload
                        //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                        $filepath = $trimmedLocation;
                        if (!is_dir($filepath)) {
                            return Response::json([
                                'status' => 0,
                                'message' => 'The path you given was invalid'
                            ], 400);
                        }
                        $uploadedFile = file_put_contents($filepath . '/' . $docuName, $tmp);

                        if ($uploadedFile) {
                            $document_name = $docuName;
                        } else {
                            $document_name = '';
                        }
                    }
                    else
                    {
                        $document_name = '';
                    }

                    $savelog['id'] = false;
                    $savelog['workorder_status_id'] = $save_status_updation;
                    $savelog['reading_channel'] = $trow['channel_id'];
                    $savelog['test_point_id'] = $trow['test_point_id'];
                    $savelog['sample_readings'] = json_encode($trow['sample_reading']);
                    $savelog['reading_mean'] = $trow['reading_mean'];
                    $savelog['reading_sd'] = $trow['reading_sd'];
                    $savelog['reading_unc'] = $trow['reading_unc'];
                    $savelog['reading_mean_volume'] = $trow['reading_mean_volume'];
                    $savelog['reading_accuracy'] = $trow['reading_accuracy'];
                    $savelog['reading_precision'] = $trow['reading_precision'];
                    $savelog['reading_status'] = $trow['reading_status']=='pass'?1:0;
                    $savelog['reading_document'] = $document_name;
                    $savelog['cali_date'] = date('Y-m-d');
                    $savelog['target_data'] = $trow['target'];
                    $save_status_log = $this->workorderProcess->save_ascalibrated_log($savelog,$this->uid);
                }
            }

        }



        return Response::json([
            'status' => 1,
            'msg' => 'As Calibrated has been updated'
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


    function testpointcalculation(Request $request)
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
            'id' => $reqInputs['id'],
            'test_point' => $reqInputs['test_point'],
            'water_temperature' => $reqInputs['water_temperature'],
            'relevent_humidities' => $reqInputs['relevent_humidities'],
            'barometric_pressure' => $reqInputs['barometric_pressure'],
            'air_density' => $reqInputs['air_density'],
            'z_factor' => $reqInputs['z_factor'],
            'liquid_density' => $reqInputs['liquid_density'],
            'sensitivity' => isset($reqInputs['sensitivity'])?$reqInputs['sensitivity']:'',
            'weights' => $reqInputs['weights']
        ];
        $rules = array(
            'id' => 'required',
            'test_point' => 'required',
            'water_temperature' => 'required',
            'relevent_humidities' => 'required',
            'barometric_pressure' => 'required',
            'air_density' => 'required',
            'z_factor' => 'required',
            'sensitivity' => 'required',
            'liquid_density' => 'required'
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
        if($result)
        {

            if(empty($input['weights']))
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Test points weights are required'
                ], 400);
            }

            $volume = array();
            $weights = $input['weights'];
            $z_factor = $input['z_factor'];
            $weight_total = 0;
            $readings = count($weights);
            $params = array();
            $sum_deviation_value = 0;
            $sensitivity_value = $input['sensitivity']*1000;
            $sensitivity_explode = explode('.',$sensitivity_value);
            $sensitivity = strlen($sensitivity_explode[1]);
            foreach ($weights as $key=>$row)
            {
                if($row['weight'])
                {
                    $original_value = $row['weight']*$z_factor;
                    $value = round($original_value,$sensitivity);
                }
                else
                {
                    $value = 'Weight not specified';
                }
                $volume[$key]['sample_weight'] = $row['weight'];
                $volume[$key]['sample_volume'] = $value;
                $weight_total += $row['weight'];

            }

            $params['mean_weight'] = $weight_total/$readings;
            $actual_mean_volume = $params['mean_weight']*$z_factor;
            $params['mean_volume'] = round(($params['mean_weight']*$z_factor),2);
            //print_r($weights);die;
            foreach ($weights as $key1=>$row1)
            {

                $a = round(($row1['weight'] -  $params['mean_weight']),3);
                if($a<0)
                {
                    $b = str_replace('-','',$a);
                }
                else
                {
                    $b = $a;
                }
                $c = $b*$b;
                $sum_deviation_value += $c;
            }

            $mean_weight_n = count($weights)-1;
            if($mean_weight_n>0)
            {
                $sqr_rt = $sum_deviation_value/(count($weights) - 1);
                $sd_value = sqrt($sqr_rt)*$z_factor;
                $sd_round_value = round($sd_value,5);
            }

            $volume_id = (isset($result->volume) && $result->volume) ? $result->volume : '0';
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
                       // $tolerance = $spec->get();
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
                      //  $tolerance = $spec->get();
                    }



                    break;
                case 2:
                    $spec = DB::table('tbl_limit_tolerance');
                    $spec->join('tbl_equipment_model','tbl_equipment_model.id','=','tbl_limit_tolerance.model_id');
                    $spec->where('tbl_equipment_model.id','=',$result->equipment_model_id);
                    $spec->select('tbl_limit_tolerance.*');
                    break;
                default:
                    $spec = DB::table('tbl_limit_tolerance');
                    $spec->join('tbl_equipment_model','tbl_equipment_model.id','=','tbl_limit_tolerance.model_id');
                    $spec->where('tbl_equipment_model.id','=',$result->equipment_model_id);
                    $spec->select('tbl_limit_tolerance.*');
                    break;
            }

            $tolerence = $spec->get();
            //echo'<pre>';print_r($tolerence);'</pre>';die;
            $target_volume = 100;
            if($input['test_point']==1)
            {
                $limits = $tolerence[0];
                $target_volume = $tolerence[0]->target_value;
            }
            elseif($input['test_point']==2)
            {
                $limits = $tolerence[1];
                $target_volume = $tolerence[1]->target_value;
            }
            else
            {
                $limits = $tolerence[2];
                $target_volume = $tolerence[2]->target_value;
            }

            if($mean_weight_n<=0)
            {
                $params['standard_deviation'] = 'N/A';
                $params['precison_cv_percentage'] = 'N/A';
            }
            else
            {
                $params['standard_deviation'] = $sd_round_value;
                //$params['precison_cv_percentage'] = round(($params['standard_deviation']*$limits->target_value)/$params['mean_volume'],5);
                $params['precison_cv_percentage'] = abs(round(($params['standard_deviation']*100)/$params['mean_volume'],5));
            }
            //$params['accuracy_E_percentage'] = round((($actual_mean_volume-$limits->target_value)*($limits->target_value/100)),5);
            //$params['accuracy_E_percentage'] = round((($actual_mean_volume-100)*(100/100)),5);
            $params['accuracy_E_percentage'] = abs(round((($actual_mean_volume-$target_volume)*100)/$target_volume,5));
            //echo'<pre>';print_r($params);'</pre>';die;


           // $target_volume = $limits->target_value;
           // print_r($target_volume);die;

            $actual_accuracy = $limits->accuracy;
            $actual_precision = $limits->precision;
            $accuracy_upper_limit = $target_volume+($target_volume*$actual_accuracy);
            $accuracy_lower_limit = $target_volume-($target_volume*$actual_accuracy);
            $precision_upper_limit =$target_volume+($target_volume*$actual_precision);
            $precision_lower_limit = $target_volume-($target_volume*$actual_precision);


            $status = 'fail';
            $check_status = array(); //print_r($params);die;

            if($volume)
            {
                foreach ($volume as $vkey=>$vrow)
                {
                    $check_status[$vkey] = $vrow;
                    if(($vrow['sample_volume']<=$accuracy_upper_limit && $vrow['sample_volume']>=$accuracy_lower_limit) && ($vrow['sample_volume']<=$precision_upper_limit && $vrow['sample_volume']>=$precision_lower_limit))
                    {
                        $check_status[$vkey]['status'] = 'pass';
                    }
                    else
                    {
                        $check_status[$vkey]['status'] = 'fail';
                    }
                }
            }


            if($actual_accuracy<$params['accuracy_E_percentage'] || $actual_precision<$params['precison_cv_percentage'])
            {
                $params['status'] = 'fail';
                return Response::json([
                    'status' => 1,
                    'data' => $volume,
                    'values'=>$params,
                    'check_status'=>$check_status

                ], 200);
            }
            if($volume)
            {
               foreach ($volume as $vkey=>$vrow)
               {
                   $check_status[$vkey] = $vrow;
                   if(($vrow['sample_volume']<=$accuracy_upper_limit && $vrow['sample_volume']>=$accuracy_lower_limit) && ($vrow['sample_volume']<=$precision_upper_limit && $vrow['sample_volume']>=$precision_lower_limit))
                   {
                       $check_status[$vkey]['status'] = 'pass';
                   }
                   else
                   {
                       $check_status[$vkey]['status'] = 'fail';
                   }
               }
               if($check_status)
               {
                   if(in_array('fail', array_column($check_status, 'status'))) { // search value in the array
                       $status = 'fail';
                   }
                   else
                   {
                       $status = 'pass';
                   }
               }
            }

            $params['status'] = $status;

            return Response::json([
                'status' => 1,
                'data' => $volume,
                'values'=>$params,
                'check_status'=>$check_status

            ], 200);


        }

    }

    function workordermove(Request $request)
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
            'work_order_status' => $reqInputs['work_order_status']
        ];
        $rules = array(
            'work_order_id' => 'required|not_in:0',
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
        $workorder_id = $input['work_order_id'];
        $workorder_status = $input['work_order_status'];
        $work_order = $this->workorder->workorder($workorder_id);
        $workorder_items = $this->workorder->totalInstruments($workorder_id);
        $workorder_items_move = $this->workorder->totalInstrumentsWorkOrderProcess($workorder_id,$workorder_status);
        if($workorder_items!=$workorder_items_move)
        {
            return Response::json([
                'status' => 0,
                'message' => 'Some Instruments are not completed'
            ], 200);
        }

        $work_order_items_data = $this->workorderProcess->getWorkOrderItems($workorder_id);

        if($workorder_status==1)
        {
           $next_step = '';
            if($work_order_items_data)
            {
                foreach ($work_order_items_data as $key=>$row)
                {
                    $save_workorder_status['id'] = $row->id;
                    $save_workorder_status['as_found_status'] = 'completed';
                    $save_workorder_status['maintenance_status'] = 'progress';
                    $this->workorderitemmove->saveWorkOrderItemStatus($save_workorder_status);
                }
            }
           if($work_order->maintanence_to==$this->tid)
           {
               $next_step = 2;
               $save['id'] = $workorder_id;
               $save['status'] = $next_step;
               $this->workorder->saveWorkOrderStatus($save);
               return Response::json([
                   'status' => 1,
                   'next_step'=>$next_step,
                   'message' => 'Next process is maintenance',
                   'work_order_id'=>$workorder_id
               ], 200);
           }
           else
           {
               $next_step = 2;
               $save['id'] = $workorder_id;
               $save['status'] = $next_step;
               $this->workorder->saveWorkOrderStatus($save);
               return Response::json([
                   'status' => 1,
                   'next_step'=>$next_step,
                   'message' => 'Next process is assigned to others',
                   'work_order_id'=>$workorder_id
               ], 200);
           }

        }
        elseif ($workorder_status==2)
        {
            $next_step = '';
            if($work_order->as_calibrated==1)
            {
                if($work_order_items_data)
                {
                    foreach ($work_order_items_data as $key=>$row)
                    {
                        $save_workorder_status['id'] = $row->id;
                        $save_workorder_status['maintenance_status'] = 'completed';
                        $save_workorder_status['as_calibrated_status'] = 'progress';
                        $this->workorderitemmove->saveWorkOrderItemStatus($save_workorder_status);
                    }
                }

                if($work_order->calibration_to==$this->tid)
                {
                    $next_step = 3;
                    $save['id'] = $workorder_id;
                    $save['status'] = $next_step;
                    $this->workorder->saveWorkOrderStatus($save);
                    return Response::json([
                        'status' => 1,
                        'next_step'=>$next_step,
                        'message' => 'Next process is calibration',
                        'work_order_id'=>$workorder_id
                    ], 200);
                }
                else
                {
                    $next_step = 3;
                    $save['id'] = $workorder_id;
                    $save['status'] = $next_step;
                    $this->workorder->saveWorkOrderStatus($save);
                    return Response::json([
                        'status' => 1,
                        'next_step'=>$next_step,
                        'message' => 'Next process is assigned to others',
                        'work_order_id'=>$workorder_id
                    ], 200);
                }


            }
            else
            {
                if($work_order_items_data)
                {
                    foreach ($work_order_items_data as $key=>$row)
                    {
                        $save_workorder_status['id'] = $row->id;
                        $save_workorder_status['maintenance_status'] = 'completed';
                        $save_workorder_status['as_calibrated_status'] = 'completed';
                        $save_workorder_status['despatched_status'] = 'progress';
                        $this->workorderitemmove->saveWorkOrderItemStatus($save_workorder_status);
                    }
                }

                $next_step = 4;
                $save['id'] = $workorder_id;
                $save['status'] = $next_step;
                $this->workorder->saveWorkOrderStatus($save);
                return Response::json([
                    'status' => 1,
                    'next_step'=>$next_step,
                    'message' => 'Next process is dispatched',
                    'work_order_id'=>$workorder_id
                ], 200);
            }

        }
        else
        {
            if($work_order_items_data)
            {
                foreach ($work_order_items_data as $key=>$row)
                {
                    $save_workorder_status['id'] = $row->id;
                    $save_workorder_status['as_calibrated_status'] = 'completed';
                    $save_workorder_status['despatched_status'] = 'progress';
                    $this->workorderitemmove->saveWorkOrderItemStatus($save_workorder_status);
                }
            }
            $next_step = 4;
            $save['id'] = $workorder_id;
            $save['status'] = $next_step;
            $this->workorder->saveWorkOrderStatus($save);
            return Response::json([
                'status' => 1,
                'next_step'=>$next_step,
                'message' => 'Next process is dispatched',
                'work_order_id'=>$workorder_id
            ], 200);
        }


    }

    public function saveasdespatched(Request $request)
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
        $despatch_items = (isset($reqInputs['despatched_items']) && isset($reqInputs['despatched_items']))?$reqInputs['despatched_items']:'';
        $work_order_item_id = $despatch_items[0]['id'];
        $work_order_item = DB::table('tbl_work_order_items as oi')->select('work_order_id','technician_review')->where('id',$work_order_item_id)->get()->first();
        $allWorkorderItems = DB::table('tbl_work_order_items as oi')->select('work_order_id')->where('work_order_id',$work_order_item->work_order_id)->get()->count();
        if($despatch_items)
        {
            $outside_calibrated = (isset($despatch_items[0]['outside_calibrated']) && isset($despatch_items[0]['outside_calibrated']))?$despatch_items[0]['outside_calibrated']:'';
            if($outside_calibrated==1)
            {
                self::$workorder_item_id= $work_order_item_id;
                self::$user_id = $this->uid;
                self::$despatch_items = $despatch_items;
                $this->outsidecalibration->saveasdespatchoutside($user);
                return Response::json([
                    'status' => 1,
                    'msg' => 'Despatch has been completed'
                ], 200);
            }

            /*For check individual report generated*/

//            if(!$work_order_item->technician_review)
//            {
//                return Response::json([
//                    'status' => 0,
//                    'msg' => 'Report needs to be reviewed to proceed further'
//                ], 400);
//            }

            foreach ($despatch_items as $key=>$row)
            {
                $getStatusDespatch = $this->workorderProcess->getWorkOrderProcessStatus($row['id'],5);
               if(!$getStatusDespatch)
               {
                   if($work_order_item->technician_review)
                   {
                       $save['id'] = '';
                       $save['workorder_item_id'] = $row['id'];
                       $save['workorder_status'] = 5;
                       $save['water_temperature'] = '';
                       $save['relevent_humidity'] = '';
                       $save['barometric_pressure'] = '';
                       $save['air_dencity'] = '';
                       $save['z_factor'] = '';
                       $save['liquid_dencity'] = '';
                       $workorder_status_id = $this->workorderProcess->save_workorder_status_updation($save,$this->uid);
                       $savelog['id'] = '';
                       $savelog['workorder_status_id'] = $workorder_status_id;
                       $savelog['external_cleaning'] = $row['external_cleaning'];
                       $savelog['labels'] = $row['labels'];
                       $this->workorderProcess->save_asdespatch_log($savelog,$this->uid);
                   }


                   $serice_request_items = DB::table('tbl_work_order_items as oi')
                       ->select('oi.request_item_id')
                   ->where('oi.id','=',$row['id'])->get()->first();
//                   if($serice_request_items)
//                   {
//                      $save_request['id'] = $serice_request_items->request_item_id;
//                      $save_request['is_calibrated'] = 1;
//                      $this->workorderProcess->save_request_item($save_request,$this->uid);
//                   }
               }
                $saveworkorderitem['id'] = $row['id'];
                if($work_order_item->technician_review)
                {
                    $saveworkorderitem['despatched_status'] = 'completed';
                }

                $this->workorderitemmove->saveWorkOrderItemStatus($saveworkorderitem);

            }
        }

        return Response::json([
            'status' => 1,
            'msg' => 'Despatch has been completed'
        ], 200);

    }

}